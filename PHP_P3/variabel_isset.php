<!DOCTYPE html>
<html lang="en">
<head>
  <title>Demo isset()</title>
</head>
<body>

<?php
$bil = 3;
$kosong = "";
$hilang = null;

echo "<h3>Pemeriksaan dengan isset()</h3>";

// Cara 1: gunakan <br> agar hasilnya ke bawah di browser
echo 'Apakah $bil diset? ';
var_dump(isset($bil));
echo "<br>";

echo 'Apakah $kosong diset? ';
var_dump(isset($kosong));
echo "<br>";

echo 'Apakah $hilang diset? ';
var_dump(isset($hilang));
echo "<br><br>";

// Tambahan: tampilkan isi variabel juga biar jelas
echo "<h3>Isi variabel:</h3>";
echo "\$bil = "; var_dump($bil); echo "<br>";
echo "\$kosong = "; var_dump($kosong); echo "<br>";
echo "\$hilang = "; var_dump($hilang); echo "<br>";
?>

</body>
</html>