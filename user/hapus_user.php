<?php
include ('../config/db.php');
$id = $_GET['admin_id'];

$query = mysqli_query($koneksi, "DELETE FROM admin WHERE admin_id = '$id'");
header('Location:../users.php?page=Data User');
?>