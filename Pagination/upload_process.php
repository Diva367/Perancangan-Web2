<?php
include 'koneksi.php';

function tampilkanPesan($jenis, $pesan) {
    $warna = $jenis == "success" ? "#ff8bb3" : "#ff4d6d";
    $emoji = $jenis == "success" ? "🌸" : "💔";
    
    echo "
    <div class='card'>
        <div class='msg $jenis' style='color: $warna;'>
            <b>$emoji $pesan</b>
        </div>
        <a href='upload_form.php' class='btn'>⬅ Kembali Upload</a>
        <a href='tampil.php' class='btn2'>🌷 Lihat Koleksi Bunga</a>
    </div>
    ";
    exit;
}

// cek apakah ada file yang diupload
if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {

    $file      = $_FILES['gambar'];
    $namaAsli  = $file['name'];
    $tmp       = $file['tmp_name'];
    $size      = $file['size'];

    // Folder penyimpanan
    $folder = "uploads/";

    // Validasi ekstensi file
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($namaAsli, PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_ext)) {
        tampilkanPesan("error", "Format file tidak diperbolehkan! Hanya JPG, JPEG, PNG, GIF.");
    }

    // Validasi ukuran file (maks 2 MB)
    if ($size > 2 * 1024 * 1024) {
        tampilkanPesan("error", "Ukuran file terlalu besar! Maksimal 2MB.");
    }

    // Validasi MIME type
    $mime = mime_content_type($tmp);
    $allowed_mime = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($mime, $allowed_mime)) {
        tampilkanPesan("error", "File bukan gambar (MIME tidak valid)!");
    }

    // buat filename unik
    $newName = time() . "_" . uniqid() . "." . $ext;

    // pindahkan file
    if (move_uploaded_file($tmp, $folder . $newName)) {

        // simpan ke database
        $sql = "INSERT INTO images (filename, original_name) VALUES ('$newName', '$namaAsli')";
        $conn->query($sql);

        tampilkanPesan("success", "Gambar berhasil diupload!");
    } else {
        tampilkanPesan("error", "Gagal memindahkan file.");
    }

} else {
    tampilkanPesan("error", "Tidak ada gambar yang diupload atau terjadi error.");
}
?>

<!-- 🌸 CSS Aesthetic Pink Soft -->
<style>
    body {
        background: linear-gradient(135deg, #ffd6e7, #ffeaf4, #ffe3f0);
        font-family: 'Poppins', sans-serif;
        text-align: center;
        padding-top: 100px;
    }
    .card {
        background: white;
        width: 50%;
        margin: auto;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        backdrop-filter: blur(3px);
    }
    .msg {
        font-size: 20px;
        margin-bottom: 25px;
    }
    .btn, .btn2 {
        display: inline-block;
        margin: 10px;
        padding: 10px 20px;
        border-radius: 20px;
        text-decoration: none;
        font-weight: bold;
        transition: .3s;
    }
    .btn {
        background: #ffb3c6;
        color: white;
    }
    .btn:hover {
        background: #d16d89;
    }
    .btn2 {
        background: #d63384;
        color: white;
    }
    .btn2:hover {
        background: #b02168;
    }
</style>