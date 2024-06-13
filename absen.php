<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Absensi Karyawan</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
</head>


<style>
    video {
        max-width: 100%;
        height: auto;
    }
</style>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Absensi Karyawan</h3>
                                </div>
                                <div class="card-body">
                                    <div id="response"></div>
                                    <form id="attendanceForm">
                                    <video id="video" ></video>
                                        <div id="txt"></div>
                                        <div class="form-group">
                                            <h6 class="text-center text-muted"> Hadapkan Wajah ke Kamera</h6>
                                            <input class="form-control py-4" name="nik" id="nik" type="text"
                                                placeholder="NIK" maxlength="10" autocomplete="off" autofocus required>
                                        </div>
                                        <div class="text-center">
                                            <button class="btn btn-primary" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="small"><a href="./">Kembali ke Halaman Utama</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <canvas style="display:none;"></canvas>
    <script src="assets/js/jquery-3.5.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script>
        // Mengambil  dari ESP32-CAM
// Mengambil elemen video
var video = document.getElementById('video');

// URL dari ESP32-CAM
var esp32camUrl = 'http://192.168.1.9'; // Ganti dengan alamat IP ESP32-CAM Anda

// Set source video ke URL stream ESP32-CAM
video.src = esp32camUrl;

// Memulai pemutaran video
video.play();

        const canvas = document.createElement('canvas');

        // Mengambil foto saat submit form
        $(document).ready(function() {
            $("form#attendanceForm").submit(function(e) {
                e.preventDefault(); // Mencegah form untuk melakukan submit default
                takePhoto();
            });
        });

        function takePhoto() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            var dataUrl = canvas.toDataURL("image/png");
            var nik = $("#nik").val(); // Mendapatkan NIK dari input form
            $.ajax({
                type: "POST",
                url: "./absen.php", // Ganti dengan URL untuk menyimpan data absensi
                data: {
                    nik: nik,
                    imgBase64: dataUrl
                },
                success: function(response) {
                    $("#response").html(response);
                    $("#nik").val(''); // Mengosongkan input NIK setelah submit
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $("#response").html('<div class="alert alert-danger">Error: ' + error + '</div>');
                }
            });
        }

        // Jam Waktu
        $(document).ready(function() {
            startTime();
            setInterval(startTime, 500);
        });

        function startTime() {
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('txt').innerHTML = '<h1 class="text-center">' + h + ':' + m + ':' + s + '</h1>';
        }

        function checkTime(i) {
            if (i < 10) {
                i = "0" + i;
            } // Jika jam dibawah 10 maka ditambahkan angka 0 pada contoh : 9 menjadi 09
            return i;
        }
    </script>
</body>

</html>
