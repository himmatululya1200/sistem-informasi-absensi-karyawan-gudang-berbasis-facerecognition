<?php
// Lokasi penyimpanan gambar
$target_dir = "uploads/";

// Membuat nama file baru dengan format timestamp
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Cek apakah file yang diunggah adalah gambar
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Cek apakah file sudah ada di server
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Batasi jenis file yang diizinkan
if($imageFileType != "jpg" && $imageFileType != "jpeg") {
    echo "Sorry, only JPG, JPEG files are allowed.";
    $uploadOk = 0;
}

// Cek apakah uploadOk bernilai 0 (terjadi kesalahan) atau tidak
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    // Jika semua pengecekan berhasil, coba unggah file
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
