<?php
session_start();
if (isset($_SESSION['username'])) {
    if ($_SESSION['admin_nama'] == 'User') {
        header('location:../hal-user/users.php');
    } else if ($_SESSION['admin_nama'] == 'Administrator') {
        header('location:../index.php');
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Login - Absensi</title>
    <link href="../css/styles.css" rel="stylesheet">
    <link href="../css/r.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="login-container">
        <h3 class="text-center font-weight-light my-4">Login</h3>
        <!-- START MESSAGE -->
        <?php
            if (isset($_GET['msg']) && $_SERVER['HTTP_REFERER']) {
                $msg = intval($_GET['msg']);
                $message = '';
                switch ($msg) {
                    case 1:
                        $message = 'Silahkan isi Username & Password!';
                        break;
                    case 2:
                        $message = 'Username/Password salah!';
                        break;
                    case 3:
                        $message = 'Terjadi Kesalahan!';
                        break;
                }
                if ($message) {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: '$message'
                        });
                    </script>";
                }
            }
        ?>
        <!-- END MESSAGE -->
        <form action="./login_post.php" method="POST">
            <div class="form-group">
                <label class="small mb-1" for="inputUsername">Username</label>
                <input class="form-control py-4" id="inputUsername" name="username" type="text" placeholder="Masukkan username">
            </div>
            <div class="form-group">
                <label class="small mb-1" for="inputPassword">Password</label>
                <input class="form-control py-4" id="inputPassword" name="password" type="password" placeholder="Masukkan password">
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit">Login</button>
                <button class="btn btn-danger btn-block" type="reset">Reset</button>
            </div>
        </form>
        <button class="btn btn-info btn-block" id="esp32camButton">Go to ESP32-CAM</button>
        <p id="esp32camIP" style="text-align: center; margin-top: 10px;"></p>
    </div>

    
    <script src="../assets/js/jquery-3.5.1.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('esp32camButton').addEventListener('click', function() {
        fetch('esp32cam_ip.txt')
            .then(response => response.text())
            .then(ip => {
                document.getElementById('esp32camIP').innerText = 'ESP32-CAM IP: ' + ip;
                window.location.href = 'http://' + ip;
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch ESP32-CAM IP!'
                });
            });
    });
</script>
</body>
</html>
