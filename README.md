# Sistem Informasi Absensi Karyawan Gudang Berbasis Face Recognition

![License](https://img.shields.io/badge/license-MIT-blue.svg)

## Deskripsi Proyek

Proyek ini adalah sebuah Sistem Informasi Absensi Karyawan Gudang yang menggunakan teknologi Pengenalan Wajah (Face Recognition) untuk mencatat kehadiran karyawan secara otomatis dan akurat. Sistem ini dirancang untuk memudahkan manajemen kehadiran dan meningkatkan efisiensi operasional di lingkungan gudang.

## Fitur Utama

- **Pengenalan Wajah:** Menggunakan teknologi canggih untuk mengenali wajah karyawan.
- **Laporan Kehadiran:** Menghasilkan laporan kehadiran harian, mingguan, dan bulanan.
- **Manajemen Pengguna:** Tambah, hapus, dan kelola data karyawan dengan mudah.
- **Keamanan Data:** Data karyawan dan laporan kehadiran disimpan dengan aman.
- **Notifikasi:** Kirim notifikasi email untuk kehadiran terlambat atau tidak hadir.

## Demo

Anda dapat melihat demo aplikasi ini secara online di [sini](https://himmatululya.my.id/).

## Teknologi yang Digunakan

- **Bahasa Pemrograman:** HTML, CSS, JavaScript, PHP
- **Database:** MySQL
- **Pengenalan Wajah:** Dlib
- **Server:** XAMPP

## Instalasi

Berikut adalah langkah-langkah untuk menginstal dan menjalankan proyek ini di lingkungan lokal Anda menggunakan XAMPP:

1. **Clone repositori:**
    ```bash
    git clone https://github.com/himmatululya1200/sistem-informasi-absensi-karyawan-gudang-berbasis-facerecognition.git
    ```
2. **Pindahkan direktori proyek ke folder `htdocs` di XAMPP:**
    - Pindahkan folder proyek hasil clone ke direktori `xampp/htdocs/`.
    - Contoh: `C:/xampp/htdocs/sistem-informasi-absensi-karyawan-gudang-berbasis-facerecognition`.

3. **Konfigurasi database:**
    - Buka phpMyAdmin melalui browser Anda (`http://localhost/phpmyadmin`).
    - Buat database baru untuk proyek ini, misalnya `absensi_gudang`.
    - Impor file database SQL yang disertakan dalam proyek (`absensi_gudang.sql`) ke dalam database yang baru dibuat.

4. **Konfigurasi koneksi database:**
    - Buka file `config.php` atau file konfigurasi lain yang digunakan untuk koneksi database di proyek Anda.
    - Sesuaikan pengaturan koneksi database dengan yang ada di XAMPP:
      ```php
      <?php
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "absensi_gudang";

      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }
      ?>
      ```

5. **Jalankan XAMPP:**
    - Buka XAMPP Control Panel.
    - Jalankan `Apache` dan `MySQL`.

6. **Akses aplikasi:**
    Buka browser Anda dan navigasikan ke `http://localhost/sistem-informasi-absensi-karyawan-gudang-berbasis-facerecognition`.

## Cara Penggunaan

1. **Daftarkan Karyawan:**
   - Tambahkan data karyawan baru melalui antarmuka manajemen pengguna.
   - Ambil foto wajah karyawan untuk data pengenalan wajah.

2. **Absensi Karyawan:**
   - Karyawan dapat melakukan absensi dengan menghadapkan wajah mereka ke kamera yang terhubung ke sistem.
   - Sistem akan mengenali wajah dan mencatat kehadiran secara otomatis.

3. **Laporan Kehadiran:**
   - Lihat dan unduh laporan kehadiran dari antarmuka laporan.

## Kontribusi

Kami sangat terbuka terhadap kontribusi dari komunitas. Jika Anda ingin berkontribusi, silakan fork repositori ini dan buat pull request dengan perubahan Anda. Pastikan untuk mengikuti pedoman kontribusi kami.

1. Fork repositori ini.
2. Buat branch fitur baru (`git checkout -b fitur-baru`).
3. Commit perubahan Anda (`git commit -m 'Tambahkan fitur baru'`).
4. Push ke branch (`git push origin fitur-baru`).
5. Buat Pull Request.

## Lisensi

Proyek ini dilisensikan di bawah lisensi MIT. Lihat file [LICENSE](LICENSE) untuk informasi lebih lanjut.

## Kontak

Jika Anda memiliki pertanyaan atau masukan, silakan hubungi kami di:

- **Email:** himmatululya1200@example.com
- **GitHub:** [himmatululya1200](https://github.com/himmatululya1200)

## Terima Kasih

Terima kasih telah menggunakan Sistem Informasi Absensi Karyawan Gudang Berbasis Face Recognition! Kami harap sistem ini membantu meningkatkan efisiensi dan kemudahan dalam manajemen kehadiran di gudang Anda.
