<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
include "koneksi.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['login'])) {
    exit("Akses ditolak");
}

$id = (int)$_GET['id'];
$id_pelanggan = $_SESSION['user']['id'];

/* ======================
   AMBIL DATA PESANAN + PELANGGAN
====================== */
$q = mysqli_query($conn, "
    SELECT p.*, pl.username, pl.no_telp
    FROM pesanan p
    JOIN pelanggan pl ON p.id_pelanggan = pl.id
    WHERE p.id='$id' AND p.id_pelanggan='$id_pelanggan'
    LIMIT 1
");

$data = mysqli_fetch_assoc($q);

if (!$data) {
    exit("Nota tidak ditemukan");
}

/* ======================
   DATA USER SESSION
====================== */
$nama  = $data['username'];
$email = $_SESSION['user']['email'];

/* ======================
   KIRIM EMAIL NOTA
====================== */
$mail = new PHPMailer(true);

try {
    // SMTP Gmail
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'xxxxxxxxx@gmail.com';     // GANTI
    $mail->Password   = 'xxxxxxxxxxxxxxxxxxx';       // GANTI
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Pengirim & penerima
    $mail->setFrom('xxxxxxxxx@gmail.com', 'GOKILAU SYSTEM');
    $mail->addAddress($email, $nama);

    $mail->isHTML(true);
    $mail->Subject = "Nota Pembayaran GOKILAU #{$data['id']}";

    /* ======================
       BODY EMAIL = NOTA
    ====================== */
    $mail->Body = "
<div style='font-family:Arial,sans-serif;background:#f4f7fb;padding:20px'>
  <div style='max-width:540px;margin:auto;background:#fff;
              border-radius:16px;padding:28px;
              box-shadow:0 12px 30px rgba(30,60,114,.15)'>

    <h2 style='text-align:center;color:#1E3C72;margin-bottom:4px'>
      GOKILAU
    </h2>

    <p style='text-align:center;color:#666;margin-top:0'>
      Nota Pembayaran Servis Kendaraan
    </p>

    <hr style='border:none;border-top:1px dashed #ccc;margin:20px 0'>

    <table width='100%' cellpadding='6' style='font-size:14px'>
      <tr>
        <td>No. Pesanan</td>
        <td align='right'><b>#{$data['id']}</b></td>
      </tr>
      <tr>
        <td>Nama Pelanggan</td>
        <td align='right'>{$data['username']}</td>
      </tr>
      <tr>
        <td>No. HP</td>
        <td align='right'>".preg_replace('/[^0-9]/','',$data['no_telp'])."</td>
      </tr>
      <tr>
        <td>Tanggal</td>
        <td align='right'>
          ".date('d M Y H:i', strtotime($data['tanggal']))."
        </td>
      </tr>
    </table>

    <hr style='border:none;border-top:1px dashed #ccc;margin:18px 0'>

    <table width='100%' cellpadding='6' style='font-size:14px'>
      <tr>
        <td>Layanan</td>
        <td align='right'><b>{$data['nama_layanan']}</b></td>
      </tr>
      <tr>
        <td>Status</td>
        <td align='right'><b>{$data['status']}</b></td>
      </tr>
      <tr>
        <td>Total Bayar</td>
        <td align='right'>
          <b style='color:#1E3C72;font-size:16px'>
            Rp ".number_format($data['harga'],0,',','.')."
          </b>
        </td>
      </tr>
    </table>
";

    // CATATAN (JIKA ADA)
    if (!empty($data['catatan'])) {
        $mail->Body .= "
        <div style='margin-top:20px;
                    background:#f9fafc;
                    padding:14px;
                    border-radius:12px;
                    font-size:13px;
                    color:#445'>
            <b>Catatan Pelanggan:</b><br>
            ".nl2br(htmlspecialchars($data['catatan']))."
        </div>
        ";
    }

    // PENUTUP
    $mail->Body .= "
    <div style='margin-top:25px;
                background:#f1f6ff;
                padding:14px;
                border-radius:12px;
                font-size:13px;
                color:#445'>
      Email ini merupakan <b>bukti pembayaran resmi</b> dari sistem <b>GOKILAU</b>.
      Simpan email ini sebagai arsip kamu.
    </div>

    <p style='margin-top:25px;font-size:12px;color:#777;text-align:center'>
      Terima kasih telah mempercayai layanan kami.<br>
      <b>GOKILAU SYSTEM</b>
    </p>

  </div>
</div>
";

    $mail->send();

    header("Location: nota.php?id={$data['id']}&sent=1");
    exit;

} catch (Exception $e) {
    echo "Email gagal dikirim. Error: {$mail->ErrorInfo}";
}