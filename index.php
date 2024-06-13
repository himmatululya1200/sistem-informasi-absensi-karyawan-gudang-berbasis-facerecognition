<?php
$page = "Dashboard";
require_once("./header.php");

// Koneksi ke database
$servername = "localhost";
$username = "root"; // ganti dengan username database Anda
$password = ""; // ganti dengan password database Anda
$dbname = "ta_absensi"; // ganti dengan nama database Anda

$koneksi = new mysqli($servername, $username, $password, $dbname);

if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}


?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb=4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table mr-1"></i>
                    <b>Rekap Absen Hari Ini</b>
                </div>
                <div class="card-body">
                    <div class="col" style="padding: 30px 0;">
                        <button id="btn-export" class="btn btn-success no-print">Export</button>
                    </div>
                    <div id="print-table">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama</th>
                                        <th>Masuk</th>
                                        <th>Foto Masuk</th>
                                        <th>Pulang</th>
                                        <th>Foto Pulang</th>
                                        <th>Status</th>
                                        <!-- <th>Aksi</th> -->
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    // update data
                                    // Update status to 'Terlambat' for employees who arrived late
                                    $sql_update_terlambat = "UPDATE absensi
                                SET status = 'Terlambat'
                                WHERE tgl = CURRENT_DATE
                                AND TIME(masuk) > '07:00:00'
                                AND masuk != '00:00:00'";
                                    $koneksi->query($sql_update_terlambat);

                                    // Update status to 'Hadir Masuk' for employees who arrived on time
                                    $sql_update_masuk = "UPDATE absensi
                                SET status = 'Hadir Masuk'
                                WHERE tgl = CURRENT_DATE
                                AND TIME(masuk) <= '07:00:00'
                                AND masuk != '00:00:00'";
                                    $koneksi->query($sql_update_masuk);


                                    // insert data

                                    // Get yesterday's date
                                    $yesterday = date('Y-m-d', strtotime('-1 day'));

                                    // Get all karyawan IDs
                                    $sql_karyawan = "SELECT karyawan_id FROM karyawan";
                                    $result_karyawan = $koneksi->query($sql_karyawan);
                                    $karyawan_ids = [];

                                    if ($result_karyawan->num_rows > 0) {
                                        while ($row = $result_karyawan->fetch_assoc()) {
                                            $karyawan_ids[] = $row['karyawan_id'];
                                        }
                                    }

                                    // Check and insert data for each karyawan
                                    foreach ($karyawan_ids as $karyawan_id) {
                                        // Check if the karyawan has data for yesterday
                                        $sql_check = "SELECT COUNT(*) AS count FROM absensi WHERE karyawan_id = '$karyawan_id' AND tgl = '$yesterday'";
                                        $result_check = $koneksi->query($sql_check);
                                        $row_check = $result_check->fetch_assoc();
                                        $count = $row_check['count'];

                                        if ($count == 0) {
                                            // Insert new data with status 'Alpa'
                                            $sql_insert = "INSERT INTO absensi (`karyawan_id`, `face_id`, `tgl`, `masuk`, `foto_masuk`, `pulang`, `foto_pulang`, `status`,`created_at`,`updated_at`) 
                                        VALUES ('$karyawan_id', 
                                                (SELECT `face_id` FROM `karyawan` WHERE `karyawan_id` = '$karyawan_id'), 
                                                '$yesterday', '00:00:00', NULL, '00:00:00', NULL, 'Alpa',NOW(),NOW())";
                                            $koneksi->query($sql_insert);
                                        }
                                    }




                                    // Dapatkan semua karyawan
                                    $sql_karyawan = "SELECT karyawan.face_id, karyawan.karyawan_id FROM karyawan";
                                    $result_karyawan = $koneksi->query($sql_karyawan);
                                    $karyawan_data = [];

                                    if ($result_karyawan->num_rows > 0) {
                                        while ($row = $result_karyawan->fetch_assoc()) {
                                            $karyawan_data[$row['face_id']] = $row['karyawan_id'];
                                        }
                                    }

                                    // Dapatkan data absensi hari ini
                                    $sql_absensi = "SELECT *
                                                FROM absensi
                                                LEFT JOIN karyawan ON absensi.face_id = karyawan.face_id
                                                WHERE absensi.tgl = CURRENT_DATE";
                                    $result_absensi = $koneksi->query($sql_absensi);

                                    $absensi_data = [];
                                    if ($result_absensi->num_rows > 0) :
                                        while ($row = $result_absensi->fetch_assoc()) : ?>
                                            <tr>
                                                <td><?php echo $row['tgl']; ?></td>
                                                <td><?php echo $row['face_id']; ?></td>
                                                <td><?php echo $row['masuk']; ?></td>
                                                <td><?php echo !empty($row['foto_masuk']) ? '<img src="./uploads/' . $row['foto_masuk'] . '" alt="Foto Masuk" width="100"/>' : "N/A"; ?></td>
                                                <td><?php echo $row['pulang']; ?></td>
                                                <td><?php echo !empty($row['foto_pulang']) ? '<img src="./uploads/' . $row['foto_pulang'] . '" alt="Foto Keluar" width="100"/>' : "N/A"; ?></td>
                                                <td>
                                                    <?php if ($row['status'] == 'Hadir Masuk') : ?>
                                                        <span class="badge badge-success">Hadir Masuk</span>
                                                    <?php elseif ($row['status'] == 'Terlambat') : ?>
                                                        <span class="badge badge-warning">Terlambat</span>
                                                    <?php elseif ($row['status'] == 'Alpa') : ?>
                                                        <span class="badge badge-danger">Alpa</span>
                                                    <?php else : ?>
                                                        <?php echo $row['status']; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <!-- <td>
                                                <a href="edit_rekap.php?face_id=<?php echo $row['face_id']; ?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
                                            </td> -->
                                            </tr>
                                    <?php
                                        endwhile;
                                    endif;
                                    $koneksi->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php
    require_once("./footer.php");
    ?>
</div>

<script>
    $(document).ready(function() {
        var table = $('#dataTable').DataTable();
        $('#btn-export').click(function() {
            table.destroy(); // Destroy DataTable
            window.print(); // Print the plain HTML table
            location.reload(); // Reload the page to reinitialize DataTable
        });
    });
</script>