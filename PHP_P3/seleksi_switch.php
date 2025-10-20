<!DOCTYPE html>
<html lang="en">
<head>
  <title>Seklesi switch</title>
</head>
<body>

<?php
$i = 2;

// Menggunakan if-elseif
if ($i == 0) {
  echo "i equals 0";
} elseif ($i == 1) {
  echo "i equals 1";
} elseif ($i == 2) {
  echo "i equals 2";
}

// Ekuivalen dengan switch
switch ($i) {
  case 0:
    echo "i equals 0";
    break;
  case 1:
    echo "i equals 1";
    break;
  case 2:
    echo "i equals 2";
    break;
}
?>

</body>
</html>