<?php
date_default_timezone_set('Asia/Jakarta');

include "koneksi.php";
include "auto_batal.php";


$id_layanan   = (int)$_GET['id'];
$id_pelanggan = $_SESSION['user']['id'];

// ambil data layanan
$q = mysqli_query($conn, "SELECT * FROM paket_layanan WHERE id='$id_layanan'");
$layanan = mysqli_fetch_assoc($q);

// JIKA FORM DIKIRIM (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $catatan = $_POST['catatan'] ?? '';
    $tanggal_layanan = $_POST['tanggal_layanan']; // ✅ BENAR
    $tanggal = $tanggal_layanan . ' ' . date('H:i:s'); // ✅ WIB
    $batas_bayar = date('Y-m-d H:i:s', strtotime('+1 minute'));

    mysqli_query($conn, "
        INSERT INTO pesanan
        (
            id_pelanggan,
            id_layanan,
            nama_layanan,
            harga,
            catatan,
            tanggal,
            batas_bayar,
            status
        )
        VALUES
        (
            '$id_pelanggan',
            '$id_layanan',
            '{$layanan['nama_layanan']}',
            '{$layanan['harga']}',
            '$catatan',
            '$tanggal',
            '$batas_bayar',
            'Menunggu'
        )
    ");

    header("Location: beranda.php?menu=pesanan");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Pesan Layanan | GOKILAU</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f7fb;
}

.card {
    max-width: 500px;
    margin: 50px auto;
    background: white;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.1);
}

h2 {
    color: #1E3C72;
}

.info {
    margin: 15px 0;
    font-size: 14px;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 8px; /* jarak lebih dekat */
}

.info-row b {
    min-width: 120px; /* diperkecil */
}

.info-row input[type="date"] {
    padding: 6px 10px;
    border-radius: 8px;
    border: 1px solid #c9d8f0;
    font-size: 13px;
}

textarea {
    width: 100%;
    padding: 10px;
    border-radius: 10px;
    border: 1px solid #c9d8f0;
}

.btn-group {
    display: flex;
    gap: 12px;
    margin-top: 18px;
}

.btn-primary {
    flex: 1;
    padding: 12px;
    background: #1E3C72;
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    cursor: pointer;
}

.btn-primary:hover {
    opacity: 0.9;
}

.btn-secondary {
    flex: 1;
    padding: 12px;
    background: #e6ebf5;
    color: #1E3C72;
    border-radius: 10px;
    font-size: 14px;
    text-align: center;
    text-decoration: none;
    line-height: 1.5;
}

.btn-secondary:hover {
    background: #dbe3f3;
}
</style>
</head>

<body>
<div class="card">
    <h2>Konfirmasi Pesanan</h2>

    <form method="post">

        <div class="info">
            <b>Nama Pelanggan:</b>
            <?= htmlspecialchars($_SESSION['user']['username']) ?>
        </div>

        <?php
        $no_telp = preg_replace('/[^0-9]/', '', $_SESSION['user']['no_telp']);
        ?>

        <div class="info">
            <b>No. HP:</b> <?= $no_telp ?>
        </div>

        <div class="info">
            <b>Layanan:</b> <?= htmlspecialchars($layanan['nama_layanan']) ?>
        </div>

        <div class="info">
            <b>Harga:</b> Rp <?= number_format($layanan['harga'],0,',','.') ?>
        </div>

        <div class="info">
            <b>Estimasi:</b> <?= htmlspecialchars($layanan['estimasi']) ?>
        </div>

        <div class="info info-row">
    <b>Tanggal Pengerjaan:</b>
    <input type="date" name="tanggal_layanan" required min="<?= date('Y-m-d') ?>">
</div>

        <textarea name="catatan" placeholder="Catatan tambahan (opsional)"></textarea>

        <div class="btn-group">
    <button type="submit" name="pesan" class="btn-primary">
        Pesan Sekarang
    </button>

    <a href="beranda.php?menu=layanan" class="btn-secondary">
        Batal
    </a>
</div>
    </form>
</div>
    </form>
</div>
</body>
</html>