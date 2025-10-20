<!DOCTYPE html>
<html lang="en">
<head>
  <title>Contoh 1 - PHP terpisah</title>
</head>

<body>

<?php
// Kode PHP berada di luar HTML
$nama = "Diva";
$umur = 20;

echo "<h2>Halo, $nama!</h2>";
echo "<p>Umur kamu $umur tahun.</p>";
?>

<p>Ini bagian HTML biasa, tidak ada PHP-nya.</p>

<?php
// PHP lagi, bisa lanjut di sini
echo "<p>Data di atas ditampilkan menggunakan PHP.</p>";
?>

</body>
</html>