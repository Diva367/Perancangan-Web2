<?php
// Set zona waktu ke Indonesia (WIB)
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk ubah tanggal ke bahasa Indonesia
function tanggal_indo($timestamp) {
    // Daftar nama hari dan bulan dalam Bahasa Indonesia
    $hari = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];

    $bulan = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];

    // Ambil bagian tanggal dalam bahasa Inggris dulu
    $hariInggris = date('l', $timestamp);
    $bulanInggris = date('F', $timestamp);

    // Ubah ke Bahasa Indonesia
    $hariIndo = $hari[$hariInggris];
    $bulanIndo = $bulan[$bulanInggris];

    // Gabungkan hasil akhir
    return $hariIndo . ', ' . date('d', $timestamp) . ' ' . $bulanIndo . ' ' . date('Y - H:i:s', $timestamp) . ' WIB';
}

// Contoh prosedur
function do_print() {
    $timestamp = time();

    echo "Timestamp (detik sejak 1970): " . $timestamp . "<br>";
    echo "Waktu saat ini: " . tanggal_indo($timestamp);
}

// Jalankan
do_print();

echo '<br/><br/>';

// Contoh fungsi penjumlahan
function jumlah($a, $b) {
    return ($a + $b);
}

echo "Hasil penjumlahan 2 + 3 = " . jumlah(2, 3);
?>