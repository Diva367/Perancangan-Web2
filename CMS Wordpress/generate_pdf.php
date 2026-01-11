<?php
require_once __DIR__ . '/vendor/autoload.php';
include "koneksi.php";

use Mpdf\Mpdf;

if (!isset($_SESSION['login'])) {
    exit("Akses ditolak");
}

$id = (int)$_GET['id'];
$id_pelanggan = $_SESSION['user']['id'];

$q = mysqli_query($conn, "
    SELECT * FROM pesanan 
    WHERE id='$id' AND id_pelanggan='$id_pelanggan'
    LIMIT 1
");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    exit("Nota tidak ditemukan");
}

$nama  = $_SESSION['user']['username'];
$no_hp = preg_replace('/[^0-9]/', '', $_SESSION['user']['no_telp']);
$catatan = $data['catatan'] ?: '-';

$html = "
<h2 style='text-align:center'>GOKILAU</h2>
<p style='text-align:center'>Nota Pembayaran Servis Kendaraan</p>
<hr>

<p><b>No Pesanan:</b> #{$data['id']}</p>
<p><b>Nama:</b> {$nama}</p>
<p><b>No HP:</b> {$no_hp}</p>
<p><b>Layanan:</b> {$data['nama_layanan']}</p>
<p><b>Tanggal:</b> ".date('d M Y H:i', strtotime($data['tanggal']))." WIB</p>
<p><b>Catatan:</b> {$catatan}</p>

<hr>

<h3>Total Bayar: Rp ".number_format($data['harga'],0,',','.')."</h3>

<p style='margin-top:30px;font-size:12px'>
Terima kasih telah mempercayakan kendaraan Anda kepada GOKILAU 💙
</p>
";

$mpdf = new Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output("Nota_GOKILAU_{$data['id']}.pdf", "I"); // tampil di browser