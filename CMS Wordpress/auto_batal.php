<?php
// auto_batal.php
// Membatalkan pesanan otomatis jika lewat batas bayar

if (!isset($conn)) {
    include "koneksi.php";
}

// WAKTU SEKARANG (WIB)
$now = date('Y-m-d H:i:s');

// UPDATE PESANAN YANG KADALUARSA
mysqli_query($conn, "
    UPDATE pesanan
    SET status = 'Dibatalkan'
    WHERE status = 'Menunggu'
      AND batas_bayar IS NOT NULL
      AND batas_bayar < '$now'
");