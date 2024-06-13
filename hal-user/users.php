<?php
session_start();
require_once("../config/db.php");
require_once("../config/function.php");
if (!$_SESSION['username']) {
    header('Location: ../auth/login.php');
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>User - Absensi</title>
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="../assets/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="#"><img src="../image/logo.png" alt="logo" style="width:70px;height:50px;"></a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i
                class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        <a class="nav-link <?php echo ($page == 'Dashboard User') ? "active" : "" ?>" href="dashboard-user.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link <?php echo ($page == 'Data Rekapan') ? "active" : "" ?>"
                            href="data-rekap.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                            Data Rekap
                        </a>
                        <a class="nav-link" href="../auth/logout.php" id="logoutLink">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                            Log out
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Masuk sebagai:</div>
                    <?php echo $username; ?>
                </div>
            </nav>
            <script>
        document.getElementById('logoutLink').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Anda yakin ingin logout?',
                text: "Anda akan keluar dari sesi ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../auth/logout.php';
                }
            });
        });
    </script>
        </div>