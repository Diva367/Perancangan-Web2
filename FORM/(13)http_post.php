<!DOCTYPE html>
<html>
<head>
    <title>Upload File</title>
</head>
<body>
    <h1>Simpan File yang Diupload</h1>

    <?php
    if (isset($_FILES['file1'])) {
        $namafile = $_FILES['file1']['name'];
        $tmpfile = $_FILES['file1']['tmp_name'];

        echo "<p>Nama File : $namafile</p><br>";

        // Pastikan folder 'files' sudah ada
        if (!is_dir('files')) {
            mkdir('files', 0777, true);
        }

        // Pindahkan file ke folder 'files'
        if (move_uploaded_file($tmpfile, "files/$namafile")) {
            echo "<p>File berhasil disimpan di folder <strong>files/</strong>.</p>";
        } else {
            echo "<p style='color:red;'>Gagal menyimpan file.</p>";
        }
    } else {
        echo "<p style='color:red;'>Tidak ada file yang diupload.</p>";
    }
    ?>
</body>
</html>
