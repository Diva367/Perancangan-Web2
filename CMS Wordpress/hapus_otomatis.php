<?php
include "koneksi.php";
session_start();

$id = (int)$_GET['id'];
$id_pelanggan = $_SESSION['user']['id'];

mysqli_query($conn, "
    DELETE FROM pesanan 
    WHERE id='$id' 
    AND id_pelanggan='$id_pelanggan'
    AND status='Menunggu'
");

header("Location: beranda.php?menu=pesanan");
exit;