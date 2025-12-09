<?php
date_default_timezone_set('Asia/Jakarta');

require 'vendor/autoload.php';
include 'koneksi.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Mpdf\Mpdf;

// =========================
// 1. GENERATE PDF
// =========================

$result = $conn->query("SELECT * FROM images ORDER BY id DESC");

$mpdf = new Mpdf([
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 30,
    'margin_bottom' => 15,
    'default_font' => 'dejavusans'
]);

$header = "
<div style='text-align:center; padding:10px 0; border-bottom:1px solid pink;'>
    <h2 style='color:#d63384; font-size:20px; margin:0;'>Laporan Data Bunga</h2>
    <small>Dicetak pada: ".date('d M Y H:i')."</small>
</div>
";
$mpdf->SetHTMLHeader($header);

$css = "
<style>
table { width:100%; border-collapse:collapse; }
th, td { border:1px solid #ff9acb; padding:8px; text-align:center; }
</style>
";
$mpdf->WriteHTML($css);

$html = "
<table>
<tr>
    <th>No</th>
    <th>Nama Bunga</th>
    <th>Foto</th>
</tr>
";

$no = 1;
while($row = $result->fetch_assoc()){
    $html .= "
    <tr>
        <td>$no</td>
        <td>{$row['original_name']}</td>
        <td><img src='uploads/{$row['filename']}' width='80'></td>
    </tr>";
    $no++;
}

$html .= "</table>";
$mpdf->WriteHTML($html);

$pdfPath = "Laporan_Bunga.pdf";
$mpdf->Output($pdfPath, "F");


// =========================
// 2. KIRIM EMAIL
// =========================

$mail = new PHPMailer(true);

try {

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // Gunakan password aplikasi TANPA SPASI
    $mail->Username = 'oktad9126@gmail.com';
    $mail->Password = 'ajtxerjajbszeopt';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('oktad9126@gmail.com', 'Sistem Bunga Aesthetic');
    $mail->addAddress('techsavvy2a10@gmail.com');

    $mail->addAttachment($pdfPath);

    $mail->isHTML(true);
    $mail->Subject = 'Laporan Data Bunga (PDF)';
    $mail->Body = "
        <h3 style='color:#d63384;'>Halo 🌸</h3>
        <p>Berikut terlampir laporan data bunga dalam bentuk PDF.</p>
        <p>Terima kasih 💗</p>
    ";

    $mail->send();

    echo "Email berhasil dikirim! 💗";

} catch (Exception $e) {
    echo "Email gagal dikirim. Error: {$mail->ErrorInfo}";
}
?>