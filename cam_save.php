<?php
require_once("./config/db.php");
require_once("./config/function.php");
// if (isset($_POST['absen']) && $_POST['imgBase64'] && $_SERVER['HTTP_REFERER']) {
if (isset($_POST['nik']) && $_POST['imgBase64']) {
    $nik = $_POST['nik'];
    $rawData = $_POST['imgBase64'];

    if (!strlen($nik) || !strlen($nik)) {
        die(json_encode(array(
            'status' => false, 
            'message' => 'Data tidak lengkap'
        )));
    }
    /// mengambil data karyawan.id, jabatan_id, absen_unique by
    $sql = "SELECT `karyawan`.`jabatan_id`, `karyawan`.`karyawan_id`, `karyawan` 
            FROM `karyawan` 
            WHERE `karyawan` = '$nik';";
    $result = $koneksi->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $karyawan_id = $row['karyawan_id'];
            $jabatan_id = $row['jabatan_id'];
        }
        /// mencari jadwal
        $hari = hari_indonesia(date("D"));
        $sql = "SELECT * FROM `jadwal` WHERE `jabatan_id` = '$jabatan_id' AND `jadwal_hari` = '$hari'";
        $result = $koneksi->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $jadwal_id = $row["jadwal_id"];
                $jadwal_hari = $row["jadwal_hari"];
                $jadwal_masuk = $row["jadwal_masuk"];
                $jadwal_pulang = $row["jadwal_pulang"];
            }

            /// Jam dan Waktu sekarang
            $tanggal_sekarang = date("Y:m:d");
            $jam_sekarang = date("H:i:s");

            /// mencari apakah sudah absen masuk atau belum
            $sql = "SELECT * FROM `rekap` WHERE `jadwal_id` = '$jadwal_id' AND `karyawan_id` = '$karyawan_id'";
            $result = $koneksi->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $rekap_id = $row["rekap_id"];
                    $rekap_keluar = $row["rekap_keluar"];
                }

                if($rekap_keluar != NULL) {
                    // Kamu sudah absen pulang
                    die(json_encode(array(
                        'status' => false, 
                        'message' => 'Kamu sudah absen pulang'
                    )));
                }

                // Kondisi saat absen keluar ( Tidak berlaku untuk absen masuk )
                // Ketika `jam` saat absen keluar belum sama dengan `jam pulang` = Tidak bisa absen
                // Ketika `jam` saat absen keluar sama dengan atau lebih dari `jam pulang` = Pulang (Hadir)
                // Ketika `jam` saat absen keluar lebih dari `satu jam sesudah jam pulang` = Lembur

                // menambahkan satu jam dari `jam pulang`
                $datetime = new DateTime($jadwal_pulang);
                $datetime->modify('+1 hour');
                $jam_jangkawaktu = $datetime->format('H:i:s');

                if (strtotime($jam_sekarang) <= strtotime($jadwal_pulang)) {
                    // Belum saatnya pulang
                    die(json_encode(array(
                        'status' => false, 
                        'message' => 'Belum saatnya pulang'
                    )));
                } else if(strtotime($jam_sekarang) >= strtotime($jadwal_pulang)) {
                    /// Image Process
                    $filteredData = explode(',', $rawData);

                    $unencoded = base64_decode($filteredData[1]);
                    $randomName = uniqid(rand()).'.png';

                    $folderPath = 'image';
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath);
                    }

                    $file = @fopen('./image/'.$randomName, "w");
                    if ($file != false){
                        fwrite($file, $unencoded);
                        fclose($file);
                    }
                    /// END Image Process

                    // Pulang
                    $sql = "UPDATE `rekap` SET `rekap_keluar` = '$jam_sekarang', `rekap_keterangan` = 'Hadir Pulang', `rekap_photokeluar` = '$randomName' WHERE `rekap`.`rekap_id` = '$rekap_id';";
                    if ($koneksi->query($sql) === TRUE) {
                        die(json_encode(array(
                            'status' => true, 
                            'message' => 'Berhasil Absen Pulang'
                        )));
                    }else{
                        /// Query Gagal
                        die(json_encode(array(
                            'status' => false, 
                            'message' => 'Query Gagal : '.$koneksi->error.''
                        )));
                    }
                } else {
                    // Terjadi Kesalahan
                    die(json_encode(array(
                        'status' => false, 
                        'message' => 'Terjadi Kesalahan'
                    )));
                }
                // echo "absen keluar";
            } else  if ($result->num_rows < 1){
                // Kondisi saat absen masuk ( Tidak berlaku untuk absen keluar )
                // Ketika `jam` saat absen masuk antara `satu jam sebelum jam masuk` sampai `jam masuk` = Hadir
                // Ketika `jam` saat absen masuk lebih dari `jam masuk` = Hadir Terlambat
                // Ketika `jam` saat absen masuk kurang dari `jam pulang` = Hadir Terlambat
                // Ketika `jam` saat absen masuk kurang dari `satu jam sebelum jam masuk` = Jadwal sudah selesai / belum mulai
                // Ketika `jam` saat absen masuk lebih dari `jam pulang` = Jadwal sudah selesai / belum mulai

                // mengurangi satu jam dari `jam masuk`
                $datetime = new DateTime($jadwal_masuk);
                $datetime->modify('-1 hour');
                $jam_jangkawaktu = $datetime->format('H:i:s');

                if (strtotime($jam_sekarang) >= strtotime($jam_jangkawaktu) && strtotime($jam_sekarang) <= strtotime($jadwal_masuk)) {
                    /// Image Process
                    $filteredData = explode(',', $rawData);

                    $unencoded = base64_decode($filteredData[1]);
                    $randomName = uniqid(rand()).'.png';

                    $folderPath = 'image';
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath);
                    }

                    $file = @fopen('./image/'.$randomName, "w");
                    if ($file != false){
                        fwrite($file, $unencoded);
                        fclose($file);
                    }
                    /// END Image Process

                    // Hadir
                    $sql = "INSERT INTO `rekap` (`rekap_id`, `jadwal_id`, `karyawan_id`, `rekap_tanggal`, `rekap_masuk`, `rekap_keluar`, `rekap_photomasuk`, `rekap_photokeluar`, `rekap_keterangan`) 
                            VALUES (NULL, '$jadwal_id', '$karyawan_id', '$tanggal_sekarang', '$jam_sekarang', NULL, '$randomName', NULL, 'Hadir Masuk');";
                    if ($koneksi->query($sql) === TRUE) {
                        die(json_encode(array(
                            'status' => true, 
                            'message' => 'Berhasil Absen Masuk'
                        )));
                    }else{
                        /// Query Gagal
                        die(json_encode(array(
                            'status' => false, 
                            'message' => 'Query Gagal : '.$koneksi->error.''
                        )));
                    }
                }else if(strtotime($jam_sekarang) >= strtotime($jadwal_masuk) && strtotime($jam_sekarang) <= strtotime($jadwal_pulang)){
                    /// Image Process
                    $filteredData = explode(',', $rawData);

                    $unencoded = base64_decode($filteredData[1]);
                    $randomName = uniqid(rand()).'.png';

                    $folderPath = 'image';
                    if (!file_exists($folderPath)) {
                        mkdir($folderPath);
                    }

                    $file = @fopen('./image/'.$randomName, "w");
                    if ($file != false){
                        fwrite($file, $unencoded);
                        fclose($file);
                    }
                    /// END Image Process

                    // Hadir Terlambat
                    $sql = "INSERT INTO `rekap` (`rekap_id`, `jadwal_id`, `karyawan_id`, `rekap_tanggal`, `rekap_masuk`, `rekap_keluar`, `rekap_photomasuk`, `rekap_photokeluar`, `rekap_keterangan`) 
                            VALUES (NULL, '$jadwal_id', '$karyawan_id', '$tanggal_sekarang', '$jam_sekarang', NULL, '$randomName', NULL, 'Hadir Terlambat');";
                    if ($koneksi->query($sql) === TRUE) {
                        die(json_encode(array(
                            'status' => true, 
                            'message' => 'Berhasil Absen Masuk'
                        )));
                    }else{
                        /// Query Gagal
                        die(json_encode(array(
                            'status' => false, 
                            'message' => 'Query Gagal : '.$koneksi->error.''
                        )));
                    }
                }else{
                    // Jadwal sudah selesai / belum mulai
                    die(json_encode(array(
                        'status' => false, 
                        'message' => 'Jadwal sudah selesai / belum mulai'
                    )));
                }
            }else{
                /// Terjadi Kesalahan
                die(json_encode(array(
                    'status' => false, 
                    'message' => 'Terjadi kesalahan'
                )));
            }
        } else {
            /// JIka jadwal tidak ditemukan atau hasil query = 0
            die(json_encode(array(
                'status' => false, 
                'message' => 'Jadwal tidak ditemukan'
            )));
        }
    } else {
        /// JIka absen tidak ditemukan atau hasil query = 0
        die(json_encode(array(
            'status' => false, 
            'message' => 'nik belum ada'
        )));
    }
}else{
    /// Jika data tidak lengkap
    die(json_encode(array(
        'status' => false, 
        'message' => 'Data tidak lengkap'
    )));
}