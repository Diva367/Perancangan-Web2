<?php
include "koneksi.php";

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
    echo "Nota tidak ditemukan.";
    exit;
}

/* data pelanggan dari session */
$nama_pelanggan = $_SESSION['user']['username'];
$no_hp = preg_replace('/[^0-9]/', '', $_SESSION['user']['no_telp']);
?>

<!-- ALERT EMAIL TERKIRIM -->
<?php if (isset($_GET['sent'])) { ?>
<script>
    alert("Nota berhasil dikirim ke email kamu 📧");
</script>
<?php } ?>

<!DOCTYPE html>
<html>
<head>
<title>Nota Pembayaran | GOKILAU</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f2f7ff;
    margin: 0;
    padding: 30px;
}

.nota {
    max-width: 420px;
    margin: auto;
    background: #fff;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 12px 30px rgba(30,60,114,0.15);
}

.nota h2 {
    text-align: center;
    color: #1E3C72;
    margin-bottom: 6px;
}

.nota .sub {
    text-align: center;
    font-size: 13px;
    color: #777;
    margin-bottom: 20px;
}

.line {
    border-top: 1px dashed #ccc;
    margin: 16px 0;
}

.row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 14px;
}

.total {
    font-size: 16px;
    font-weight: bold;
    color: #1E3C72;
}

.status {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 999px;
    background: #e3f2fd;
    color: #1565c0;
    font-size: 12px;
    font-weight: 600;
}

.footer {
    text-align: center;
    margin-top: 20px;
    font-size: 12px;
    color: #777;
}

.nota-actions {
    display: flex;
    gap: 12px;
    margin-top: 22px;
}

/* Tombol utama (Cetak Nota) */
.btn {
    flex: 1;
    padding: 12px;
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    color: white;
    border: none;
    border-radius: 14px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
}

/* Tombol sekunder (Tidak Cetak / Kembali) */
.btn-secondary {
    flex: 1;
    padding: 12px;
    border-radius: 14px;
    border: 2px solid #3A7BD5;
    background: transparent;
    color: #3A7BD5;
    font-weight: 600;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
}

.btn-secondary:hover {
    background: #e9f1ff;
}

/* ===== MODE CETAK ===== */
@media print {
    body {
        background: white;
        margin: 0;
    }

    .nota-actions {
        display: none;
    }
}
</style>
</head>
<body>

<div class="nota">

    <h2>GOKILAU</h2>
    <div class="sub">Nota Pembayaran Servis Kendaraan</div>

    <div class="line"></div>

    <div class="row">
        <span>No. Pesanan</span>
        <strong>#<?= $data['id'] ?></strong>
    </div>

    <div class="row">
        <span>Nama Pelanggan</span>
        <strong><?= htmlspecialchars($nama_pelanggan) ?></strong>
    </div>

    <div class="row">
        <span>No. HP</span>
        <strong><?= $no_hp ?></strong>
    </div>

    <div class="row">
        <span>Layanan</span>
        <strong><?= htmlspecialchars($data['nama_layanan']) ?></strong>
    </div>

    <div class="row">
        <span>Tanggal</span>
        <strong><?= date('d M Y H:i', strtotime($data['tanggal'])) ?> WIB</strong>
    </div>

    <div class="row">
        <span>Status</span>
        <span class="status"><?= $data['status'] ?></span>
    </div>

    <?php if (!empty($data['catatan'])) { ?>
    <div class="row">
        <span>Catatan</span>
        <strong><?= htmlspecialchars($data['catatan']) ?></strong>
    </div>
    <?php } ?>

    <div class="line"></div>

    <div class="row total">
        <span>Total Bayar</span>
        <span>Rp <?= number_format($data['harga'],0,',','.') ?></span>
    </div>

   <div class="nota-actions">
    <a href="kirim_nota.php?id=<?= $data['id'] ?>" class="btn">
        🖨 Cetak & Kirim ke Email
    </a>

    <a href="beranda.php?menu=pesanan" class="btn-secondary">
        Tidak Cetak
    </a>
</div>

    <div class="footer">
        Terima kasih telah mempercayakan kendaraan Anda kepada GOKILAU 💙
    </div>

</div>
</body>
</html>