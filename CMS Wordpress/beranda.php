<?php
date_default_timezone_set('Asia/Jakarta');

include "koneksi.php";
include "auto_batal.php";


if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

$notif_unread = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM notifikasi
    WHERE id_pelanggan = '{$user['id']}'
      AND status = 'Belum Dibaca'
"))['total'];

// menu aktif
$menu = $_GET['menu'] ?? 'beranda';

if (isset($_POST['pesan_ulang'])) {

    $id = (int)$_GET['id'];
    $id_pelanggan = $_SESSION['user']['id'];

    $tanggal_baru = $_POST['tanggal_baru'];
    $tanggal_fix  = $tanggal_baru . ' ' . date('H:i:s');
    $batas_bayar  = date('Y-m-d H:i:s', strtotime('+1 minute'));

    mysqli_query($conn, "
    INSERT INTO notifikasi (id_pelanggan, pesan, status)
    VALUES (
        '$id_pelanggan',
        '🔄 Pesanan <b>$nama</b> berhasil diaktifkan kembali.',
        'Belum Dibaca'
    )
");

    if (mysqli_affected_rows($conn) > 0) {

    $qNama = mysqli_query($conn, "
        SELECT nama_layanan 
        FROM pesanan 
        WHERE id = '$id' AND id_pelanggan = '$id_pelanggan'
        LIMIT 1
    ");
    $nama = mysqli_fetch_assoc($qNama)['nama_layanan'];

    mysqli_query($conn, "
        INSERT INTO notifikasi (id_pelanggan, pesan)
        VALUES (
            '$id_pelanggan',
            '🔄 Pesanan <b>$nama</b> berhasil diaktifkan kembali.'
        )
    ");
    }

    header("Location: beranda.php?menu=detail_pesanan&id=$id");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Pelanggan | GOKILAU</title>

<style>
* {
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    margin: 0;
    background: #f2f7ff;
}

/* ===== NAVBAR ===== */
.navbar {
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    padding: 15px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: #fff;
}

.navbar .brand {
    font-size: 20px;
    font-weight: bold;
}

.navbar .menu a {
    color: #fff;
    text-decoration: none;
    margin-left: 15px;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 14px;
    background: rgba(255,255,255,0.15);
}

.navbar .menu a.active,
.navbar .menu a:hover {
    background: rgba(255,255,255,0.35);
}

/* ===== HEADER ===== */
.header {
    padding: 35px 25px;
    background: linear-gradient(135deg, #3A7BD5, #00D2FF);
    color: white;
}

.header h1 {
    margin: 0;
}

.header p {
    margin-top: 8px;
    font-size: 15px;
}

/* ===== CONTENT ===== */
.container {
    padding: 30px 25px;
}

.card-empty {
    background: #fff;
    padding: 50px;
    border-radius: 18px;
    text-align: center;
    box-shadow: 0 12px 30px rgba(30,60,114,0.12);
    color: #6b7c93;
}

.card-empty h3 {
    margin-bottom: 10px;
    color: #1E3C72;
}

/* ===== FOOTER ===== */
.footer {
    text-align: center;
    padding: 20px;
    font-size: 13px;
    color: #9aa9c2;
}
/* === GRID PAKET === */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 22px;
}

/* === CARD LAYANAN === */
.service-card {
    background: #ffffff;
    padding: 22px;
    border-radius: 18px;
    box-shadow: 0 12px 28px rgba(30,60,114,0.12);
    transition: 0.3s ease;
    display: flex;
    flex-direction: column;
}

.service-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(30,60,114,0.18);
}

.service-card h4 {
    margin: 0;
    color: #1E3C72;
    font-size: 17px;
}

/* === DESKRIPSI === */
.service-card p {
    font-size: 14px;
    color: #4a5d73;
    margin: 10px 0 14px;
    line-height: 1.6;
    flex: 1;
}

/* === HARGA === */
.price {
    font-size: 18px;
    font-weight: 700;
    color: #1E3C72;
    margin-bottom: 6px;
}

/* === ESTIMASI BADGE === */
.estimasi {
    display: inline-block;
    font-size: 12px;
    background: #e9f1ff;
    color: #1E3C72;
    padding: 4px 10px;
    border-radius: 20px;
    width: fit-content;
    margin-bottom: 14px;
}

/* === BUTTON === */
.btn-pilih {
    margin-top: auto;
    padding: 10px;
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    cursor: pointer;
    transition: 0.3s;
    text-align: center;
}

.btn-pilih:hover {
    opacity: 0.9;
    transform: scale(1.02);
}

/* ===== FOOTER ===== */
.footer {
    margin-top: 50px;
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    color: #eef3ff;
    padding: 28px 20px;
    font-size: 13px;
    text-align: center;

    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
}

.order-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 18px 20px;
    margin-bottom: 16px;
    box-shadow: 0 8px 22px rgba(30,60,114,0.08);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.order-title {
    font-weight: 600;
    color: #1E3C72;
    font-size: 15px;
}

.order-status {
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

/* STATUS */
.status-menunggu {
    background: #fff4cc;
    color: #b68900;
}

.status-diproses {
    background: #e3f2fd;
    color: #1565c0;
}

.status-selesai {
    background: #e6f4ea;
    color: #2e7d32;
}

.order-body {
    font-size: 14px;
    color: #445;
}

.order-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
}

.order-note {
    margin-top: 8px;
    font-size: 13px;
    color: #666;
}

.order-footer {
    margin-top: 14px;
    text-align: right;
}

.btn.bayar {
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    color: white;
    border: none;
    padding: 8px 14px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 13px;
}

.info {
    font-size: 13px;
    font-weight: 600;
}

.info.success {
    color: #2e7d32;
}

.order-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.order-card {
    cursor: pointer;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(30,60,114,0.15);
}

/* === DETAIL PESANAN CARD === */
.detail-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 28px 30px;
    box-shadow: 0 14px 35px rgba(30,60,114,0.12);
    max-width: 520px;
    margin: 0 auto;
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

.detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    color: #6b7c93;
}

.detail-value {
    font-weight: 600;
    color: #1E3C72;
    text-align: right;
}

/* STATUS */
.detail-status {
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.status-menunggu {
    background: #fff4cc;
    color: #b68900;
}

.status-diproses {
    background: #e3f2fd;
    color: #1565c0;
}

.status-selesai {
    background: #e6f4ea;
    color: #2e7d32;
}

/* CATATAN */
.detail-note {
    margin-top: 16px;
    padding: 14px;
    background: #f7faff;
    border-radius: 12px;
    font-size: 13px;
    color: #4a5d73;
}

/* ACTION */
.detail-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 24px;
}

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

.status-dibatalkan {
    background: #ffebee;
    color: #b71c1c;
    font-weight: 700;
}

/* === PESAN ULANG FORM === */
.reorder-box {
    margin-top: 22px;
    padding: 18px;
    background: #f5f9ff;
    border: 1px dashed #cfe0ff;
    border-radius: 14px;
}

.reorder-title {
    font-size: 14px;
    font-weight: 700;
    color: #1E3C72;
    margin-bottom: 12px;
}

.reorder-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 14px;
}

.reorder-row label {
    font-size: 13px;
    color: #4a5d73;
    font-weight: 600;
}

.reorder-row input[type="date"] {
    padding: 8px 12px;
    border-radius: 10px;
    border: 1px solid #c9d8f0;
    font-size: 13px;
    outline: none;
    transition: 0.2s;
}

.reorder-row input[type="date"]:focus {
    border-color: #3A7BD5;
    box-shadow: 0 0 0 2px rgba(58,123,213,0.15);
}

.btn-reorder {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    color: #fff;
    border: none;
    padding: 10px 18px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.25s;
}

.btn-reorder:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 18px rgba(30,60,114,0.25);
}

/* ===== BANTUAN BOX ===== */
.bantuan-box {
    background: linear-gradient(180deg, #ffffff, #f7faff);
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 16px 35px rgba(30,60,114,0.12);
    animation: fadeUp 0.4s ease;
}

.bantuan-box h4 {
    color: #1E3C72;
    margin-bottom: 14px;
    font-size: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.bantuan-box p {
    color: #6b7c93;
    font-size: 14px;
    line-height: 1.8;
    margin-bottom: 18px;
}

/* ===== LIST BANTUAN ===== */
.bantuan-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.bantuan-list li {
    background: #f1f6ff;
    padding: 14px 16px;
    border-radius: 14px;
    margin-bottom: 12px;
    font-size: 14px;
    color: #4a5d73;
    display: flex;
    gap: 10px;
    align-items: flex-start;
    transition: 0.25s;
}

.bantuan-list li:hover {
    background: #e6efff;
    transform: translateX(4px);
}

/* ICON */
.bantuan-icon {
    font-size: 18px;
    line-height: 1;
}

/* FOOT NOTE */
.bantuan-note {
    margin-top: 18px;
    padding: 14px;
    background: #e9f1ff;
    border-left: 5px solid #3A7BD5;
    border-radius: 12px;
    font-size: 13px;
    color: #445;
}

/* ANIMASI */
@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="brand">GOKILAU</div>
    <div class="menu">
        <a href="beranda.php?menu=beranda" class="<?= $menu=='beranda'?'active':'' ?>">Beranda</a>
        <a href="beranda.php?menu=layanan" class="<?= $menu=='layanan'?'active':'' ?>">Paket Layanan</a>
        <a href="beranda.php?menu=pesanan" class="<?= $menu=='pesanan'?'active':'' ?>">Pesanan Saya</a>
        <a href="beranda.php?menu=bantuan" class="<?= $menu=='bantuan'?'active':'' ?>">Bantuan</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- HEADER -->
<div class="header">
    <h1>Halo, <?= htmlspecialchars($user['username']) ?> 👋</h1>
    <p>Pesan layanan servis kendaraan tanpa ribet, langsung dari bengkel terpercaya.</p>
</div>

<!-- CONTENT -->
<div class="container">

<?php if ($menu == 'beranda') { ?>

<div class="card">
    <h4>👋 Selamat Datang di GOKILAU</h4>

    <p style="margin-top:10px">
        Halo <b><?= htmlspecialchars($user['username']) ?></b>,  
        selamat datang di sistem bengkel digital <b>GOKILAU</b>.
    </p>

    <hr style="margin:20px 0">

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px">

        <div class="service-card">
            <h4>🚗 Paket Layanan</h4>
            <p>Pilih layanan servis mobil sesuai kebutuhan kamu.</p>
            <a href="beranda.php?menu=layanan" class="btn-pilih">
                Lihat Paket
            </a>
        </div>

        <div class="service-card">
            <h4>📦 Pesanan Saya</h4>
            <p>Lihat status servis & riwayat pesanan kamu.</p>
            <a href="beranda.php?menu=pesanan" class="btn-pilih">
                Lihat Pesanan
            </a>
        </div>

        <div class="service-card">
            <h4>🔔 Notifikasi</h4>
            <p>Info penting terkait pesanan kamu.</p>
            <a href="notifikasi.php" class="btn-pilih">
                <?php if ($notif_unread > 0) { ?>
        <span style="
            background:red;
            color:white;
            font-size:11px;
            padding:2px 6px;
            border-radius:999px;
            margin-left:4px;
        ">
            <?= $notif_unread ?>
        </span>
    <?php } ?>
                Lihat Notifikasi
            </a>
        </div>

    </div>
</div>

<?php } elseif ($menu == 'layanan') { ?>

<div style="padding:30px">

    <h2 style="color:#1E3C72; margin-bottom:20px;">
        🚗 Paket Layanan Mobil
    </h2>

    <div style="
        display:grid;
        grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
        gap:20px;
    ">

        <?php
        $paket = mysqli_query($conn, "
            SELECT * FROM paket_layanan
            ORDER BY LOWER(nama_layanan) ASC
        ");

        if (mysqli_num_rows($paket) == 0) {
            echo "<p>Belum ada paket layanan.</p>";
        }

        while ($row = mysqli_fetch_assoc($paket)) {
        ?>
            <div class="service-card">
    <h4><?= htmlspecialchars($row['nama_layanan']) ?></h4>

    <p><?= htmlspecialchars($row['deskripsi']) ?></p>

    <div class="price">
        Rp <?= number_format($row['harga'], 0, ',', '.') ?>
    </div>

    <div class="estimasi">
        ⏱ <?= htmlspecialchars($row['estimasi']) ?>
    </div>

    <a href="pesan.php?id=<?= $row['id'] ?>" class="btn-pilih">
    Pilih Paket
</a>
</div>
        <?php } ?>

    </div>

</div>

<?php } elseif ($menu == 'pesanan') { ?>

<?php
$id_pelanggan = $_SESSION['user']['id'];

$data = mysqli_query($conn, "
    SELECT * FROM pesanan
    WHERE id_pelanggan = '$id_pelanggan'
    ORDER BY tanggal DESC
");
?>

<div class="card" style="text-align:left">
    <h4>📦 Pesanan Saya</h4>

    <?php if (mysqli_num_rows($data) == 0) { ?>
        <p style="text-align:center;color:#6b7c93;margin-top:20px">
            Belum ada pesanan.
        </p>
    <?php } ?>

    <?php while ($row = mysqli_fetch_assoc($data)) { ?>
        <a href="beranda.php?menu=detail_pesanan&id=<?= $row['id'] ?>" class="order-link">
            <div class="order-card">

                <div class="order-header">
                    <span class="order-title">
                        <?= htmlspecialchars($row['nama_layanan']) ?>
                    </span>

                    <span class="order-status status-<?= strtolower($row['status']) ?>">
                        <?= $row['status'] ?>
                    </span>
                </div>

                    <div class="order-body">

    <div class="order-row">
        <span>Tanggal</span>
        <strong>
            <?= date('d M Y H:i', strtotime($row['tanggal'])) ?>
        </strong>
    </div>

    <div class="order-row">
        <span>Harga</span>
        <strong>
            Rp <?= number_format($row['harga'], 0, ',', '.') ?>
        </strong>
    </div>

    <?php if ($row['catatan']) { ?>
        <div class="order-note">
            <span>Catatan:</span>
            <em><?= htmlspecialchars($row['catatan']) ?></em>
        </div>
    <?php } ?>

</div>

            </div>
        </a>
    <?php } ?>
</div>

<?php } elseif ($menu == 'detail_pesanan') { ?>

<?php
$id = (int)$_GET['id'];
$id_pelanggan = $_SESSION['user']['id'];

// ambil detail pesanan
$q = mysqli_query($conn, "
    SELECT p.*, pl.username, pl.no_telp
    FROM pesanan p
    JOIN pelanggan pl ON p.id_pelanggan = pl.id
    WHERE p.id='$id' AND p.id_pelanggan='$id_pelanggan'
    LIMIT 1
");

$data = mysqli_fetch_assoc($q);

if (!$data) {
    echo "<p>Pesanan tidak ditemukan.</p>";
    exit;
}

if (isset($_POST['kirim_testimoni'])) {

    $id_pesanan   = (int)$_POST['id_pesanan'];
    $id_pelanggan = $_SESSION['user']['id'];
    $rating       = (int)$_POST['rating'];
    $isi          = mysqli_real_escape_string($conn, $_POST['isi']);

    mysqli_query($conn, "
        INSERT INTO testimoni (id_pesanan, id_pelanggan, rating, isi)
        VALUES (
            '$id_pesanan',
            '$id_pelanggan',
            '$rating',
            '$isi'
        )
    ");

    mysqli_query($conn, "
        INSERT INTO notifikasi (id_pelanggan, pesan, status)
        VALUES (
            '$id_pelanggan',
            '⭐ Terima kasih atas testimoni kamu di GOKILAU!',
            'Belum Dibaca'
        )
    ");

    header("Location: beranda.php?menu=detail_pesanan&id=$id_pesanan#testimoni");
exit;
}


// ✅ BARU SETELAH INI BOLEH PAKAI $data
$cekTesti = mysqli_query($conn, "
    SELECT id FROM testimoni
    WHERE id_pesanan = '{$data['id']}'
      AND id_pelanggan = '$id_pelanggan'
    LIMIT 1
");

$sudahTesti = mysqli_num_rows($cekTesti) > 0;

$testimoniData = null;

if ($sudahTesti) {
    $qTesti = mysqli_query($conn, "
        SELECT rating, isi, created_at
        FROM testimoni
        WHERE id_pesanan = '{$data['id']}'
          AND id_pelanggan = '$id_pelanggan'
        LIMIT 1
    ");

    $testimoniData = mysqli_fetch_assoc($qTesti);
}
?>

<div class="detail-card">

    <div class="detail-title">
        🧾 Detail Pesanan
    </div>

    <div class="detail-row">
    <span class="detail-label">Tanggal</span>
    <span class="detail-value">
        <?php if ($data['tanggal'] && $data['tanggal'] != '0000-00-00 00:00:00') { ?>
            <?= date('d M Y H:i', strtotime($data['tanggal'])) ?>
        <?php } else { ?>
            -
        <?php } ?>
    </span>
</div>

<div class="detail-row">
    <span class="detail-label">No. HP</span>
    <span class="detail-value">
        <?= preg_replace('/[^0-9]/', '', $data['no_telp']) ?>
    </span>
</div>

    <div class="detail-row">
        <span class="detail-label">Layanan</span>
        <span class="detail-value"><?= htmlspecialchars($data['nama_layanan']) ?></span>
    </div>


    <div class="detail-row">
        <span class="detail-label">Harga</span>
        <span class="detail-value">
            Rp <?= number_format($data['harga'],0,',','.') ?>
        </span>
    </div>

    <div class="detail-row">
        <span class="detail-label">Status</span>
        <span class="detail-status status-<?= strtolower($data['status']) ?>">
            <?= $data['status'] ?>
        </span>
    </div>

    <?php if ($data['catatan']) { ?>
        <div class="detail-note">
            <b>Catatan:</b><br>
            <?= htmlspecialchars($data['catatan']) ?>
        </div>
    <?php } ?>

    <div class="detail-actions">

    <?php if ($data['status'] == 'Menunggu') { ?>
        <a href="bayar.php?id=<?= $data['id'] ?>" class="btn-bayar">
            💳 Bayar Sekarang
        </a>
    <?php } ?>

    <?php if ($data['status'] == 'Diproses' || $data['status'] == 'Selesai') { ?>
        <a href="nota.php?id=<?= $data['id'] ?>" class="btn-bayar">
            🖨 Cetak Nota
        </a>
    <?php } ?>
<?php if ($data['status'] == 'Selesai' && $sudahTesti && $testimoniData) { ?>

<div class="reorder-box" id="testimoni" style="background:#e6f4ea;border-color:#b7dfc1">

    <div class="reorder-title" style="color:#2e7d32">
        ⭐ Testimoni Kamu
    </div>

    <div style="font-size:14px;margin-bottom:8px">
        <b>Rating:</b>
        <?php
        for ($i = 1; $i <= 5; $i++) {
            echo $i <= $testimoniData['rating'] ? '⭐' : '☆';
        }
        ?>
    </div>

    <div style="
        background:#fff;
        padding:12px;
        border-radius:10px;
        font-size:14px;
        color:#445;
    ">
        <?= nl2br(htmlspecialchars($testimoniData['isi'])) ?>
    </div>

    <?php if (!empty($testimoniData['created_at'])) { ?>
        <div style="margin-top:8px;font-size:12px;color:#6b7c93">
            Dikirim pada <?= date('d M Y H:i', strtotime($testimoniData['created_at'])) ?>
        </div>
    <?php } ?>

</div>

<?php } ?>

    <?php if ($data['status'] == 'Selesai' && !$sudahTesti) { ?>

<form method="post" class="reorder-box" id="testimoni" style="margin-top:20px">
    <input type="hidden" name="id_pesanan" value="<?= $data['id'] ?>">

    <div class="reorder-title">
        ⭐ Beri Testimoni
    </div>

    <div style="margin-bottom:12px">
        <label style="font-size:13px;font-weight:600">Rating</label><br>
        <select name="rating"
                required
                style="padding:8px;border-radius:8px;border:1px solid #c9d8f0">
            <option value="5">⭐⭐⭐⭐⭐ Sangat Puas</option>
            <option value="4">⭐⭐⭐⭐ Puas</option>
            <option value="3">⭐⭐⭐ Cukup</option>
            <option value="2">⭐⭐ Kurang</option>
            <option value="1">⭐ Buruk</option>
        </select>
    </div>

    <div>
        <label style="font-size:13px;font-weight:600">Testimoni</label>
        <textarea name="isi"
                  required
                  placeholder="Tulis pengalaman kamu di GOKILAU..."
                  style="width:100%;padding:10px;border-radius:10px;border:1px solid #c9d8f0"></textarea>
    </div>

    <button type="submit" name="kirim_testimoni" class="btn-reorder" style="margin-top:12px">
        📝 Kirim Testimoni
    </button>

</form>

<?php } ?>

    <?php if ($data['status'] == 'Dibatalkan') { ?>
<form method="post" class="reorder-box">

    <div class="reorder-title">
        🔄 Pesan Ulang Layanan
    </div>

    <div class="reorder-row">
        <label>Tanggal Baru</label>
        <input type="date" name="tanggal_baru"
               required
               min="<?= date('Y-m-d') ?>">
    </div>

    <button type="submit" name="pesan_ulang" class="btn-reorder">
        🔄 Pesan Ulang
    </button>

    <!-- ⬇️ TARUH DI SINI -->
    <p style="font-size:12px;color:#6b7c93;margin-top:8px">
        Pilih tanggal baru untuk mengaktifkan kembali pesanan ini.
    </p>

</form>
<?php } ?>

    <a href="beranda.php?menu=pesanan" class="btn-back">
        ← Kembali
    </a>

</div>

</div>
<?php } ?>

<?php if ($menu == 'bantuan') { ?>

<div class="bantuan-box">
    <h4>❓ Bantuan Sistem</h4>

    <p>
        Menu bantuan ini membantu pengguna memahami fitur
        dan penggunaan sistem GOKILAU dengan mudah.
    </p>

    <ul class="bantuan-list">
        <li>
            <span class="bantuan-icon">🚗</span>
            <div><b>Paket Layanan</b><br>Lihat dan pilih layanan servis.</div>
        </li>

        <li>
            <span class="bantuan-icon">📦</span>
            <div><b>Pesanan</b><br>Kelola dan pantau status pesanan.</div>
        </li>

        <li>
            <span class="bantuan-icon">⭐</span>
            <div><b>Testimoni</b><br>Lihat ulasan dan rating pelanggan.</div>
        </li>

        <li>
            <span class="bantuan-icon">🚪</span>
            <div><b>Logout</b><br>Keluar dari sistem dengan aman.</div>
        </li>
    </ul>

    <div class="bantuan-note">
        ℹ️ Jika mengalami kendala, silakan hubungi admin atau pengelola bengkel.
    </div>
</div>

<?php } ?>

</div>

<!-- FOOTER -->
<div class="footer">
    © 2025 GOKILAU SYSTEM
</div>

<script>
if (window.location.hash) {
    const el = document.querySelector(window.location.hash);
    if (el) {
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}
</script>

</body>
</html>