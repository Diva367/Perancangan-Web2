<?php
$host = "localhost";     // biasanya localhost
$user = "root";          // user XAMPP biasanya root
$pass = "";              // password biasanya kosong
$db   = "db_gambar";     // nama database kamu

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>