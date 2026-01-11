<?php
include "koneksi.php";

if (!isset($_SESSION['admin_login'])) {
    header("Location: admin_login.php");
    exit;
}

$admin = $_SESSION['admin'];
$menu  = $_GET['menu'] ?? 'dashboard';

/* ==============================
   TAMBAH & HAPUS PAKET LAYANAN
   ============================== */

// TAMBAH LAYANAN
if (isset($_POST['tambah_layanan'])) {
    mysqli_query($conn, "
        INSERT INTO paket_layanan 
        VALUES (
            '',
            '$_POST[nama]',
            '$_POST[deskripsi]',
            '$_POST[harga]',
            '$_POST[estimasi]'
        )
    ");
    header("Location: admin_dashboard.php?menu=layanan");
    exit;
}

// HAPUS LAYANAN
if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM paket_layanan WHERE id='" . $_GET['hapus'] . "'");
    header("Location: admin_dashboard.php?menu=layanan");
    exit;
}

// PAGINATION
$batas   = 5; // jumlah data per halaman
$halaman = isset($_GET['hal']) ? (int)$_GET['hal'] : 1;
$mulai   = ($halaman - 1) * $batas;

// EDIT LAYANAN
if (isset($_POST['edit_layanan'])) {
    mysqli_query($conn, "
        UPDATE paket_layanan SET
            nama_layanan = '" . $_POST['nama'] . "',
            deskripsi    = '" . $_POST['deskripsi'] . "',
            harga        = '" . $_POST['harga'] . "',
            estimasi     = '" . $_POST['estimasi'] . "'
        WHERE id = '" . $_POST['id'] . "'
    ");
    header("Location: admin_dashboard.php?menu=layanan");
    exit;
}

require_once "vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;

// ==============================
// UPDATE STATUS / RESCHEDULE / EMAIL / NOTIFIKASI
// ==============================
if (isset($_POST['update_status'])) {

    $id = (int)$_POST['id'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $tanggal_layanan = $_POST['tanggal_layanan'] ?? null;
    $alasan = mysqli_real_escape_string($conn, $_POST['alasan_batal'] ?? '');

    // =========================
    // AMBIL DATA PESANAN + PELANGGAN (WAJIB DI ATAS)
    // =========================
    $q = mysqli_query($conn, "
        SELECT p.*, pl.email, pl.username, pl.id AS id_pelanggan
        FROM pesanan p
        JOIN pelanggan pl ON p.id_pelanggan = pl.id
        WHERE p.id='$id'
        LIMIT 1
    ");
    $d = mysqli_fetch_assoc($q);

    // =========================
    // UPDATE PESANAN (SATU KALI SAJA)
    // =========================
    $tanggal_sql = $tanggal_layanan
        ? "'$tanggal_layanan " . date('H:i:s') . "'"
        : "tanggal";

    mysqli_query($conn, "
        UPDATE pesanan 
        SET 
            status = '$status',
            tanggal = $tanggal_sql,
            alasan_batal = " . ($status=='Dibatalkan' ? "'$alasan'" : "NULL") . "
        WHERE id = '$id'
    ");

    // =========================
    // SIMPAN NOTIFIKASI
    // =========================
    $pesan_db = "🔔 Status pesanan <b>{$d['nama_layanan']}</b> diperbarui.<br>
                 Status: <b>{$status}</b>";

    if ($tanggal_layanan) {
        $pesan_db .= "<br>Tanggal Layanan: <b>"
                   . date('d M Y', strtotime($tanggal_layanan))
                   . "</b>";
    }

    if ($status == 'Dibatalkan') {
        $pesan_db = "❌ Pesanan <b>{$d['nama_layanan']}</b> dibatalkan.<br>
                     Alasan: {$alasan}";
    }

    mysqli_query($conn, "
    INSERT INTO notifikasi (id_pelanggan, pesan, status)
    VALUES (
        '{$d['id_pelanggan']}',
        '$pesan_db',
        'Belum Dibaca'
    )
");

    // =========================
    // EMAIL (OPSIONAL – SUDAH BENAR PUNYA KAMU)
    // =========================
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'oktad9126';
        $mail->Password   = 'eflocgysfoutrnys';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('EMAIL_KAMU', 'GOKILAU');
        $mail->addAddress($d['email'], $d['username']);
        $mail->isHTML(true);

        $mail->Subject = "Update Pesanan GOKILAU";
        $mail->Body = "
            <h3>Halo {$d['username']} 👋</h3>
            <p>Status pesanan <b>{$d['nama_layanan']}</b> diperbarui.</p>
            <p>Status: <b>{$status}</b></p>
            ".($tanggal_layanan ? "<p>Tanggal: <b>".date('d M Y',strtotime($tanggal_layanan))."</b></p>" : "")."
            <br><b>GOKILAU</b>
        ";

        $mail->send();
    } catch (Exception $e) {}

    header("Location: admin_dashboard.php?menu=detail_pesanan&id=$id");
    exit;
}

if (isset($_POST['tambah_pekerja'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $password_asli = $_POST['password'];
$hash = password_hash($password_asli, PASSWORD_DEFAULT);

    // cek username sudah ada atau belum
    $cek = mysqli_query($conn, "
        SELECT id FROM pekerja WHERE username='$user'
    ");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Username pekerja sudah digunakan');</script>";
    } else {
        mysqli_query($conn, "
    INSERT INTO pekerja (username, password, password_plain)
    VALUES ('$user', '$hash', '$password_asli')
");

        header("Location: admin_dashboard.php?menu=pekerja");
        exit;
    }
}

// HAPUS PEKERJA
if (isset($_GET['hapus_pekerja'])) {
    $id = (int)$_GET['hapus_pekerja'];

    mysqli_query($conn, "DELETE FROM pekerja WHERE id='$id'");

    header("Location: admin_dashboard.php?menu=pekerja");
    exit;
}

// ==============================
// GANTI PASSWORD PEKERJA (ADMIN)
// ==============================
if (isset($_POST['ganti_password'])) {

    $id = (int)$_POST['id'];
    $password_baru = $_POST['password_baru'];

    $hash = password_hash($password_baru, PASSWORD_DEFAULT);

    mysqli_query($conn, "
        UPDATE pekerja SET
            password = '$hash',
            password_plain = '$password_baru'
        WHERE id = '$id'
    ");

    header("Location: admin_dashboard.php?menu=pekerja&detail=$id");
    exit;
}

?>



<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard | GOKILAU</title>

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
    top: 0;
    left: 0;
    color: white;
    display: flex;
    flex-direction: column;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 30px;
    letter-spacing: 1px;
}

.sidebar a {
    display: block;
    padding: 10px 14px;
    margin-bottom: 8px;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 14px;
}

.sidebar-menu {
    flex: 1;
    overflow-y: auto;
    padding: 10px 10px 20px;
}

/* Scrollbar rapi */
.sidebar-menu::-webkit-scrollbar {
    width: 6px;
}

.sidebar-menu::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.3);
    border-radius: 10px;
}

.submenu {
    margin-left: 12px;
    margin-bottom: 10px;
}

.submenu a {
    font-size: 13px;
    padding: 8px 12px;
    opacity: 0.9;
}

.sidebar a.active,
.sidebar a:hover {
    background: rgba(255,255,255,0.2);
}

/* ===== CONTENT ===== */
.content {
    margin-left: 260px;
    padding: 30px;
}

/* ===== HEADER ===== */
.header {
    background: white;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    margin-bottom: 25px;
}

.header h3 {
    margin: 0;
}

.header p {
    color: #6b7c93;
    font-size: 14px;
}

/* ===== CARD ===== */
.card {
    background: white;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    text-align: center;
    color: #6b7c93;
}

.card h4 {
    color: #1E3C72;
    margin-bottom: 10px;
}

.btn {
    padding: 10px 16px;
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 14px;
}

.btn:hover {
    opacity: 0.9;
}

.form-box {
    background: #f7faff;
    padding: 20px;
    border-radius: 14px;
    margin-bottom: 20px;
    display: none;
}

.form-box input,
.form-box textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 12px;
    border-radius: 8px;
    border: 1px solid #c9d8f0;
}

.form-box textarea {
    resize: vertical;
}

.btn-cancel {
    background: #aaa;
    margin-left: 10px;
}

/* ===== TABLE ===== */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: #fff;
}

.table th {
    background: #f0f6ff;
    color: #1E3C72;
    padding: 12px;
    font-size: 14px;
    border: 1px solid #dbe6ff;
    text-align: center;
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

/* Kolom spesifik */
.col-no {
    width: 50px;
}

.col-harga {
    white-space: nowrap;
}

.col-estimasi {
    white-space: nowrap;
}

.col-aksi {
    white-space: nowrap;
}

/* Deskripsi rapi */
.deskripsi {
    color: #4a5d73;
    line-height: 1.5;
}

/* ===== BUTTON ===== */
.btn-edit {
    background: #3A7BD5;
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
}

.btn-hapus {
    background: #e74c3c;
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 13px;
    margin-left: 4px;
}

.btn-edit:hover,
.btn-hapus:hover {
    opacity: 0.85;
}

/* ===== PAGINATION ===== */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 25px;
    gap: 6px;
}

.pagination a {
    padding: 8px 12px;
    border-radius: 8px;
    text-decoration: none;
    background: #e5edff;
    color: #1E3C72;
    font-size: 14px;
}

.pagination a.active {
    background: #1E3C72;
    color: white;
}

.btn-edit,
.btn-hapus {
    display: inline-block;
    cursor: pointer;
}

/* ===== FOOTER ADMIN ===== */
.admin-footer {
    margin-left: 260px;
    background: #1E3C72;
    color: #e8f0ff;
    padding: 16px 30px;
    font-size: 13px;
    text-align: center;
    border-top: 1px solid rgba(255,255,255,0.2);
}

/* ===== FORM PEKERJA ===== */
.form-pekerja {
    background: #f7faff;
    padding: 22px;
    border-radius: 14px;
    border: 1px solid #dbe6ff;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #1E3C72;
    margin-bottom: 6px;
}

.form-group input {
    width: 100%;
    padding: 11px 14px;
    border-radius: 10px;
    border: 1px solid #c9d8f0;
    font-size: 14px;
    outline: none;
    transition: 0.2s;
}

.form-group input:focus {
    border-color: #3A7BD5;
    box-shadow: 0 0 0 2px rgba(58,123,213,0.15);
}

/* ===== AKSI TOMBOL PEKERJA ===== */
.aksi-group {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.btn-reset {
    background: linear-gradient(135deg, #3A7BD5, #5FA8FF);
    color: #fff;
    padding: 7px 14px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: 0.25s;
}

.btn-reset:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(58,123,213,0.35);
}

.btn-hapus {
    background: linear-gradient(135deg, #e74c3c, #ff6b6b);
    color: #fff;
    padding: 7px 14px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: 0.25s;
}

.btn-hapus:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(231,76,60,0.35);
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

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>ADMIN</h2>

    <div class="sidebar-menu">

        <a href="?menu=dashboard" class="<?= $menu=='dashboard'?'active':'' ?>">📊 Dashboard</a>
        <a href="?menu=layanan" class="<?= $menu=='layanan'?'active':'' ?>">🔧 Paket Layanan</a>
        <a href="?menu=pesanan" class="<?= $menu=='pesanan'?'active':'' ?>">📦 Pesanan</a>
        <a href="?menu=testimoni" class="<?= $menu=='testimoni'?'active':'' ?>">
    ⭐ Testimoni
</a>
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

        <a href="?menu=pekerja" class="<?= $menu=='pekerja'?'active':'' ?>">👷 Akun Pekerja</a>
        <a href="?menu=bantuan" class="<?= $menu=='bantuan'?'active':'' ?>">❓ Bantuan</a>

        <a href="admin_logout.php">🚪 Logout</a>

    </div>
</div>

<!-- CONTENT -->
<div class="content">

    <div class="header">
        <h3>Halo, <?= htmlspecialchars($admin['nama']) ?> 👋</h3>
        <p>Kelola sistem bengkel digital GOKILAU</p>
    </div>

    <?php if ($menu == 'dashboard') { ?>
        <div class="card">
            <h4>Dashboard Admin</h4>
            <p>Ringkasan sistem akan ditampilkan di sini.</p>
        </div>

    <?php } elseif ($menu == 'layanan') { ?>

<div class="card" style="text-align:left">

    <div style="display:flex;justify-content:space-between;align-items:center;">
        <h4>📦 Paket Layanan</h4>
        <button class="btn" onclick="toggleForm()">+ Tambah Paket</button>
    </div>

    <!-- FORM TAMBAH (SEMBUNYI DULU) -->
    <div class="form-box" id="formPaket">
        <form method="post">
            <input type="text" name="nama" placeholder="Nama Layanan" required>
            <textarea name="deskripsi" placeholder="Deskripsi Layanan" required></textarea>
            <input type="number" name="harga" placeholder="Harga" required>
            <input type="text" name="estimasi" placeholder="Estimasi Waktu (contoh: 1 jam)" required>

            <button class="btn" name="tambah_layanan">Simpan</button>
            <button type="button" class="btn btn-cancel" onclick="toggleForm()">Batal</button>
        </form>
    </div>

    <!-- LIST -->
     <form method="get" style="margin:15px 0; display:flex; gap:10px;">
    <input type="hidden" name="menu" value="layanan">

    <input type="text" name="q"
           placeholder="Cari paket layanan..."
           value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
           style="flex:1; padding:10px; border-radius:8px; border:1px solid #c9d8f0;">

    <button class="btn" type="submit">Cari</button>
</form>

    <table class="table">
<tr>
    <th class="col-no">No</th>
    <th>Nama</th>
    <th>Deskripsi</th>
    <th class="col-harga">Harga</th>
    <th class="col-estimasi">Estimasi</th>
    <th class="col-aksi">Aksi</th>
</tr>

<?php
$no = $mulai + 1;
$q = isset($_GET['q']) ? trim($_GET['q']) : '';
$where = '';

if ($q !== '') {
    $q_safe = mysqli_real_escape_string($conn, $q);
    $where = "WHERE nama_layanan LIKE '%$q_safe%'";
}
$data = mysqli_query($conn, "
    SELECT * FROM paket_layanan
    $where
    ORDER BY LOWER(nama_layanan) ASC
    LIMIT $mulai, $batas
");
$total = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM paket_layanan $where")
);
$total_halaman = ceil($total / $batas);

while ($row = mysqli_fetch_assoc($data)) {
?>
<tr>
    <td class="col-no"><?= $no++ ?></td>
    <td><strong><?= htmlspecialchars($row['nama_layanan']) ?></strong></td>
    <td class="deskripsi">
        <?= htmlspecialchars(substr($row['deskripsi'], 0, 90)) ?>
        <?= strlen($row['deskripsi']) > 90 ? '...' : '' ?>
    </td>
    <td class="col-harga">
    Rp <?= number_format($row['harga'], 0, ',', '.') ?>
</td>
    <td class="col-estimasi"><?= htmlspecialchars($row['estimasi']) ?></td>
    <td class="col-aksi">
    <a href="?menu=layanan&edit=<?= $row['id'] ?>" class="btn-edit">
        Edit
    </a>
    <a href="?menu=layanan&hapus=<?= $row['id'] ?>"
       class="btn-hapus"
       onclick="return confirm('Hapus paket ini?')">
       Hapus
    </a>
</td>
</tr>
<?php } ?>

<?php
// AMBIL DATA YANG AKAN DIEDIT
$editData = null;
if (isset($_GET['edit'])) {
    $idEdit = $_GET['edit'];
    $qEdit = mysqli_query($conn, "SELECT * FROM paket_layanan WHERE id='$idEdit'");
    $editData = mysqli_fetch_assoc($qEdit);
}
?>

<?php if ($editData) { ?>
<div class="form-box" style="display:block">
    <h4>✏️ Edit Paket Layanan</h4>

    <form method="post">
        <input type="hidden" name="id" value="<?= $editData['id'] ?>">

        <input type="text" name="nama"
               value="<?= htmlspecialchars($editData['nama_layanan']) ?>" required>

        <textarea name="deskripsi" required><?= htmlspecialchars($editData['deskripsi']) ?></textarea>

        <input type="number" name="harga"
               value="<?= $editData['harga'] ?>" required>

        <input type="text" name="estimasi"
               value="<?= htmlspecialchars($editData['estimasi']) ?>" required>

        <button class="btn" name="edit_layanan">Simpan Perubahan</button>
        <a href="?menu=layanan" class="btn btn-cancel">Batal</a>
    </form>
</div>
<hr>
<?php } ?>
</table>

<div class="pagination">
<?php
$total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM paket_layanan"));
$total_halaman = ceil($total / $batas);

for ($i = 1; $i <= $total_halaman; $i++) {
?>
    <a href="?menu=layanan&hal=<?= $i ?>&q=<?= urlencode($q) ?>"
   class="<?= $i == $halaman ? 'active' : '' ?>">
   <?= $i ?>
</a>
<?php } ?>
</div>

    <?php } elseif ($menu == 'pesanan') { ?>

<?php
$q = mysqli_query($conn, "
    SELECT 
        pesanan.*,
        pelanggan.username,
        pelanggan.no_telp
    FROM pesanan
    JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id
    ORDER BY pesanan.tanggal DESC
");
?>

<div class="card">
    <h4>📦 Pesanan Pelanggan</h4>

    <?php if (mysqli_num_rows($q) == 0) { ?>
        <p style="text-align:center;color:#6b7c93">
            Belum ada pesanan.
        </p>
    <?php } else { ?>

    <table class="table">
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>No HP</th>
            <th>Layanan</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php $no=1; while ($row = mysqli_fetch_assoc($q)) { ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= preg_replace('/[^0-9]/','',$row['no_telp']) ?></td>
            <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
            <td><?= date('d M Y H:i', strtotime($row['tanggal'])) ?></td>
            <td>
                <span class="badge status-<?= strtolower($row['status']) ?>">
                    <?= $row['status'] ?>
                </span>
            </td>
            <td>
                <a href="?menu=detail_pesanan&id=<?= $row['id'] ?>" class="btn-edit">
                    Detail
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <?php } ?>
    <?php } elseif ($menu == 'detail_pesanan') { ?>

<?php
$id = (int)$_GET['id'];

$q = mysqli_query($conn, "
    SELECT pesanan.*, pelanggan.username, pelanggan.no_telp
    FROM pesanan
    JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id
    WHERE pesanan.id='$id'
    LIMIT 1
");

$data = mysqli_fetch_assoc($q);

if (!$data) {
    echo "Pesanan tidak ditemukan";
    exit;
}

if ($data['status'] == 'Dibatalkan' && !empty($data['alasan_batal'])) { ?>
    <div style="margin-top:15px;padding:12px;
                background:#fff0f0;
                border-radius:10px;
                color:#c0392b">
        <b>Alasan Pembatalan:</b><br>
        <?= htmlspecialchars($data['alasan_batal']) ?>
    </div>
<?php } ?>

<div class="card" style="text-align:left">
    <h4>🧾 Detail Pesanan</h4>

    <p><b>Nama Pelanggan:</b> <?= htmlspecialchars($data['username']) ?></p>
    <p><b>No. HP:</b> <?= preg_replace('/[^0-9]/','',$data['no_telp']) ?></p>
    <p><b>Layanan:</b> <?= htmlspecialchars($data['nama_layanan']) ?></p>
    <p><b>Tanggal:</b> <?= date('d M Y H:i', strtotime($data['tanggal'])) ?></p>
    <p><b>Status Saat Ini:</b> <b><?= $data['status'] ?></b></p>

    <p><b>Tanggal Layanan Saat Ini:</b>
<?= date('d M Y', strtotime($data['tanggal'])) ?>
</p>
    <hr>
    
    <!-- FORM UBAH STATUS PESANAN -->
<form method="post">

    <input type="hidden" name="id" value="<?= $data['id'] ?>">

    <label><b>📆 Tanggal Layanan</b></label><br><br>
    <input type="date"
       name="tanggal_layanan"
       value="<?= date('Y-m-d', strtotime($data['tanggal'])) ?>"
       style="padding:10px;border-radius:8px;border:1px solid #c9d8f0">

    <br><br>

    <label><b>Ubah Status</b></label><br><br>
    <select name="status" required style="padding:10px;border-radius:8px">
        <option value="Menunggu"   <?= $data['status']=='Menunggu'?'selected':'' ?>>Menunggu</option>
        <option value="Diproses"   <?= $data['status']=='Diproses'?'selected':'' ?>>Diproses</option>
        <option value="Selesai"    <?= $data['status']=='Selesai'?'selected':'' ?>>Selesai</option>
        <option value="Dibatalkan" <?= $data['status']=='Dibatalkan'?'selected':'' ?>>Dibatalkan</option>
    </select>

    <br><br>

    <label><b>Alasan Pembatalan</b></label>
    <textarea name="alasan_batal"
              placeholder="Wajib diisi jika dibatalkan"
              style="width:100%;padding:10px;border-radius:10px;border:1px solid #c9d8f0"></textarea>

    <br><br>

    <button class="btn" name="update_status">
        💾 Simpan Perubahan
    </button>

    <a href="?menu=pesanan" class="btn btn-cancel">
        ← Kembali
    </a>

</form>
</div>

<?php } elseif ($menu == 'testimoni') { ?>

<?php
$testimoni = mysqli_query($conn, "
    SELECT 
        t.rating,
        t.isi,
        t.created_at,
        p.nama_layanan,
        pl.username
    FROM testimoni t
    JOIN pesanan p ON t.id_pesanan = p.id
    JOIN pelanggan pl ON t.id_pelanggan = pl.id
    ORDER BY t.created_at DESC
");
?>

<div class="card" style="text-align:left">
    <h4>⭐ Testimoni Pelanggan</h4>

    <?php if (mysqli_num_rows($testimoni) == 0) { ?>
        <p style="color:#6b7c93">Belum ada testimoni.</p>
    <?php } ?>

    <?php while ($t = mysqli_fetch_assoc($testimoni)) { ?>
    <div style="
        background:#f7faff;
        padding:18px;
        border-radius:14px;
        margin-bottom:16px;
        border-left:5px solid #3A7BD5
    ">

        <div style="display:flex;justify-content:space-between">
            <b><?= htmlspecialchars($t['username']) ?></b>
            <span style="font-size:12px;color:#6b7c93">
                <?= date('d M Y H:i', strtotime($t['created_at'])) ?>
            </span>
        </div>

        <div style="font-size:13px;color:#1E3C72;margin-top:4px">
            🚗 <?= htmlspecialchars($t['nama_layanan']) ?>
        </div>

        <div style="margin:6px 0">
            <?php
            for ($i=1;$i<=5;$i++) {
                echo $i <= $t['rating'] ? '⭐' : '☆';
            }
            ?>
        </div>

        <div style="font-size:14px;color:#333">
            <?= nl2br(htmlspecialchars($t['isi'])) ?>
        </div>

    </div>
    <?php } ?>
</div>

    <?php } elseif ($menu == 'laporan_transaksi') { ?>
    <?php
$tgl_mulai  = $_GET['tgl_mulai'] ?? '';
$tgl_akhir  = $_GET['tgl_akhir'] ?? '';
$status     = $_GET['status'] ?? '';

$where = "WHERE 1=1";

if ($tgl_mulai && $tgl_akhir) {
    $where .= " AND DATE(pesanan.tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'";
}

if ($status) {
    $where .= " AND pesanan.status = '$status'";
}
?>

<form method="get" style="margin-bottom:20px;display:flex;gap:10px;flex-wrap:wrap">
    <input type="hidden" name="menu" value="laporan_transaksi">

    <input type="date" name="tgl_mulai" value="<?= $tgl_mulai ?>"
           style="padding:10px;border-radius:8px;border:1px solid #c9d8f0">

    <input type="date" name="tgl_akhir" value="<?= $tgl_akhir ?>"
           style="padding:10px;border-radius:8px;border:1px solid #c9d8f0">

    <select name="status"
            style="padding:10px;border-radius:8px;border:1px solid #c9d8f0">
        <option value="">Semua Status</option>
        <option value="Menunggu"   <?= $status=='Menunggu'?'selected':'' ?>>Menunggu</option>
        <option value="Diproses"   <?= $status=='Diproses'?'selected':'' ?>>Diproses</option>
        <option value="Selesai"    <?= $status=='Selesai'?'selected':'' ?>>Selesai</option>
        <option value="Dibatalkan" <?= $status=='Dibatalkan'?'selected':'' ?>>Dibatalkan</option>
    </select>

    <button class="btn">🔍 Filter</button>

    <a href="?menu=laporan_transaksi" class="btn btn-cancel">
        Reset
    </a>
    
    <a href="laporan_transaksi_pdf.php?
menu=laporan_transaksi
&tgl_mulai=<?= $tgl_mulai ?>
&tgl_akhir=<?= $tgl_akhir ?>
&status=<?= $status ?>"
class="btn"
target="_blank">
📄 Export PDF
</a>

</form>


<?php
$laporan = mysqli_query($conn, "
    SELECT 
        pesanan.id,
        pesanan.nama_layanan,
        pesanan.harga,
        pesanan.status,
        pesanan.tanggal,
        pelanggan.username
    FROM pesanan
    JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id
    $where
    ORDER BY pesanan.tanggal DESC
");

$total_pendapatan = 0;
?>

<div class="card" style="text-align:left">
    <h4>📑 Laporan Transaksi</h4>
    <p style="color:#6b7c93;font-size:14px">
        Data seluruh transaksi pelanggan.
    </p>

    <?php if (mysqli_num_rows($laporan) == 0) { ?>
        <p style="color:#6b7c93">Belum ada transaksi.</p>
    <?php } else { ?>

    <table class="table">
        <tr>
            <th>No</th>
            <th>Pelanggan</th>
            <th>Layanan</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Harga</th>
        </tr>

        <?php $no=1; while ($l = mysqli_fetch_assoc($laporan)) { ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($l['username']) ?></td>
            <td><?= htmlspecialchars($l['nama_layanan']) ?></td>
            <td><?= date('d M Y H:i', strtotime($l['tanggal'])) ?></td>
            <td><?= $l['status'] ?></td>
            <td>
                Rp <?= number_format($l['harga'], 0, ',', '.') ?>
            </td>
        </tr>

        <?php 
            if ($l['status'] == 'Selesai') {
                $total_pendapatan += $l['harga'];
            }
        } ?>
    </table>

    <div style="
        margin-top:20px;
        background:#f1f6ff;
        padding:16px;
        border-radius:12px;
        font-size:14px;
        color:#1E3C72
    ">
        <b>💰 Total Pendapatan (Transaksi Selesai):</b><br>
        Rp <?= number_format($total_pendapatan, 0, ',', '.') ?>
    </div>

    <?php } ?>
</div>

        

<?php } elseif ($menu == 'pekerja') { ?>

<?php
$aksi = $_GET['aksi'] ?? '';
$pekerja = mysqli_query($conn, "SELECT * FROM pekerja ORDER BY id DESC");
?>

<?php if ($aksi == 'tambah') { ?>

<!-- =======================
     FORM TAMBAH PEKERJA
======================= -->

<div class="card" style="max-width:520px;margin:auto">

    <h4>➕ Tambah Akun Pekerja</h4>
    <p style="font-size:14px;color:#6b7c93;margin-bottom:20px">
        Buat akun pekerja baru.
    </p>

    <form method="post">

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div style="display:flex;justify-content:space-between;margin-top:22px">
            <a href="?menu=pekerja" class="btn btn-cancel">
                ← Kembali
            </a>

            <button class="btn" name="tambah_pekerja">
                💾 Simpan Akun
            </button>
        </div>

    </form>
</div>

<?php } else { ?>

<!-- =======================
     TABEL PEKERJA
======================= -->

<div class="card">

    <div style="display:flex;justify-content:space-between;align-items:center">
        <h4>📋 Daftar Akun Pekerja</h4>

        <a href="?menu=pekerja&aksi=tambah" class="btn">
            ➕ Tambah Akun
        </a>
    </div>

    <?php if (mysqli_num_rows($pekerja) == 0) { ?>
        <p style="color:#6b7c93;margin-top:15px">
            Belum ada akun pekerja.
        </p>
    <?php } else { ?>
    
    <?php
if (isset($_GET['detail'])) {
    $id = (int)$_GET['detail'];

    $d = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT username, password_plain
        FROM pekerja
        WHERE id='$id'
    "));
?>
<div class="card" style="max-width:520px;margin:auto;text-align:left">

    <h4>👤 Detail Akun Pekerja</h4>

    <p><b>Username:</b><br>
        <?= htmlspecialchars($d['username']) ?>
    </p>

    <p><b>Password</b></p>

<div style="display:flex;align-items:center;gap:10px">
    <input type="password"
           id="passwordField"
           value="<?= htmlspecialchars($d['password_plain']) ?>"
           readonly
           style="
               flex:1;
               padding:10px;
               border-radius:8px;
               border:1px solid #c9d8f0;
               font-size:14px
           ">

    <button type="button"
            onclick="togglePassword()"
            style="
                border:none;
                background:#3A7BD5;
                color:white;
                padding:10px 14px;
                border-radius:8px;
                cursor:pointer;
                font-size:16px
            ">
        <span id="eyeIcon">👁</span>
    </button>
</div>


    <div style="
        margin-top:14px;
        background:#fff3cd;
        padding:14px;
        border-radius:10px;
        font-size:13px;
        color:#856404
    ">
        ⚠️ Password ditampilkan untuk kebutuhan administrasi.
        Pastikan hanya admin yang dapat mengakses halaman ini.
    </div>

<hr style="margin:25px 0">

<h4>🔐 Ganti Password</h4>

<form method="post">

    <input type="hidden" name="id" value="<?= $id ?>">

    <input type="password"
           name="password_baru"
           placeholder="Password baru"
           required
           style="width:100%;padding:10px;border-radius:8px;border:1px solid #c9d8f0">

    <br><br>

    <div style="display:flex; gap:12px; margin-top:16px">

    <a href="?menu=pekerja" class="btn btn-cancel">
        ← Kembali
    </a>

    <button class="btn" name="ganti_password">
        💾 Simpan Perubahan
    </button>

</div>
</div>
<?php

}
?>

    <table class="table" style="margin-top:18px">
        <tr>
            <th width="50">No</th>
            <th>Username</th>
            <th width="220">Aksi</th>
        </tr>

        <?php $no=1; while ($p = mysqli_fetch_assoc($pekerja)) { ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($p['username']) ?></td>
            <td>
    <div class="aksi-group">

    <a href="?menu=pekerja&detail=<?= $p['id'] ?>"
       class="btn-edit">
        👁 Detail
    </a>
    
    <!-- HAPUS -->
        <a href="?menu=pekerja&hapus_pekerja=<?= $p['id'] ?>"
           class="btn-hapus"
           onclick="return confirm('Yakin ingin menghapus akun pekerja ini?')">
            🗑 Hapus
        </a>

    </div>
</td>

        </tr>
        <?php } ?>
    </table>

    <?php } ?>
</div>

<?php } ?>

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
        ℹ️ Jika mengalami kendala, silakan hubungi pengelola bengkel.
    </div>
</div>

<?php } ?>

</div>

<script>
function toggleLaporan() {
    const menu = document.getElementById("laporan-menu");
    menu.style.display = menu.style.display === "none" ? "block" : "none";
}
</script>

<script>
function toggleForm() {
    const form = document.getElementById("formPaket");
    form.style.display = form.style.display === "block" ? "none" : "block";
}
</script>
<div class="admin-footer">
        © 2025 GOKILAU – Admin System
    </div>
    
    <script>
function togglePassword() {
    const field = document.getElementById("passwordField");
    const icon  = document.getElementById("eyeIcon");

    // CEGAH ERROR JIKA ELEMENT TIDAK ADA
    if (!field || !icon) {
        alert("Field password tidak ditemukan");
        return;
    }

    if (field.type === "password") {
        field.type = "text";
        icon.textContent = "🙈"; // password terlihat
    } else {
        field.type = "password";
        icon.textContent = "👁"; // password tersembunyi
    }
}
</script>

</body>
</html>