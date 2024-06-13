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

// Ambil data gambar dari database
$sql = "SELECT face_id, image_data FROM absensi";
$result = $conn->query($sql);

// Tampilkan data gambar
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div>';
        echo '<h3>' . htmlspecialchars($row['face_id']) . '</h3>';
        echo '<img src="data:image/jpeg;base64,' . $row['image_data'] . '" alt="Image" />';
        echo '</div>';
    }
} else {
    echo "Tidak ada data gambar.";
}

// Tutup koneksi database
$conn->close();
?>
