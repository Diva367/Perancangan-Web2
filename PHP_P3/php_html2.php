<!DOCTYPE html>
<html lang="en">
<head>
  <title>Contoh 2 - PHP di dalam HTML</title>
</head>

<body>

<?php
// Siapkan variabel dulu di atas
$nama = "Diva";
$umur = 20;
?>

<h2>Halo, <?php echo $nama; ?>!</h2>
<p>Umur kamu <?php echo $umur; ?> tahun.</p>
<p>Senang bertemu denganmu di halaman PHP ini!</p>

</body>
</html>