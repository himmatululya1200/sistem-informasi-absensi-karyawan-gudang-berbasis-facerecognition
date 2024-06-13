<?php
include('../config/db.php');
$username = $_GET['admin_username'];
$password = $_GET['admin_password'];
$nama = $_GET['admin_nama'];
$query = mysqli_query($koneksi, "INSERT INTO admin (admin_id,admin_username,admin_password,admin_nama) VALUES ('','$username','$password','$nama')");
header('Location:../users.php?page=Data User');
