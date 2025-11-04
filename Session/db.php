<?php
$koneksi = mysqli_connect("localhost", "root", "", "coba");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
