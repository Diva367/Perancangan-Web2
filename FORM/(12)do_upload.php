<html>
<head>
<title></title>
</head>
<body>
<h1>Simpan file yang diupload</h1>
<br>
<?php
$namaFile = $_FILES['file1']['name'];
$namaSementara = $_FILES['file1']['tmp_name'];
$dirUpload = "hasilupload/";
$terupload = move_uploaded_file($namaSementara, $dirUpload.$namaFile);
if ($terupload) {
    echo "Upload berhasil!<br/>";
    echo "Link: <a href='".$dirUpload.$namaFile."'>".$namaFile."</a>";
} else {
    echo "Upload gagal!";
}
?>
</body>
</html>
