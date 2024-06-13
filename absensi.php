<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ta_absensi";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Terima data dari POST request
$face_id = $_POST['face_id'] ?? ''; // Ambil ID nama dari POST request
$image_base64 = $_POST['image'] ?? ''; // Ambil data gambar dalam format Base64 dari POST request
$checkindate = $_POST['checkindate'] ?? ''; // Ambil tanggal dari POST request
$checkintime = $_POST['checkintime'] ?? ''; // Ambil waktu dari POST request

// Periksa apakah data POST telah diterima
if (!empty($face_id) && !empty($image_base64) && !empty($checkindate) && !empty($checkintime)) {
    // Hapus prefix 'data:image/jpeg;base64,' jika ada
    if (strpos($image_base64, 'data:image/jpeg;base64,') === 0) {
        $image_base64 = substr($image_base64, 23);
    }

    // Bersihkan data input untuk menghindari injeksi SQL
    $face_id = $conn->real_escape_string($face_id);
    $image_base64 = $conn->real_escape_string($image_base64);
    $checkindate = $conn->real_escape_string($checkindate);
    $checkintime = $conn->real_escape_string($checkintime);

    // Decode Base64 menjadi data biner
    $image_data = base64_decode($image_base64);

    // Tentukan path untuk menyimpan gambar
    $image_path = 'uploads/' . $face_id . '_' . date('Ymd_His') . '.jpg';
    $nameImage = $face_id . '_' . date('Ymd_His') . '.jpg';

    // Simpan gambar ke file
    if (file_put_contents($image_path, $image_data)) {
        // Get employee data where face_id matches
        $sql_karyawan = "SELECT * FROM karyawan WHERE face_id = '$face_id'";
        $result_karyawan = $conn->query($sql_karyawan);

        if ($result_karyawan->num_rows > 0) {
            // Fetch employee data
            $karyawan = $result_karyawan->fetch_assoc();
            $karyawan_id = $karyawan['karyawan_id'];
            // Check if a record for today already exists
            $sql_check = "SELECT * FROM absensi WHERE face_id = '$face_id' AND tgl = '$checkindate'";
            $result = $conn->query($sql_check);

            if ($result->num_rows > 0 && strtotime($checkintime) >= strtotime('17:00:00') && strtotime($checkintime) <= strtotime('17:30:00')) {
                // Update the existing record with pulang time and foto_pulang
                $sql_update = "UPDATE absensi SET pulang = '$checkintime', foto_pulang = '$nameImage', updated_at = NOW() WHERE tgl = '$checkindate' AND karyawan_id = '$karyawan_id' AND face_id = '$face_id'";
                if ($conn->query($sql_update) === TRUE) {
                    echo "Data berhasil diperbarui.";
                } else {
                    echo "Error: " . $sql_update . "<br>" . $conn->error;
                }
            } else {

                // Get the current time
                $current_time = date('H:i:s');

                // Define the start and end times for allowed check-in
                $start_time = '06:30:00';
                $end_time = '07:30:00';

                if ($current_time >= $start_time && $current_time <= $end_time) {
                    // Simpan informasi ke database
                    if ($result->num_rows == 0) {
                        $sql = "INSERT INTO absensi (`karyawan_id`,`face_id`,`tgl`, `masuk`, `foto_masuk`,`pulang`, `foto_pulang`, `status`, `created_at`, `updated_at`) VALUES ('$karyawan_id','$face_id','$checkindate', '$checkintime', '$nameImage', '00:00:00' , NULL, 'Hadir Masuk',NOW(),NOW() )";
                        if ($conn->query($sql) === TRUE) {
                            echo "Data berhasil disimpan.";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                    }
                }
            }
        }
    } else {
        echo "Gagal menyimpan gambar.";
    }
} else {
    echo "Data POST tidak lengkap.";
}

// Tutup koneksi
$conn->close();
