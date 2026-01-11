<?php
include "koneksi.php";

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id_pelanggan = $_SESSION['user']['id'];

mysqli_query($conn, "
    UPDATE notifikasi
    SET status = 'Dibaca'
    WHERE id_pelanggan = '$id_pelanggan'
");

$data = mysqli_query($conn, "
    SELECT * FROM notifikasi
    WHERE id_pelanggan = '$id_pelanggan'
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Notifikasi | GOKILAU</title>
<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f2f7ff;
    margin: 0;
}
.container {
    padding: 30px;
    max-width: 700px;
    margin: auto;
}
.notif {
    background: white;
    padding: 16px 18px;
    border-radius: 14px;
    box-shadow: 0 6px 20px rgba(30,60,114,0.08);
    margin-bottom: 14px;
}
.time {
    font-size: 12px;
    color: #6b7c93;
    margin-top: 6px;
}

.notif.unread {
    border-left: 5px solid #e74c3c;
    background: #fff5f5;
}

.notif.read {
    opacity: 0.8;
}
</style>
</head>
<body>

<div class="container">
    <h2>🔔 Notifikasi Saya</h2>

    <?php if (mysqli_num_rows($data) == 0) { ?>
        <p>Belum ada notifikasi.</p>
    <?php } ?>

    <?php while ($n = mysqli_fetch_assoc($data)) { ?>
        <div class="notif">
            <?= $n['pesan'] ?>
            <?php if (isset($n['created_at'])) { ?>
                <div class="time">
                    <?= date('d M Y H:i', strtotime($n['created_at'])) ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <div class="notif <?= $n['status']=='Belum Dibaca' ? 'unread' : 'read' ?>">

    <a href="beranda.php" style="text-decoration:none;color:#3A7BD5;font-weight:600">
        ← Kembali
    </a>
</div>

</body>
</html>