<?php
$page = "Data Karyawan";
require_once("./header.php");
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Data Karyawan</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Data Karyawan</li>
            </ol>
            <!-- START MESSAGE -->
            <?php
            if (isset($_GET['msg']) && isset($_SERVER['HTTP_REFERER'])) {
                if ($_GET['msg'] == 1 && $_SERVER['HTTP_REFERER']) {
            ?>
                    <div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">
                        <strong>Berhasil Menghapus Data Karyawan!</strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php
                } else if ($_GET['msg'] == 2 && $_SERVER['HTTP_REFERER']) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">
                        <strong>Gagal Menghapus Data Karyawan!</strong>
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
                        Data Karyawan
                    </div>
                    <div>
                        <a href="./tambah_karyawan.php" class="btn btn-info">
                            Tambah
                        </a>
                        <button id="btn-export" class="btn btn-success no-print">Export</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="print-table">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Jabatan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $sql = "SELECT `karyawan`.`karyawan_id`, `karyawan`.`face_id`, `karyawan`.`karyawan_nik`, `karyawan`.`karyawan_jeniskelamin`, `karyawan`.`karyawan_lahir`, `karyawan`.`karyawan_foto`, `jabatan`.`jabatan_nama` 
                                    FROM `karyawan`
                                    INNER JOIN `jabatan` ON `karyawan`.`jabatan_id` = `jabatan`.`jabatan_id`";
                                    $result = $koneksi->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        $karyawan_id = $row['karyawan_id'];
                                        $karyawan_foto = $row['karyawan_foto'];
                                        $face_id = $row['face_id'];
                                        $karyawan_nik = $row['karyawan_nik'];
                                        $karyawan_jeniskelamin = $row['karyawan_jeniskelamin'];
                                        $karyawan_lahir = $row['karyawan_lahir'];
                                        $jabatan_nama = $row['jabatan_nama'];
                                    ?>
                                        <tr>
                                            <td><img src="./image/<?php echo $karyawan_foto ?>" class="rounded-circle" alt="Foto <?php echo $face_id ?>" width="80" height="80"></td>
                                            <td><?php echo $face_id; ?></td>
                                            <td><?php echo $karyawan_nik; ?></td>
                                            <td><?php echo jenis_kelamin($karyawan_jeniskelamin); ?></td>
                                            <td><?php echo format_hari_tanggal($karyawan_lahir, true); ?></td>
                                            <td><?php echo $jabatan_nama; ?></td>
                                            <td class="text-center">
                                                <a href="edit_karyawan.php?karyawan_id=<?php echo $karyawan_id; ?>" class="btn btn-primary m-2"><i class="fas fa-edit"></i> Edit</a>
                                                <a href="hapus_karyawan.php?karyawan_id=<?php echo $karyawan_id; ?>" class="btn btn-danger" onclick="return confirm('Apakah anda yakin?')"><i class="fas fa-trash"></i> Hapus</a>
                                            </td>
                                        </tr>
                                    <?php
                                    }
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