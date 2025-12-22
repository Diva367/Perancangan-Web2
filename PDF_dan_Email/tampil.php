<?php
session_start();

// 🛡️ BATAS WAKTU LOGIN — otomatis logout setelah 30 menit tidak aktif
$timeout = 1800;

// Jika belum login → paksa ke login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

// Jika sudah login tapi sesi terlalu lama tidak aktif → logout otomatis
if (isset($_SESSION['last_active'])) {
    if (time() - $_SESSION['last_active'] > $timeout) {
        session_destroy();
        header("Location: login.php?expired=true");
        exit;
    }
}

// Update aktivitas terakhir user
$_SESSION['last_active'] = time();

include 'koneksi.php';
// PAGINATION
$limit = 5;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// SEARCH
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';

// SORT
$sort = isset($_GET['sort']) && $_GET['sort'] === 'DESC' ? 'DESC' : 'ASC';

// WHERE
$where = "";
if ($keyword !== '') {
    $keyword = $conn->real_escape_string($keyword);
    $where = "WHERE original_name LIKE '%$keyword%'";
}

// TOTAL DATA
$totalData = $conn->query("
    SELECT COUNT(*) AS total
    FROM images
    $where
")->fetch_assoc()['total'];

$totalPage = ceil($totalData / $limit);

// DATA UTAMA (INI PENTING)
$result = $conn->query("
    SELECT *
    FROM images
    $where
    ORDER BY original_name $sort
    LIMIT $start, $limit
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🌸 Data Bunga Aesthetic 🌸</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Sacramento&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #ffd6e7, #ffeaf4, #ffe3f0);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding-bottom: 70px;
        }

        /* 🌸 TOPBAR NAVIGATION */
        .topbar {
            width: 100%;
            padding: 12px 25px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            background: #ffe1ef;
            box-shadow: 0 2px 6px rgba(255, 150, 180, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .username {
            margin-right: 15px;
            font-weight: 600;
            color: #b14575;
        }

        .logout-btn {
            background: #ff4d6d;
            color: white;
            padding: 8px 18px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #c71e3a;
            transform: scale(1.07);
        }

        /* Header */
        h2 {
            text-align: center;
            margin-top: 25px;
            font-family: 'Sacramento', cursive;
            font-size: 60px;
            color: #d63384;
            letter-spacing: 2px;
            text-shadow: 2px 3px rgba(255, 182, 193, 0.6);
        }

        .subtext {
            text-align: center;
            margin-top: -20px;
            margin-bottom: 30px;
            font-size: 16px;
            color: #a62d68;
        }

        /* 🌸 ACTION BAR */
        .action-bar {
            width: 85%;
            margin: 0 auto 25px;
            background: #ffe0ef;
            padding: 18px;
            border-radius: 18px;
            display: flex;
            justify-content: center;
            gap: 18px;
            box-shadow: 0 4px 12px rgba(255, 150, 180, 0.25);
        }

        .action-btn {
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            color: white;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s ease;
            box-shadow: 0 3px 6px rgba(255, 110, 150, 0.25);
        }

        .action-btn svg {
            margin-right: 4px;
        }

        .action-btn.add { background: #ff8bb3; }
        .action-btn.add:hover { background: #d63384; transform: translateY(-3px) scale(1.05); }

        .action-btn.pdf { background: #d94a85; }
        .action-btn.pdf:hover { background: #b13463; transform: translateY(-3px) scale(1.05); }

        .action-btn.email { background: #ff66a3; }
        .action-btn.email:hover { background: #d83d78; transform: translateY(-3px) scale(1.05); }

        /* Table */
        .table-wrapper {
            width: 85%;
            margin: auto;
            background: #ffffffcc;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 25px;
            backdrop-filter: blur(5px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #ff9ec7;
        }

        table, th, td { border: 1px solid #ff9ec7; }

        th {
            background: #ffb3d9;
            padding: 14px;
            font-size: 16px;
            color: #660033;
        }

        td {
            padding: 12px;
            text-align: center;
            color: #4a4a4a;
        }

        tr:hover { background: #fff5fb; }

        img {
            width: 100px;
            height: 120px;
            object-fit: cover;
            border-radius: 15px;
            border: 3px solid #ffb3d9;
            box-shadow: 0 3px 5px rgba(255, 192, 203, 0.6);
            transition: 0.3s;
        }

        img:hover { transform: scale(1.05); }

        .edit-btn{
    background:#ffb703;
    color:white;
    padding:6px 12px;
    border-radius:15px;
    font-size:14px;
    text-decoration:none;
    font-weight:600;
    margin-right:6px;
    transition:.3s;
}

.edit-btn:hover{
    background:#fb8500;
    transform:scale(1.08);
}

        .delete-btn {
            color: white;
            background: #ff4d6d;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 14px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .delete-btn:hover { background: #b3002d; transform: scale(1.08); }

        /* Pagination */
        .pagination { text-align: center; margin-top: 25px; }
        .page-number, .page-btn {
            padding: 8px 14px;
            margin: 3px;
            background: #ffb3d9;
            color: #660033;
            text-decoration: none;
            border-radius: 12px;
            transition: .3s;
            border: 1px solid #ff94c6;
        }
        .page-number:hover, .page-btn:hover {
            background: #ff85c2;
            transform: scale(1.07);
        }
        .active {
            background: #d63384 !important;
            color: white !important;
            transform: scale(1.1);
        }
        
        /* 🌸 FOOTER */
        .footer {
        width: 100%;
        position: fixed;
        bottom: 0;
        left: 0;
        background: linear-gradient(135deg, #ffe1ef, #ffd6e7);
        text-align: center;
        padding: 10px 0;
        font-size: 13px;
        color: #b14575;
        font-weight: 600;
        box-shadow: 0 -2px 6px rgba(255, 150, 180, 0.25);
        z-index: 99;
        }

        .footer span {
        color: #d63384;
        }

        .notif-success{
    position: fixed;
    top: 90px;
    right: 30px;

    /* 🌸 WARNA PINK CERAH */
    background: linear-gradient(135deg,#ffd6e9,#ffb3d9);

    color: #ffffffff;              /* teks pink tua lembut */
    padding: 16px 26px;
    border-radius: 22px;
    font-weight: 600;
    font-size: 15px;
    line-height: 1.6;

    display: flex;
    align-items: center;
    gap: 8px;

    box-shadow: 0 6px 18px rgba(255,182,213,.55);
    border: 1px solid #ff9ec7;

    z-index: 9999;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateX(30px); }
    to   { opacity: 1; transform: translateX(0); }
}

.notif-success{
    animation: slideIn .4s ease;
}
    </style>
</head>

<body>

<!-- 🌸 TOPBAR -->
<div class="topbar">
    <div class="username">Hai, <?= $_SESSION['user']; ?> 🌸</div>
    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="notif-success" id="notif">
        <?= $_SESSION['success']; ?>
    </div>
<?php unset($_SESSION['success']); endif; ?>

<h2>Data Bunga 🌸</h2>
<div class="subtext">Koleksi foto bunga yang cantik & aesthetic dari kamu 💗</div>
<form method="GET" style="text-align:center; margin-bottom:25px;">

    <!-- reset ke halaman 1 saat filter -->
    <input type="hidden" name="page" value="1">

    <!-- SEARCH -->
    <input type="text" name="keyword"
        value="<?= htmlspecialchars($keyword) ?>"
        placeholder="Cari nama bunga 🌸"
        style="
            padding:10px 18px;
            border-radius:20px;
            border:1px solid #ff9ec7;
            width:220px;
            margin-right:8px;
        ">

    <!-- SORT -->
    <select name="sort"
        onchange="this.form.submit()"
        style="
            padding:10px 18px;
            border-radius:20px;
            border:1px solid #ff9ec7;
            background:#ffe1ef;
            color:#b14575;
            font-weight:600;
        ">
        <option value="ASC"  <?= ($sort=='ASC')?'selected':'' ?>>A – Z</option>
        <option value="DESC" <?= ($sort=='DESC')?'selected':'' ?>>Z – A</option>
    </select>

    <!-- TOMBOL SEARCH -->
    <button type="submit"
        style="
            padding:10px 18px;
            border-radius:20px;
            border:none;
            background:#ff8bb3;
            color:white;
            font-weight:600;
            margin-left:8px;
            cursor:pointer;
        ">
        Cari
    </button>

</form>

<!-- 🌸 ACTION BAR -->
<div class="action-bar">

    <!-- Tambah Data -->
    <a href="upload_form.php" class="action-btn add">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5V7.5H11.5a.5.5 0 0 1 0 1H8.5V11.5a.5.5 0 0 1-1 0V8.5H4.5a.5.5 0 0 1 0-1H7.5V4.5A.5.5 0 0 1 8 4z"/>
        </svg>
        Tambah Data
    </a>

    <!-- Cetak PDF -->
<a href="cetak_pdf.php?keyword=<?= urlencode($keyword) ?>&sort=<?= $sort ?>"
   class="action-btn pdf">

    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
        <path d="M5 1a2 2 0 0 0-2 2v2h10V3a2 2 0 0 0-2-2H5z"/>
        <path d="M13 6H3a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v2h8v-2h1a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2z"/>
    </svg>

    Cetak PDF
</a>

    <!-- Email -->
    <a href="kirim_email.php" class="action-btn email">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="white" viewBox="0 0 16 16">
            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v.217l-8 4.8-8-4.8V4z"/>
            <path d="M0 6.383v5.617A2 2 0 0 0 2 14h12a2 2 0 0 0 2-2V6.383l-7.555 4.533a.5.5 0 0 1-.89 0L0 6.383z"/>
        </svg>
        Kirim Laporan
    </a>

</div>

<!-- TABLE -->
<div class="table-wrapper">
    <table>
        <tr>
            <th>NO</th>
            <th>NAMA</th>
            <th>FOTO</th>
            <th>AKSI</th>
        </tr>

        <?php 
        if ($result->num_rows > 0) {
            $no = $start + 1;
            while ($row = $result->fetch_assoc()) :
        ?>

        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['original_name']; ?></td>
            <td><img src="uploads/<?= $row['filename']; ?>"></td>
            <td>
                <a href="edit.php?id=<?= $row['id']; ?>" class="edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['id']; ?>"
                class="delete-btn"
                onclick="return confirm('Yakin mau hapus bunga ini beb? 🌸');">
                Hapus
                </a>
            </td>
        </tr>

        <?php endwhile; } else { ?>

        <tr><td colspan="4">Belum ada data bunga 🌸</td></tr>

        <?php } ?>
    </table>

    <!-- PAGINATION -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page-1 ?>&sort=<?= $sort ?>&keyword=<?= urlencode($keyword) ?>" class="page-btn">⬅ Prev</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPage; $i++): ?>
            <a href="?page=<?= $i ?>&sort=<?= $sort ?>&keyword=<?= urlencode($keyword) ?>"
   class="page-number <?= ($i==$page)?'active':'' ?>">
   <?= $i ?>
</a>

        <?php endfor; ?>

        <?php if ($page < $totalPage): ?>
            <a href="?page=<?= $page+1 ?>&sort=<?= $sort ?>&keyword=<?= urlencode($keyword) ?>" class="page-btn">Next ➡</a>
        <?php endif; ?>
    </div>

</div>

<div class="footer">
    © <?= date('Y') ?> <span>Sistem Bunga Aesthetic</span> 🌸 | Login sebagai <span><?= $_SESSION['user']; ?></span>
</div>

<script>
setTimeout(() => {
    const notif = document.getElementById('notif');
    if (notif) {
        notif.style.opacity = '0';
        notif.style.transform = 'translateX(30px)';
    }
}, 3000);
</script>
</body>
</html>