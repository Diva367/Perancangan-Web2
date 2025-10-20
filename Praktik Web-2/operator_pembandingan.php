<?php
$x = 4;

$a = ($x == 4);
echo "\$a = " . ($a ? "true" : "false") . "<br>";

$b = ($x === "4");
echo "\$b = " . ($b ? "true" : "false") . "<br>";

$c = ($x != 4);
echo "\$c = " . ($c ? "true" : "false") . "<br>";

$d = ($x !== "4");
echo "\$d = " . ($d ? "true" : "false") . "<br>";

$e = ($x < 5);
echo "\$e = " . ($e ? "true" : "false") . "<br>";

$f = ($x > 5);
echo "\$f = " . ($f ? "true" : "false") . "<br>";

$g = ($x <= 4);
echo "\$g = " . ($g ? "true" : "false") . "<br>";

$h = ($x >= 5);
echo "\$h = " . ($h ? "true" : "false") . "<br>";
?>
