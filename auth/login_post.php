<?php
session_start();
require_once("../config/db.php");

if(!isset($_POST['username']) || !isset($_POST['password'])){
    header('location:./login.php?msg=1');
    exit();
} else {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM `admin` WHERE `admin_username` = '$username' AND `admin_password` = '$password'";
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        // Berhasil Login
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $username;
        $_SESSION['admin_nama'] = $user['admin_nama']; // Asumsi kolom 'role' ada dalam tabel 'admin'

        if ($user['admin_nama'] == 'User') {
            header('location: ../hal-user/dashboard-user.php');
        } else if ($user['admin_nama'] == 'Administrator') {
            header('location: ../index.php');
        }
        exit();
    } else {
        // Username/Password Salah
        header('location:./login.php?msg=2');
        exit();
    }
}
?>
