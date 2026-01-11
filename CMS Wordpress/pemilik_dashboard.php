<?php
include "koneksi.php";

if (!isset($_SESSION['pemilik_login'])) {
    header("Location: pemilik_login.php");
    exit;
}

$pemilik = $_SESSION['pemilik'];
$menu = $_GET['menu'] ?? 'dashboard';
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Pemilik | GOKILAU</title>

<style>
* {
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    margin: 0;
    background: #f4f7fb;
}

/* ===== SIDEBAR ===== */
.sidebar {
    width: 260px;
    height: 100vh;
    background: linear-gradient(180deg, #1E3C72, #2a5298);
    position: fixed;
    left: 0;
    top: 0;
    color: #fff;
    padding: 22px 16px;

    overflow-y: auto;
}

/* Scrollbar sidebar */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 10px;
}

.sidebar {
    width: 260px;
    height: 100vh;
    background: linear-gradient(180deg, #1E3C72, #2a5298);
    position: fixed;
    left: 0;
    top: 0;
    color: #fff;
    padding: 22px 16px;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
    letter-spacing: 1px;
}

.sidebar a {
    display: block;
    padding: 12px 14px;
    margin-bottom: 8px;
    color: #fff;
    text-decoration: none;
    border-radius: 10px;
    font-size: 14px;
}

.sidebar a.active,
.sidebar a:hover {
    background: rgba(255,255,255,0.25);
}

.submenu {
    margin-left: 10px;
    margin-bottom: 10px;
}

.submenu a {
    font-size: 13px;
    padding: 8px 12px;
    opacity: 0.9;
    border-radius: 8px;
}

.submenu a:hover {
    background: rgba(255,255,255,0.18);
}

/* ===== CONTENT ===== */
.content {
    margin-left: 260px;
    padding: 30px;
    min-height: 100vh;
}

html {
    scroll-behavior: smooth;
}

/* ===== HEADER ===== */
.header {
    background: #fff;
    padding: 20px 24px;
    border-radius: 16px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}

.header h3 {
    margin: 0;
    color: #1E3C72;
}

.header p {
    margin-top: 6px;
    font-size: 14px;
    color: #6b7c93;
}

/* ===== CARD ===== */
.card {
    background: #fff;
    padding: 26px;
    border-radius: 18px;
    box-shadow: 0 14px 35px rgba(0,0,0,0.08);
}

/* ===== TABLE ===== */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 18px;
}

.table th {
    background: #eef4ff;
    color: #1E3C72;
    padding: 12px;
    font-size: 14px;
    border: 1px solid #dbe6ff;
}

.table td {
    padding: 12px;
    font-size: 14px;
    border: 1px solid #e2e8f0;
    vertical-align: top;
}

.table tr:hover {
    background: #f9fbff;
}

/* ===== BADGE RATING ===== */
.rating {
    font-weight: 600;
    color: #f39c12;
}

/* ===== FOOTER ===== */
.footer {
    margin-left: 260px;
    margin-top: 40px;
    background: #1E3C72;
    color: #e8f0ff;
    padding: 16px;
    text-align: center;
    font-size: 13px;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>PEMILIK</h2>

    <a href="?menu=dashboard" class="<?= $menu=='dashboard'?'active':'' ?>">📊 Dashboard</a>
    <a href="?menu=laporan_pendapatan" class="<?= $menu=='laporan_pendapatan'?'active':'' ?>">💰 Pendapatan</a>
    <a href="?menu=testimoni" class="<?= $menu=='testimoni'?'active':'' ?>">⭐ Testimoni</a>
    <a href="javascript:void(0)" onclick="toggleLaporan()" 
           class="<?= str_starts_with($menu,'laporan')?'active':'' ?>">
            📑 Laporan
        </a>

        <div id="laporan-menu" class="submenu"
             style="display: <?= str_starts_with($menu,'laporan')?'block':'none' ?>;">
            <a href="?menu=laporan_transaksi">• Laporan Transaksi</a>
            <a href="?menu=laporan_pembayaran">• Laporan Pembayaran</a>
            <a href="?menu=laporan_pendapatan">• Laporan Pendapatan</a>
            <a href="?menu=laporan_kinerja">• Laporan Kinerja Petugas</a>
            <a href="?menu=laporan_jadwal">• Laporan Jadwal</a>
            <a href="?menu=laporan_aktivitas">• Laporan Aktivitas Pelanggan</a>
            <a href="?menu=laporan_laris">• Laporan Layanan Terlaris</a>
        </div>
    <a href="?menu=bantuan" class="<?= $menu=='bantuan'?'active':'' ?>">❓ Bantuan</a>

    <a href="pemilik_logout.php">🚪 Logout</a>
</div>

<!-- CONTENT -->
<div class="content">

    <div class="header">
        <h3>Halo, <?= htmlspecialchars($pemilik['username']) ?> 👋</h3>
        <p>Dashboard pemilik GOKILAU</p>
    </div>

    <?php if ($menu == 'dashboard') { ?>

        <div class="card">
    <h4>📊 Dashboard Pemilik</h4>
    <p style="color:#6b7c93; line-height:1.7">
        Halaman ini menampilkan ringkasan umum sistem bengkel <b>GOKILAU</b>.
        Pemilik dapat memantau performa usaha, pendapatan, aktivitas transaksi,
        serta kepuasan pelanggan secara menyeluruh.
        <br><br>
        Gunakan menu di samping untuk melihat laporan detail dan analisis sistem.
    </p>
</div>

    <?php } elseif ($menu == 'testimoni') { ?>

        <div class="card">
            <h4>⭐ Testimoni Pelanggan</h4>

            <table class="table">
                <tr>
                    <th>No</th>
                    <th>Pelanggan</th>
                    <th>Layanan</th>
                    <th>Rating</th>
                    <th>Testimoni</th>
                    <th>Tanggal</th>
                </tr>

                <?php
                $q = mysqli_query($conn, "
                    SELECT t.*, p.nama_layanan, pl.username
                    FROM testimoni t
                    JOIN pesanan p ON t.id_pesanan = p.id
                    JOIN pelanggan pl ON t.id_pelanggan = pl.id
                    ORDER BY t.id DESC
                ");

                $no=1;
                while ($row = mysqli_fetch_assoc($q)) {
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                    <td class="rating">⭐ <?= $row['rating'] ?>/5</td>
                    <td><?= htmlspecialchars($row['isi']) ?></td>
                    <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <?php } elseif ($menu == 'laporan_transaksi') { ?>

<div class="card">
    <h4>📑 Laporan Transaksi</h4>
    <p style="color:#6b7c93; line-height:1.7">
        Halaman ini berisi ringkasan seluruh transaksi pelanggan
        yang tercatat dalam sistem GOKILAU.
        <br><br>
        Laporan ini membantu pemilik memantau aktivitas servis
        dan evaluasi operasional bengkel.
    </p>
</div>

<?php } elseif ($menu == 'laporan_pembayaran') { ?>

<div class="card">
    <h4>💳 Laporan Pembayaran</h4>
    <p style="color:#6b7c93; line-height:1.7">
        Menampilkan informasi pembayaran yang telah dilakukan pelanggan.
        <br><br>
        Digunakan untuk memastikan setiap transaksi tercatat dengan baik.
    </p>
</div>

<?php } elseif ($menu == 'laporan_pendapatan') { ?>

<div class="card">
    <h4>💰 Laporan Pendapatan</h4>
    <p style="color:#6b7c93; line-height:1.7">
        Ringkasan total pendapatan bengkel berdasarkan pesanan yang selesai.
        <br><br>
        Laporan ini membantu pemilik memantau perkembangan usaha.
    </p>
</div>

<?php } elseif ($menu == 'laporan_kinerja') { ?>

<div class="card">
    <h4>👷 Laporan Kinerja Petugas</h4>
    <p style="color:#6b7c93; line-height:1.7">
        Digunakan untuk memantau performa petugas dalam menangani pesanan.
    </p>
</div>

<?php } elseif ($menu == 'laporan_jadwal') { ?>

<div class="card">
    <h4>📆 Laporan Jadwal</h4>
    <p style="color:#6b7c93; line-height:1.7">
        Gambaran jadwal layanan servis kendaraan yang telah direncanakan.
    </p>
</div>

<?php } elseif ($menu == 'laporan_aktivitas') { ?>

<div class="card">
    <h4>👥 Laporan Aktivitas Pelanggan</h4>
    <p style="color:#6b7c93; line-height:1.7">
        Menampilkan aktivitas pelanggan dalam menggunakan layanan GOKILAU.
    </p>
</div>

<?php } elseif ($menu == 'laporan_laris') { ?>

<div class="card">
    <h4>🔥 Laporan Layanan Terlaris</h4>
    <p style="color:#6b7c93; line-height:1.7">
        Menunjukkan layanan yang paling sering digunakan pelanggan.
    </p>
</div>

    <?php } elseif ($menu == 'bantuan') { ?>

        <div class="card">
    <h4>❓ Bantuan Pemilik</h4>
    <p style="color:#6b7c93; line-height:1.7">
        Menu bantuan berisi panduan singkat penggunaan sistem
        bagi pemilik bengkel.
        <br><br>
        Gunakan menu di sidebar untuk berpindah antar laporan
        dan memantau kinerja sistem GOKILAU dengan mudah.
    </p>
</div>

    <?php } ?>

</div>

<div class="footer">
    © 2025 GOKILAU – Pemilik System
</div>

<script>
function toggleLaporan() {
    const menu = document.getElementById("laporan-menu");
    menu.style.display = menu.style.display === "none" ? "block" : "none";
}
</script>
</body>
</html>