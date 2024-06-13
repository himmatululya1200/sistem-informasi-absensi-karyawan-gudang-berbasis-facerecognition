<?php
$page = "Dashboard User";
require_once("header.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page; ?></title>
    <link rel="stylesheet" href="../css/p.css"> <!-- Link ke file CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .widget-box {
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
            color: black;
        }

        .widget-title {
            background-color: #f1f1f1;
            padding: 10px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: black;
        }

        .widget-content {
            display: none;
            padding: 10px;
            border-top: 1px solid #ccc;
        }

        .active .widget-content {
            display: block;
        }
    </style>
</head>

<body>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <h1 class="mt-4">Dashboard Karyawan</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="dashboard-user.php">Dashboard Karyawan</a></li>
                </ol>
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </div>
                    <div class="card-body">
                        <div class="quick-actions">
                            <li class="bg_lb">
                                <a href="dashboard-user.php">
                                    <i class="fas fa-home"></i> My Home
                                </a>
                            </li>
                            <li class="bg_ls">
                                <a href="data-rekap.php">
                                    <i class="fas fa-copy"></i> Rekap
                                </a>
                            </li>
                        </div>
                        <div class="widget-box">
                            <div class="widget-title">
                                <span>APA ITU ABSENSI FACE RECOGNITION ?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="widget-content">
                                Absensi Face Recognition adalah sistem absensi yang menggunakan teknologi pengenalan wajah untuk mencatat kehadiran karyawan.
                            </div>
                        </div>

                        <div class="widget-box">
                            <div class="widget-title">
                                <span>APAKAH FITUR ABSENSI FACE RECOGNITION DAPAT DI KUSTOMISASI ?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="widget-content">
                                Absensi Face Recognition sangat fleksibel dan fiturnya dapat di kustomisasi.
                            </div>
                        </div>

                        <div class="widget-box">
                            <div class="widget-title">
                                <span>APA SAJA MANFAAT MENGGUNAKAN ABSENSI FACE RECOGNITION ?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="widget-content">
                                Beberapa keuntungan yang Perusahaan dapatkan dari menggunakan Abseni face recognition antara lain:
                                <ul>
                                    <li>Proses absen yang mudah dan cepat: Karyawan hanya perlu memindai wajah mereka untuk absen, tanpa perlu menyentuh alat absen atau memasukkan kartu.</li>
                                    <li>Karyawan dapat melihat data kehadiran dan informasi penting dari perusahaan.</li>
                                    <li>Memudahkan dalam pekerjaan Departemen HR seperti melihat laporan absensi karyawan berkala, pemantauan kinerja karyawan, membuat rekapitulasi sebagai acuan pembuatan payroll.</li>
                                    <li>Perusahaan dapat mengelola kehadiran karyawanya kapan saja melalui internet.</li>
                                    <li>Memudahkan perusahaan dalam membuat laporan kehadiran karyawan.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $sql = "SELECT *
                        FROM absensi
                        INNER JOIN karyawan ON absensi.karyawan_id = karyawan.karyawan_id
                        WHERE DATE_FORMAT(absensi.created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m')
                        AND absensi.face_id = '$name'
                        AND absensi.status = 'alpa' ORDER BY tgl DESC";

                $result = $koneksi->query($sql);

                if ($result->num_rows >= 10) {
                ?>
                    <div class="card">
                        <div class="card-header" style="background-color: red;">
                            <i class="fas fa-calendar"></i> Peringatan
                        </div>
                        <div class="card-body text-center">
                            <h1>Anda Sudah Tidak Masuk 10 Kali</h1>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </main>
        <?php
        require_once("footer.php");
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const titles = document.querySelectorAll('.widget-title');
            titles.forEach(title => {
                title.addEventListener('click', function() {
                    const currentActive = document.querySelector('.widget-box.active');
                    if (currentActive && currentActive !== this.parentElement) {
                        currentActive.classList.remove('active');
                    }
                    this.parentElement.classList.toggle('active');
                });
            });
        });
    </script>
</body>

</html>