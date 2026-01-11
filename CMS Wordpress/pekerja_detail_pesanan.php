<?php
include "koneksi.php";

if (!isset($_SESSION['pekerja_login'])) {
    header("Location: pekerja_login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

// ==================
// AMBIL DATA PESANAN
// ==================
$q = mysqli_query($conn, "
    SELECT p.*, pl.username, pl.id AS id_pelanggan
    FROM pesanan p
    JOIN pelanggan pl ON p.id_pelanggan = pl.id
    WHERE p.id='$id'
    LIMIT 1
");

$data = mysqli_fetch_assoc($q);

if (!$data) {
    echo "<p>Pesanan tidak ditemukan.</p>";
    exit;
}

// ==================
// PROSES SELESAI
// ==================
if (isset($_POST['selesai'])) {

    mysqli_query($conn, "
        UPDATE pesanan
        SET status='Selesai'
        WHERE id='$id'
    ");

    mysqli_query($conn, "
        INSERT INTO notifikasi (id_pelanggan, pesan, status)
        VALUES (
            '{$data['id_pelanggan']}',
            '✅ Pesanan <b>{$data['nama_layanan']}</b> telah selesai dikerjakan.',
            'Belum Dibaca'
        )
    ");

    header("Location: pekerja_dashboard.php?menu=pesanan");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Detail Pesanan | GOKILAU</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f2f7ff;
    margin: 0;
}

.container {
    max-width: 520px;
    margin: 60px auto;
}

.card {
    background: #fff;
    padding: 26px 28px;
    border-radius: 18px;
    box-shadow: 0 14px 35px rgba(30,60,114,0.12);
}

.card h3 {
    margin-top: 0;
    color: #1E3C72;
}

.row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px dashed #e2e8f5;
    font-size: 14px;
}

.row:last-child {
    border-bottom: none;
}

.label {
    color: #6b7c93;
}

.value {
    font-weight: 600;
    color: #1E3C72;
}

.status {
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

.actions {
    margin-top: 26px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn {
    padding: 10px 18px;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.btn-selesai {
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    color: #fff;
}

.btn-selesai:hover {
    opacity: 0.9;
}

.btn-back {
    text-decoration: none;
    color: #3A7BD5;
    font-weight: 600;
    font-size: 14px;
}
</style>
</head>

<body>

<div class="container">

    <div class="card">

        <h3>🧾 Detail Pesanan</h3>

        <div class="row">
            <span class="label">Layanan</span>
            <span class="value"><?= htmlspecialchars($data['nama_layanan']) ?></span>
        </div>

        <div class="row">
            <span class="label">Pelanggan</span>
            <span class="value"><?= htmlspecialchars($data['username']) ?></span>
        </div>

        <div class="row">
            <span class="label">Tanggal</span>
            <span class="value">
                <?= date('d M Y H:i', strtotime($data['tanggal'])) ?>
            </span>
        </div>

        <div class="row">
            <span class="label">Status</span>
            <span class="status status-<?= strtolower($data['status']) ?>">
                <?= $data['status'] ?>
            </span>
        </div>

        <div class="actions">

            <a href="pekerja_dashboard.php?menu=pesanan" class="btn-back">
                ← Kembali
            </a>

            <?php if ($data['status'] != 'Selesai') { ?>
            <form method="post">
                <button type="submit" name="selesai" class="btn btn-selesai">
                    ✅ Tandai Selesai
                </button>
            </form>
            <?php } ?>

        </div>

    </div>

</div>

</body>
</html>