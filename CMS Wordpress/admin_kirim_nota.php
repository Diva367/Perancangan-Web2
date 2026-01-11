<?php
require_once __DIR__ . '/vendor/autoload.php';
include "koneksi.php";

use PHPMailer\PHPMailer\PHPMailer;
use Mpdf\Mpdf;

$id = (int)$_POST['id'];

$q = mysqli_query($conn, "
    SELECT p.*, pl.username, pl.email, pl.no_telp
    FROM pesanan p
    JOIN pelanggan pl ON p.id_pelanggan = pl.id
    WHERE p.id='$id'
    LIMIT 1
");
$d = mysqli_fetch_assoc($q);

if (!$d) exit("Pesanan tidak ditemukan");

// === GENERATE PDF ===
$html = "
<h2 style='text-align:center'>GOKILAU</h2>
<p style='text-align:center'>Nota Pembayaran</p>
<hr>
<p><b>Nama:</b> {$d['username']}</p>
<p><b>No HP:</b> {$d['no_telp']}</p>
<p><b>Layanan:</b> {$d['nama_layanan']}</p>
<p><b>Tanggal:</b> ".date('d M Y',strtotime($d['tanggal']))."</p>
<p><b>Total:</b> Rp ".number_format($d['harga'],0,',','.')."</p>
";

$mpdf = new Mpdf();
$mpdf->WriteHTML($html);

if (!is_dir("tmp")) mkdir("tmp");
$file = "tmp/nota_{$id}.pdf";
$mpdf->Output($file, 'F');

// === EMAIL ===
$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'EMAIL_KAMU@gmail.com';
$mail->Password = 'APP_PASSWORD';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('EMAIL_KAMU@gmail.com', 'GOKILAU');
$mail->addAddress($d['email'], $d['username']);
$mail->addAttachment($file);

$mail->isHTML(true);
$mail->Subject = "Nota Pembayaran GOKILAU #{$id}";
$mail->Body = "
Halo {$d['username']},<br>
Ini adalah <b>nota pembayaran</b> kamu.<br>
Terima kasih telah menggunakan layanan GOKILAU.
";

$mail->send();
unlink($file);

header("Location: admin_dashboard.php?menu=detail_pesanan&id=$id");
exit;