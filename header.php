<?php
session_start();
require_once("./config/db.php");
require_once("./config/function.php");
if (!$_SESSION['username']) {
    header('Location: ./auth/login.php');
    exit();
}
$name = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $page; ?> - Absensi</title>
    <link href="./assets/css/bootstrap.css" rel="stylesheet">
    <link href="./assets/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="./assets/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">
    <style>
       @media print {
            body * {
                visibility: hidden;
            }
            #print-table, #print-table * {
                visibility: visible;
            }
            #print-table {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            img {
                max-width: 50px;
                max-height: 50px;
            }
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand" href="./"><img src="image/logo.png" alt="yx" style="width:70px;height:50px;"></a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i
                class="fas fa-bars"></i></button>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        <a class="nav-link <?php echo ($page == 'Dashboard') ? "active" : "" ?>" href="./">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link <?php echo ($page == 'Data User') ? "active" : "" ?>"
                            href="./users.php">
                            <div class="sb-nav-link-icon"><i class="fa fa-user-circle"></i></div>
                            Data User
                        </a>
                        <a class="nav-link <?php echo ($page == 'Data Karyawan') ? "active" : "" ?>"
                            href="./data_karyawan.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Data Karyawan
                        </a>
                        <a class="nav-link <?php echo ($page == 'Data Jabatan') ? "active" : "" ?>"
                            href="./data_jabatan.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-id-card"></i></div>
                            Data Jabatan
                        </a>
                        <a class="nav-link <?php echo ($page == 'Data Jadwal') ? "active" : "" ?>"
                            href="./data_jadwal.php">
                            <div class="sb-nav-link-icon"><i class="fa fa-calendar"></i></div>
                            Data Jadwal
                        </a>
                        <a class="nav-link <?php echo ($page == 'Data Rekap') ? "active" : "" ?>"
                            href="./data_rekap.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                            Data Rekap
                        </a>

                        <a class="nav-link" href="./auth/logout.php" id="logoutLink">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                            Log out
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Masuk sebagai:</div>
                    <?php echo $name; ?>
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
                    window.location.href = './auth/logout.php';
                }
            });
        });
    </script>
        </div>