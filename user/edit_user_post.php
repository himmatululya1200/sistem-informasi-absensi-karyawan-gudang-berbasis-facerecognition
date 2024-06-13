<?php
include '../config/db.php'; // Sesuaikan dengan nama file koneksi Anda


$id = $_POST['admin_id'];
$admin_username = $_POST['admin_username'];
$admin_password = $_POST['admin_password'];
$admin_nama = $_POST['admin_nama'];

// Query untuk melakukan pembaruan data
$update_query = mysqli_query($koneksi, "UPDATE admin SET admin_username='$admin_username', admin_password='$admin_password', admin_nama='$admin_nama' WHERE admin_id='$id'");

header('Location:../edit_data.php?page=Data User');
