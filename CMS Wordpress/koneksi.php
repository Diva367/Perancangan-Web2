<?php
session_start();

date_default_timezone_set('Asia/Jakarta');

$conn = mysqli_connect("localhost", "u627170956_gclclii", "Ci?2sYMnx2", "u627170956_gclclii");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>