<?php
session_start();
require_once "koneksi.php";
require_once "dompdf/autoload.inc.php";
require_once "vendor/autoload.php";

$admin_username =
    $_SESSION['admin']['username']
    ?? $_SESSION['admin']['nama']
    ?? 'Admin';

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;

if (!isset($_SESSION['admin_login'])) {
    die("Akses ditolak");
}

$tgl_mulai = $_GET['tgl_mulai'] ?? '';
$tgl_akhir = $_GET['tgl_akhir'] ?? '';
$status    = $_GET['status'] ?? '';

$where = "WHERE 1=1";

if ($tgl_mulai && $tgl_akhir) {
    $where .= " AND DATE(pesanan.tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'";
}

if ($status) {
    $where .= " AND pesanan.status = '$status'";
}

$data = mysqli_query($conn, "
    SELECT 
        pesanan.nama_layanan,
        pesanan.harga,
        pesanan.status,
        pesanan.tanggal,
        pelanggan.username
    FROM pesanan
    JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id
    $where
    ORDER BY pesanan.tanggal DESC
");

$total = 0;

/* =======================
   HTML PDF
======================= */
$html = '
<style>
body {
    font-family: DejaVu Sans, Arial, sans-serif;
    font-size: 12px;
    color: #333;
}

.header {
    text-align: center;
    margin-bottom: 10px;
}

.header h2 {
    margin: 0;
    color: #1E3C72;
}

.line {
    border-top: 2px solid #1E3C72;
    margin: 8px 0 15px;
}

.info {
    margin-bottom: 15px;
}

.info table {
    width: 100%;
    font-size: 11px;
}

.info td {
    padding: 4px 0;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.table th {
    background: #1E3C72;
    color: #fff;
    padding: 8px;
    font-size: 11px;
}

.table td {
    border: 1px solid #ccc;
    padding: 7px;
    font-size: 11px;
}

.table tr:nth-child(even) {
    background: #f4f7fb;
}

.total-box {
    margin-top: 15px;
    padding: 10px;
    background: #f1f6ff;
    border-left: 4px solid #1E3C72;
    font-weight: bold;
}

.footer {
    margin-top: 25px;
    font-size: 10px;
    color: #666;
    text-align: right;
}
</style>

<div class="header">
    <h2>LAPORAN TRANSAKSI GOKILAU</h2>
    <div class="line"></div>
</div>

<div class="info">
    <table>
        <tr>
            <td width="120"><b>Periode</b></td>
            <td>: '.($tgl_mulai ?: '-').' s/d '.($tgl_akhir ?: '-').'</td>
        </tr>
        <tr>
            <td><b>Status</b></td>
            <td>: '.($status ?: 'Semua').'</td>
        </tr>
        <tr>
            <td><b>Dicetak oleh</b></td>
            <td>: '.$admin_username.'</td>
        </tr>
    </table>
</div>

<table class="table">
    <tr>
        <th width="30">No</th>
        <th>Pelanggan</th>
        <th>Layanan</th>
        <th width="120">Tanggal</th>
        <th width="90">Status</th>
        <th width="110">Harga</th>
    </tr>
';

$no = 1;
while ($r = mysqli_fetch_assoc($data)) {

    if ($r['status'] == 'Selesai') {
        $total += $r['harga'];
    }

    $html .= '
    <tr>
        <td>'.$no++.'</td>
        <td>'.$r['username'].'</td>
        <td>'.$r['nama_layanan'].'</td>
        <td>'.date('d M Y H:i', strtotime($r['tanggal'])).'</td>
        <td>'.$r['status'].'</td>
        <td>Rp '.number_format($r['harga'],0,',','.').'</td>
    </tr>';
}

$html .= '
</table>

<div class="total-box">
    Total Pendapatan (Transaksi Selesai):<br>
    Rp '.number_format($total,0,',','.').'
</div>

<div class="footer">
    Dicetak pada '.date('d M Y H:i').' WIB
</div>
';

/* =======================
   BUAT PDF
======================= */
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

/* SIMPAN SEMENTARA */
$file_pdf = "laporan_transaksi_".date('Ymd_His').".pdf";
$path_pdf = __DIR__ . "/$file_pdf";
file_put_contents($path_pdf, $dompdf->output());

/* =======================
   KIRIM EMAIL ADMIN
======================= */
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'xxxxxxxxxx@gmail.com';
    $mail->Password   = 'xxxxxxxxxxxxxxxxxxxx';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('xxxxxxxxx@gmail.com', 'GOKILAU SYSTEM');
    $mail->addAddress('xxxxxxxxx@gmail.com');

    $mail->Subject = "Laporan Transaksi GOKILAU";
    $mail->Body    = "
        Halo Admin 👋<br><br>
        Berikut terlampir laporan transaksi.<br>
        Periode: <b>".($tgl_mulai ?: '-')."</b> s/d <b>".($tgl_akhir ?: '-')."</b><br>
        Status: <b>".($status ?: 'Semua')."</b><br><br>
        <b>Total Pendapatan:</b> Rp ".number_format($total,0,',','.')."<br><br>
        GOKILAU System
    ";

    $mail->isHTML(true);
    $mail->addAttachment($path_pdf);

    $mail->send();

} catch (Exception $e) {
    die("Email gagal dikirim");
}

/* =======================
   HAPUS FILE
======================= */
unlink($path_pdf);

/* =======================
   FEEDBACK KE ADMIN
======================= */
echo "
<script>
alert('PDF berhasil dikirim ke email admin');
window.close();
</script>";
