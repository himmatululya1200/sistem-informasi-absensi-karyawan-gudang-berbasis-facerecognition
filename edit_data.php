<?php
$page = "Data User";
require_once("./header.php");
$id = $_GET['admin_id'];

// Query untuk mendapatkan data mahasiswa berdasarkan ID
$query = mysqli_query($koneksi, "SELECT * FROM admin WHERE admin_id = '$id'");
$nama = mysqli_fetch_array($query);

?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid">
            <h1 class="mt-4">Edit Data User</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="./users.php">Data User</a></li>
                <li class="breadcrumb-item active">Edit Data User</li>
            </ol>
            <!-- START MESSAGE -->
            <div id="response">
                <?php
                if (isset($_GET['msg']) && isset($_SERVER['HTTP_REFERER'])) {
                    if ($_GET['msg'] == 1 && $_SERVER['HTTP_REFERER']) {
                ?>
                        <div class="alert alert-success alert-dismissible fade show text-center h4" role="alert">
                            <strong>Berhasil update data!</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
            <!-- END MESSAGE -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-edit mr-1"></i>
                    Edit Data User
                </div>
                <div class="card-body">
                    <form class="mb-5" action="../user/edit_user_post.php" method="POST" id="appsform" enctype="multipart/form-data">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="Username">Username</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="adminUsername" name="adminUsername" placeholder="Masukkan Username" value="<?php echo $nama['admin_username']; ?>" autocomplete="off" minlength="2" maxlength="50" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="adminPassword">Password</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="adminPassword" name="adminPassword" placeholder="Masukkan Password" value="<?php echo $nama['admin_password']; ?>" autocomplete="off" minlength="2" maxlength="50" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label" for="adminNama">Level</label>
                            <div class="col-sm-8">
                                <select class="custom-select" id="inputGroupSelect01" name="Level" required>
                                    <option value="<?php echo $nama['admin_nama']; ?>" selected><?php echo $nama['admin_nama']; ?></option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="User">User</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-8 ml-auto">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
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
        $("form#appsform").submit(function() {
            // Karena ada file tidak bisa pakai serialize
            // var postdata = $(this).serialize();
            var postdata = new FormData(this);
            var postaction = $(this).attr('action');
            $.ajax({
                type: "POST",
                url: postaction,
                timeout: false,
                data: postdata,
                contentType: false,
                processData: false,
                dataType: 'JSON',
                success: function(response) {
                    if (response.status) {
                        $("#response").html(
                            '<div class="alert alert-success alert-dismissible fade show text-center h4" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                            response.message + '</div>');
                        $("#appsform")[0].reset();
                    } else {
                        $("#response").html(
                            '<div class="alert alert-danger alert-dismissible fade show text-center h4" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                            response.message + '</div>');
                    }
                },
                error: function(_, _, errorMessage) {
                    $("#response").html(
                        '<div class="alert alert-danger alert-dismissible fade show text-center h4" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        errorMessage + '</div>');
                },
                beforeSend: function() {
                    $("#response").html(
                        '<div class="alert alert-warning alert-dismissible fade show text-center h4" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Loading...</div>'
                    );
                }
            });
            return false;
        });
    });
</script>