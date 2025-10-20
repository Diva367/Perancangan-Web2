<!DOCTYPE html>
<html lang="en">
<head>
  <title>Casting Tipe</title>
</head>
<body>

<?php
$str = "123abc";  // string

echo "<h3>Sebelum Casting</h3>";
echo "Tipe data \$str: " . gettype($str) . "<br>";  // string
echo "Isi: $str<br><br>";

// Casting ke integer
$bil = (int)$str;

echo "<h3>Setelah Casting</h3>";
echo "Tipe data \$bil: " . gettype($bil) . "<br>";  // integer
echo "Isi: $bil"; // 123
?>

</body>
</html>