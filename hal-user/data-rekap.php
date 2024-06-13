<?php
$page = "Data Rekap";
require_once("header.php");

// Koneksi ke database
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username database Anda
$password = ""; // Sesuaikan dengan password database Anda
$dbname = "ta_absensi"; // Sesuaikan dengan nama database Anda

$koneksi = new mysqli($servername, $username, $password, $dbname);

if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}


?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Data Rekap Karyawan</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Data Rekap</li>
            </ol>
            <!-- START MESSAGE -->
            <?php
            if (isset($_GET['msg']) && isset($_SERVER['HTTP_REFERER'])) {
                if ($_GET['msg'] == 1) {
            ?>
                    <div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">
                        <strong>Berhasil Menghapus Data Rekap!</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php
                } else if ($_GET['msg'] == 2) {
                ?>
                    <div class="alert alert-danger alert-dismissible fade show text-center h4" role="alert">
                        <strong>Gagal Menghapus Data Rekap!</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
            <?php
                }
            }
            ?>
            <!-- END MESSAGE -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <i class="fas fa-table mr-1"></i>
                        Data Rekap
                    </div>
                    <div class="row">

                        <div class="col-md-auto">
                            <input type="date" class="form-control" name="tanggal" id="tanggal" autocomplete="off">
                        </div>
                        <div class="col">
                            <button id="btn-rekap" class="btn btn-primary">Cari</button>
                            <button id="btn-export" class="btn btn-success no-print">Export</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="print-table">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Tanggal</th>
                                        <th>Masuk</th>
                                        <th>Foto Masuk</th>
                                        <th>Keluar</th>
                                        <th>Foto Keluar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $sql = "SELECT *
                                FROM absensi
                                INNER JOIN karyawan ON absensi.karyawan_id = karyawan.karyawan_id
                                WHERE DATE_FORMAT(absensi.created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE, '%Y-%m') AND absensi.face_id = '$name' ORDER BY  tgl DESC";
                                    $result = $koneksi->query($sql);

                                    while ($row = $result->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?php echo $row['face_id']; ?></td>
                                            <td><?php echo $row['karyawan_nik']; ?></td>
                                            <td><?php echo $row['tgl']; ?></td>
                                            <td><?php echo $row['masuk']; ?></td>
                                            <td>
                                                <?php if (!empty($row['foto_masuk'])) : ?>
                                                    <img src="/uploads/<?php echo $row['foto_masuk']; ?>" class="rounded-circle" width="80" height="80">
                                                <?php else : ?>
                                                    <!-- Optionally, you can add a placeholder image or text if the image is not available -->
                                                    <span class="badge badge-danger">No Image Available</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $row['pulang']; ?></td>
                                            <td>
                                                <?php if (!empty($row['foto_pulang'])) : ?>
                                                    <img src="/uploads/<?php echo $row['foto_masuk']; ?>" class="rounded-circle" width="80" height="80">
                                                <?php else : ?>
                                                    <!-- Optionally, you can add a placeholder image or text if the image is not available -->
                                                    <span class="badge badge-danger">No Image Available</span>
                                                <?php endif; ?>
                                            </td>
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

                                        </tr>
                                    <?php
                                    endwhile;
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
    $koneksi->close();
    require_once("./footer.php");

    ?>
</div>

<script>
    $(document).ready(function() {


        // Set the default value of the date input to today's date
        var today = new Date().toISOString().split('T')[0];
        $('#tanggal').val(today);

        $('#btn-rekap').on('click', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var tanggal = $('#tanggal').val();
            var user = `<?= $name ?>`;
            $.ajax({
                url: 'rekap.php', // The server script to handle the form submission
                type: 'GET', // Use the GET method
                data: {
                    user: user,
                    tanggal: tanggal
                },
                success: function(response) {
                    // Handle the successful response
                    if (response.hasOwnProperty('message')) {
                        // Display the message
                        alert(response.message);
                    } else {
                        // console.log(response);
                        // Clear the existing table data
                        $('#dataTable').DataTable().clear();

                        // Add the new data from the response
                        response.forEach(function(row) {

                            var badgeClass;
                            switch (row.status) {
                                case 'Hadir Masuk':
                                    badgeClass = 'badge-success';
                                    break;
                                case 'Terlambat':
                                    badgeClass = 'badge-warning';
                                    break;
                                case 'Alpa':
                                    badgeClass = 'badge-danger';
                                    break;
                                default:
                                    badgeClass = 'badge-secondary';
                            }
                            var statushtml = '<span class="badge ' + badgeClass + '">' + row.status + '</span>';

                            let imgMasuk = '';
                            if (row.foto_masuk !== null && row.foto_masuk !== '') {
                                imgMasuk = `<img src="/uploads/${row.foto_masuk}" class="rounded-circle" alt="Foto Masuk ${row.face_id}" width="80" height="80">`;
                            } else {
                                imgMasuk = `<span class="badge badge-danger">No Image Available</span>`;
                            }
                            let imgPulang = '';
                            if (row.foto_pulang !== null && row.foto_pulang !== '') {
                                imgPulang = `<img src="/uploads/${row.foto_pulang}" class="rounded-circle" alt="Foto Masuk ${row.face_id}" width="80" height="80">`;
                            } else {
                                imgPulang = `<span class="badge badge-danger">No Image Available</span>`;
                            }

                            $('#dataTable').DataTable().row.add([
                                row.face_id,
                                row.karyawan_nik,
                                row.tgl,
                                row.masuk,
                                imgMasuk,
                                row.pulang,
                                imgPulang,
                                statushtml,
                                '<a href="edit_rekap.php?face_id=' + row.face_id + '" class="btn btn-primary"><i class="fas fa-edit"></i></a>'
                            ]).draw(false);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle any errors
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

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