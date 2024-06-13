<?php
header('Content-Type: application/json');

// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ta_absensi";

// Buat koneksi
$koneksi = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($koneksi->connect_error) {
    die(json_encode(["error" => "Koneksi ke database gagal: " . $koneksi->connect_error]));
}

// Periksa apakah parameter 'tanggal' ada dan tidak kosong
if (isset($_GET['tanggal']) && !empty($_GET['tanggal'])) {
    $tanggal = $_GET['tanggal'];
    $user = $_GET['user'];

    // Escape the 'tanggal' parameter to prevent SQL injection
    $tanggal_escaped = $koneksi->real_escape_string($tanggal);

    // Format the date to ensure it's valid
    $tanggal_formatted = date('Y-m-d', strtotime($tanggal_escaped));

    // Query the database
    $sql = "SELECT *
            FROM `absensi`
            INNER JOIN `karyawan` ON `absensi`.`karyawan_id` = `karyawan`.`karyawan_id`
            INNER JOIN `jabatan` ON `karyawan`.`jabatan_id` = `jabatan`.`jabatan_id`
            WHERE `tgl` = '$tanggal_formatted' AND `absensi`.`face_id` = '$user'";
    
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        echo json_encode($data);
    } else {
        echo json_encode(["message" => "Tidak ada data absensi untuk tanggal yang dipilih."]);
    }
} else {
    echo json_encode(["error" => "Tanggal tidak valid."]);
}

$koneksi->close();
?>
