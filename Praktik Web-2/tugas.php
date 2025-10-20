<?php
echo "<h2>Tes Psikologi Pola Angka</h2>";

// a. 4 6 9 13 18 ? ?
echo "<h3>a. 4, 6, 9, 13, 18, ?, ?</h3>";
$a = [4, 6, 9, 13, 18];
$selisih = 2; // selisih awal
for ($i = 5; $i < 7; $i++) {
    $selisih++;
    $a[$i] = end($a) + $selisih;
}
echo "Hasil: " . implode(", ", $a) . "<br>";
echo "<b>Jawaban: 24, 31</b><br><br>";

// b. 2 2 3 3 4 ? ?
echo "<h3>b. 2, 2, 3, 3, 4, ?, ?</h3>";
$b = [2, 2, 3, 3, 4];
$b[] = 4; // duplikasi angka 4
$b[] = 5; // angka selanjutnya
echo "Hasil: " . implode(", ", $b) . "<br>";
echo "<b>Jawaban: 4, 5</b><br><br>";

// c. 1 9 2 10 3 ? ?
echo "<h3>c. 1, 9, 2, 10, 3, ?, ?</h3>";
$c = [1, 9, 2, 10, 3];
$c[] = 11; // naik satu dari 10
$c[] = 4;  // naik satu dari 3
echo "Hasil: " . implode(", ", $c) . "<br>";
echo "<b>Jawaban: 11, 4</b><br>";
?>
