<?php
date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/vendor/autoload.php';
include 'koneksi.php';

// ambil data bunga
$result = $conn->query("SELECT * FROM images ORDER BY id DESC");

// Inisialisasi mPDF
$mpdf = new \Mpdf\Mpdf([
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 45,
    'margin_bottom' => 15,
    'default_font' => 'dejavusans'
]);

// 🌸 HEADER CLEAN ELEGANT AESTHETIC (tanpa ikon)
$header = "
<div style='
    text-align:center;
    padding:14px 0;
    border-bottom:2px solid #ff9acb;
'>

    <h2 style='
        color:#d63384;
        font-family:Poppins, sans-serif;
        font-size:24px;
        margin:0;
        font-weight:700;
        letter-spacing:0.5px;
    '>
        LAPORAN DATA BUNGA AESTHETIC
    </h2>

    <div style='
        font-size:11px;
        color:#b34b7d;
        margin-top:4px;
        font-weight:500;
    '>
        Dicetak pada: ".date("d M Y H:i")."
    </div>

</div>
";

$mpdf->SetHTMLHeader($header);

// 🌸 CSS untuk PDF
$css = "
<style>
body {
    font-family: Poppins, sans-serif;
    color: #662244;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th {
    background: #ffb3d9;
    color: #660033;
    padding: 10px;
    border: 1px solid #ff94c6;
    font-weight: bold;
}

td {
    padding: 10px;
    border: 1px solid #ffcadd;
    text-align: center;
}

.img-thumb {
    border: 2px solid #ff9ec7;
    border-radius: 10px;
}

.footer {
    text-align:center;
    margin-top:20px;
    font-size:12px;
    color:#d63384;
}
</style>
";

$mpdf->WriteHTML($css);

// 🌸 Isi PDF
$html = "
<table>
    <tr>
        <th>No</th>
        <th>Nama Bunga</th>
        <th>Foto</th>
    </tr>
";

$no = 1;
while ($row = $result->fetch_assoc()) {
    $html .= "
    <tr>
        <td>$no</td>
        <td>{$row['original_name']}</td>
        <td><img src='uploads/{$row['filename']}' width='80' class='img-thumb'></td>
    </tr>
    ";
    $no++;
}

$html .= "</table>";

$mpdf->WriteHTML($html);

// FOOTER aesthetic pink
$footer = "
<div class='footer'>
    Laporan Data Bunga — Generated with mPDF Aesthetic Pink
</div>
";

$mpdf->SetHTMLFooter($footer);

// Output PDF ke browser
$mpdf->Output("Laporan_Bunga_Aesthetic.pdf", "I");
?>