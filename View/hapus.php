<?php
include('koneksi.php');
$id = $_GET['id'];

$query = mysqli_query($koneksi, "DELETE FROM users WHERE id='$id'");

if ($query) {
    echo "<script>alert('Data berhasil dihapus!');window.location='index.php';</script>";
} else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}
?>
