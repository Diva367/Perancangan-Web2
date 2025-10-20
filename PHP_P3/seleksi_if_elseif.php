<!DOCTYPE html>
<html lang="en">
<head>
  <title>Seklesi if-elseif</title>
</head>
<body>

<?php
$a = 230224;
$b = 230224;

if ($a > $b) {
  echo 'a lebih besar dari b';
} elseif ($a == $b) {
  echo 'a sama dengan b';
} else {
  echo 'a kurang dari b';
}
?>

</body>
</html>