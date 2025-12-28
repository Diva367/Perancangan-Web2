<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

require 'vendor/autoload.php';
include 'koneksi.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Mpdf\Mpdf;

// 🔐 CEK LOGIN
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['user'];
$email    = $_SESSION['email'];

// =========================
// FILTER DARI HALAMAN TAMPIL
// =========================
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
$sort    = (isset($_GET['sort']) && $_GET['sort'] === 'DESC') ? 'DESC' : 'ASC';

$where = "";
if ($keyword !== '') {
    $keyword = $conn->real_escape_string($keyword);
    $where = "WHERE original_name LIKE '%$keyword%'";
}

// =========================
// 1. GENERATE PDF
// =========================
$result = $conn->query("
    SELECT * FROM images
    $where
    ORDER BY original_name $sort
");

$mpdf = new Mpdf([
    'margin_left'   => 15,
    'margin_right'  => 15,
    'margin_top'    => 40,
    'margin_bottom' => 15,
    'default_font'  => 'dejavusans'
]);

// HEADER PDF
$header = "
<div style='text-align:center; border-bottom:2px solid #ff9acb; padding-bottom:8px;'>
    <h2 style='color:#d63384; margin:0;'>Laporan Data Bunga</h2>
    <small>
        Dicetak oleh: <b>{$username}</b><br>
        Email: {$email}<br>
        Tanggal: ".date('d M Y H:i')."
    </small>
</div>
";
$mpdf->SetHTMLHeader($header);

// CSS
$mpdf->WriteHTML("
<style>
table { width:100%; border-collapse:collapse; margin-top:15px; }
th, td { border:1px solid #ff9acb; padding:8px; text-align:center; }
th { background:#ffb3d9; }
img { border-radius:8px; }
</style>
");

// ISI PDF
$html = "
<table>
<tr>
    <th>No</th>
    <th>Nama Bunga</th>
    <th>Foto</th>
</tr>
";

$no = 1;
while ($row = $result->fetch_assoc()) {
    $html .= "
    <tr>
        <td>$no</td>
        <td>{$row['original_name']}</td>
        <td><img src='uploads/{$row['filename']}' width='70'></td>
    </tr>
    ";
    $no++;
}

$html .= "</table>";
$mpdf->WriteHTML($html);

// SIMPAN PDF
$pdfName = "Laporan_Bunga_{$username}_" . date('Ymd_His') . ".pdf";
$mpdf->Output($pdfName, "F");

// =========================
// 2. KIRIM EMAIL
// =========================
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'oktad9126@gmail.com';       // email pengirim
    $mail->Password   = 'eflocgysfoutrnys';           // app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('oktad9126@gmail.com', 'Sistem Bunga Aesthetic');
    $mail->addAddress($email, $username);
    $mail->addAttachment($pdfName);

    $mail->isHTML(true);
    $mail->Subject = '[LAPORAN] Data Bunga Aesthetic 🌸';
    $mail->Body = "
        <h3 style='color:#d63384;'>Halo $username 🌸</h3>
        <p>Berikut kami kirimkan laporan data bunga sesuai filter yang kamu pilih.</p>
        <p><b>Terima kasih sudah menggunakan sistem kami 💗</b></p>
        <small>— Sistem Bunga Aesthetic</small>
    ";

    $mail->send();
    echo "✅ Laporan berhasil dikirim ke email kamu 💌";

} catch (Exception $e) {
    echo "❌ Email gagal dikirim: {$mail->ErrorInfo}";
}