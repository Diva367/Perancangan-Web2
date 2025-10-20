<?php
/**
 * Mencetak string
 * $teks = nilai string
 * $bold = argumen opsional (default: true)
 */
function print_teks($teks, $bold = true) {
    echo $bold ? '<b>'.$teks.'</b><br>' : $teks.'<br>';
}

print_teks('Indonesiaku');        // Huruf tebal
print_teks('Indonesiaku', false); // Huruf biasa
?>