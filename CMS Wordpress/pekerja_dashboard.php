<?php
include "koneksi.php";

if (!isset($_SESSION['pekerja_login'])) {
    header("Location: pekerja_login.php");
    exit;
}

$pekerja = $_SESSION['pekerja'];
$menu = $_GET['menu'] ?? 'beranda';
$tab  = $_GET['tab']  ?? 'aktif';
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Pekerja | GOKILAU</title>

<style>
* {
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    margin: 0;
    background: #f4f7fb;
}

/* ===== NAVBAR ===== */
.navbar {
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    color: #fff;
    padding: 14px 26px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar b {
    font-size: 18px;
}

.navbar a {
    color: #fff;
    text-decoration: none;
    margin-left: 10px;
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 14px;
    background: rgba(255,255,255,0.15);
}

.navbar a:hover,
.navbar a.active {
    background: rgba(255,255,255,0.35);
}

/* ===== CONTENT ===== */
.container {
    padding: 30px;
    max-width: 1100px;
    margin: auto;
}

/* ===== CARD ===== */
.card {
    background: #fff;
    padding: 18px 22px;
    border-radius: 14px;
    box-shadow: 0 10px 28px rgba(30,60,114,0.10);
    margin-bottom: 16px;
}

/* ===== STATUS ===== */
.status { font-weight: 700; }
.status-menunggu { color: #b68900; }
.status-diproses { color: #1565c0; }
.status-selesai { color: #2e7d32; }
.status-dibatalkan { color: #b71c1c; }

/* ===== BUTTON ===== */
.btn {
    display: inline-block;
    padding: 8px 14px;
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    color: #fff;
    border-radius: 10px;
    font-size: 13px;
    text-decoration: none;
}

.btn:hover {
    opacity: 0.9;
}

/* ===== GRID ===== */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 18px;
}

/* ===== TAB ===== */
.tab {
    margin-bottom: 20px;
}

.tab a {
    display: inline-block;
    padding: 8px 16px;
    margin-right: 8px;
    border-radius: 10px;
    font-size: 13px;
    text-decoration: none;
    background: #e6efff;
    color: #1E3C72;
    font-weight: 600;
}

.tab a.active {
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    color: #fff;
}

/* ===== FOOTER ===== */
.footer {
    margin-top: 50px;
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    color: #eaf0ff;
    padding: 18px;
    font-size: 13px;
    text-align: center;
    border-top-left-radius: 18px;
    border-top-right-radius: 18px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <b>👷 PEKERJA</b>
    <div>
        <a href="?menu=beranda" class="<?= $menu=='beranda'?'active':'' ?>">Beranda</a>
        <a href="?menu=layanan" class="<?= $menu=='layanan'?'active':'' ?>">Paket Layanan</a>
        <a href="?menu=pesanan" class="<?= $menu=='pesanan'?'active':'' ?>">Pesanan</a>
        <a href="?menu=bantuan" class="<?= $menu=='bantuan'?'active':'' ?>">Bantuan</a>
        <a href="pekerja_logout.php">Logout</a>
    </div>
</div>

<div class="container">

<?php if ($menu == 'beranda') { ?>

    <div class="card">
        <h3>Halo, <?= htmlspecialchars($pekerja['username']) ?> 👋</h3>
        <p>Selamat bekerja hari ini. Tetap semangat 💪</p>
    </div>

<?php } elseif ($menu == 'layanan') { ?>

    <h3 style="color:#1E3C72">📦 Paket Layanan</h3>

    <div class="grid">
    <?php
    $q = mysqli_query($conn, "SELECT * FROM paket_layanan ORDER BY nama_layanan ASC");
    while ($row = mysqli_fetch_assoc($q)) {
    ?>
        <div class="card">
            <b><?= htmlspecialchars($row['nama_layanan']) ?></b><br><br>
            <div style="font-size:14px;color:#6b7c93;line-height:1.6">
                <?= nl2br(htmlspecialchars($row['deskripsi'])) ?>
            </div>
            <br>
            💰 Rp <?= number_format($row['harga'],0,',','.') ?><br>
            ⏱ <?= htmlspecialchars($row['estimasi']) ?>
        </div>
    <?php } ?>
    </div>

<?php } elseif ($menu == 'pesanan') { ?>

    <h3 style="color:#1E3C72">📋 Pesanan</h3>

    <!-- TAB -->
    <div class="tab">
        <a href="?menu=pesanan&tab=aktif" class="<?= $tab=='aktif'?'active':'' ?>">🕒 Pesanan Aktif</a>
        <a href="?menu=pesanan&tab=riwayat" class="<?= $tab=='riwayat'?'active':'' ?>">📚 Riwayat Pesanan</a>
    </div>
    
    <?php if ($tab == 'riwayat') { ?>

<form method="get" style="margin-bottom:20px">
    <input type="hidden" name="menu" value="pesanan">
    <input type="hidden" name="tab" value="riwayat">

    <div style="display:flex;gap:10px;flex-wrap:wrap">
        <input type="date" name="dari"
               value="<?= $_GET['dari'] ?? '' ?>"
               style="padding:8px;border-radius:8px;border:1px solid #c9d8f0">

        <input type="date" name="sampai"
               value="<?= $_GET['sampai'] ?? '' ?>"
               style="padding:8px;border-radius:8px;border:1px solid #c9d8f0">

        <select name="status"
                style="padding:8px;border-radius:8px;border:1px solid #c9d8f0">
            <option value="">Semua Status</option>
            <option value="Selesai" <?= (@$_GET['status']=='Selesai')?'selected':'' ?>>Selesai</option>
            <option value="Dibatalkan" <?= (@$_GET['status']=='Dibatalkan')?'selected':'' ?>>Dibatalkan</option>
        </select>

        <button class="btn">🔎 Filter</button>
    </div>
</form>

<?php } ?>


    <?php
    if ($tab == 'aktif') {
        $q = mysqli_query($conn, "
            SELECT p.*, pl.username
            FROM pesanan p
            JOIN pelanggan pl ON p.id_pelanggan = pl.id
            WHERE p.status IN ('Menunggu','Diproses')
            ORDER BY p.tanggal ASC
        ");
    } else {

    $where = "p.status IN ('Selesai','Dibatalkan')";

    if (!empty($_GET['dari'])) {
        $dari = mysqli_real_escape_string($conn, $_GET['dari']);
        $where .= " AND DATE(p.tanggal) >= '$dari'";
    }

    if (!empty($_GET['sampai'])) {
        $sampai = mysqli_real_escape_string($conn, $_GET['sampai']);
        $where .= " AND DATE(p.tanggal) <= '$sampai'";
    }

    if (!empty($_GET['status'])) {
        $status = mysqli_real_escape_string($conn, $_GET['status']);
        $where .= " AND p.status = '$status'";
    }

    $q = mysqli_query($conn, "
        SELECT p.*, pl.username
        FROM pesanan p
        JOIN pelanggan pl ON p.id_pelanggan = pl.id
        WHERE $where
        ORDER BY p.tanggal DESC
    ");
    }

    if (mysqli_num_rows($q) == 0) {
        echo "<p>Tidak ada data.</p>";
    }

    while ($row = mysqli_fetch_assoc($q)) {
    ?>
        <div class="card">
            <b><?= htmlspecialchars($row['nama_layanan']) ?></b><br><br>
            👤 Pelanggan: <?= htmlspecialchars($row['username']) ?><br>
            📅 <?= date('d M Y H:i', strtotime($row['tanggal'])) ?><br>
            Status:
            <span class="status status-<?= strtolower($row['status']) ?>">
                <?= $row['status'] ?>
            </span>
            <br><br>

            <a href="pekerja_detail_pesanan.php?id=<?= $row['id'] ?>" class="btn">
                🔍 Lihat Detail
            </a>
        </div>
    <?php } ?>

<?php } elseif ($menu == 'bantuan') { ?>

    <div class="card">
        <h4>❓ Bantuan</h4>
        <p>Hubungi admin jika ada kendala dalam pengerjaan pesanan.</p>
    </div>

<?php } ?>

</div>

<div class="footer">
    © 2025 GOKILAU – Dashboard Pekerja
</div>

</body>
</html>

