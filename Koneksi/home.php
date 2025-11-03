<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Beranda</title>
<style>
body { font-family: Arial; background: #f0f8ff; text-align: center; padding: 50px; }
.container { background: white; padding: 40px; display: inline-block; border-radius: 10px; box-shadow: 0 0 10px #aaa; }
a { text-decoration: none; color: #007bff; font-weight: bold; }
</style>
</head>
<body>
<div class="container">
    <h2>Selamat Datang, <?php echo $_SESSION['username']; ?> 🎉</h2>
    <p>Anda berhasil login!</p>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
