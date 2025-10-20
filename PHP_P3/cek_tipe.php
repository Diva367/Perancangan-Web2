<!DOCTYPE html>
<html lang="en">
<head>
  <title>Cek Tipe Data</title>
</head>
<body>

<?php
$bil = 3;
$teks = "Halo";
$kosong = null;

echo "<h3>Contoh Fungsi is_()</h3>";

echo "\$bil adalah integer? ";
var_dump(is_int($bil));
echo "<br>";

echo "\$teks adalah string? ";
var_dump(is_string($teks));
echo "<br>";

echo "\$kosong adalah null? ";
var_dump(is_null($kosong));
echo "<br>";
?>

</body>
</html>