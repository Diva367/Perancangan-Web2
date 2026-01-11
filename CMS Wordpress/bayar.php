<?php
include "koneksi.php";
include "auto_batal.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
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
    die("Pesanan tidak ditemukan");
}

$nama_pelanggan = $_SESSION['user']['username'];
$no_telp = preg_replace('/[^0-9]/', '', $_SESSION['user']['no_telp']);

if (isset($_POST['bayar'])) {

    mysqli_query($conn, "
        UPDATE pesanan 
        SET status='Diproses'
        WHERE id='$id'
    ");

    header("Location: nota.php?id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Pembayaran | GOKILAU</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f2f7ff;
}

/* === DETAIL CARD (SAMA DENGAN DETAIL PESANAN) === */
.detail-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 28px 30px;
    box-shadow: 0 14px 35px rgba(30,60,114,0.12);
    max-width: 520px;
    margin: 80px auto;
}

.detail-title {
    font-size: 18px;
    font-weight: 700;
    color: #1E3C72;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px dashed #e2e8f5;
    font-size: 14px;
}

.detail-label {
    color: #6b7c93;
}

.detail-value {
    font-weight: 600;
    color: #1E3C72;
}

.detail-note {
    margin-top: 16px;
    padding: 14px;
    background: #f7faff;
    border-radius: 12px;
    font-size: 13px;
    color: #4a5d73;
}

.detail-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 24px;
}

/* BUTTON */
.btn-bayar {
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    color: #fff;
    border: none;
    padding: 10px 18px;
    border-radius: 12px;
    font-size: 14px;
    cursor: pointer;
}

.btn-bayar:hover {
    opacity: 0.9;
}

.btn-back {
    font-size: 14px;
    color: #3A7BD5;
    text-decoration: none;
    font-weight: 600;
}

/* COUNTDOWN */
.countdown {
    font-weight: 700;
    color: #d97706;
}
</style>

</head>

<body>

<div class="detail-card">

    <div class="detail-title">
        💳 Pembayaran
    </div>

    <div class="detail-row">
        <span class="detail-label">Nama Pelanggan</span>
        <span class="detail-value"><?= htmlspecialchars($nama_pelanggan) ?></span>
    </div>

    <div class="detail-row">
        <span class="detail-label">No. HP</span>
        <span class="detail-value"><?= $no_telp ?></span>
    </div>

    <div class="detail-row">
        <span class="detail-label">Layanan</span>
        <span class="detail-value"><?= htmlspecialchars($data['nama_layanan']) ?></span>
    </div>

    <div class="detail-row">
        <span class="detail-label">Tanggal</span>
        <span class="detail-value">
            <?= date('d M Y H:i', strtotime($data['tanggal'])) ?>
        </span>
    </div>

    <div class="detail-row">
        <span class="detail-label">Total Bayar</span>
        <span class="detail-value">
            Rp <?= number_format($data['harga'],0,',','.') ?>
        </span>
    </div>

    <?php if (!empty($data['catatan'])) { ?>
        <div class="detail-note">
            <b>Catatan:</b><br>
            <?= htmlspecialchars($data['catatan']) ?>
        </div>
    <?php } ?>

    <div class="detail-row">
        <span class="detail-label">Batas Pembayaran (1 Menit)</span>
        <span class="detail-value">
            <span class="countdown" id="timer">⏳ 01:00</span>
        </span>
    </div>

    <div class="detail-actions">
        <form method="post">
            <button type="submit" name="bayar" class="btn-bayar">
                ✅ Saya Sudah Bayar
            </button>
        </form>

        <a href="beranda.php?menu=detail_pesanan&id=<?= $data['id'] ?>" class="btn-back">
            ← Kembali
        </a>
    </div>

    <p style="font-size:12px;color:#6b7c93;margin-top:12px;text-align:center">
        * Pembayaran dummy untuk simulasi sistem
    </p>

</div>

<script>
let waktu = 60; // 1 menit

const timer = document.getElementById("timer");

const interval = setInterval(() => {
    let menit = Math.floor(waktu / 60);
    let detik = waktu % 60;

    timer.innerHTML = `⏳ ${menit.toString().padStart(2,'0')}:${detik.toString().padStart(2,'0')}`;

    if (waktu <= 0) {
        clearInterval(interval);
        window.location.href = "hapus_otomatis.php?id=<?= $data['id'] ?>";
    }

    waktu--;
}, 1000);
</script>

</body>
</html>