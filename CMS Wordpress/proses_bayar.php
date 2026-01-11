<?php
include "koneksi.php";
session_start();

$id = (int)$_POST['id'];
$id_pelanggan = $_SESSION['user']['id'];

mysqli_query($conn, "
    UPDATE pesanan 
    SET status='Diproses' 
    WHERE id='$id' 
    AND id_pelanggan='$id_pelanggan'
");

header("Location: beranda.php?menu=pesanan");
exit;