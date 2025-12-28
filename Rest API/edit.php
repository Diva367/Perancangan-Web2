<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$data = $conn->query("SELECT * FROM images WHERE id='$id'")->fetch_assoc();

if (!$data) {
    echo "Data tidak ditemukan 🌸";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $conn->real_escape_string($_POST['nama']);
    $foto_lama = $data['filename'];

    // Cek apakah upload foto baru
    if (!empty($_FILES['foto']['name'])) {

        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nama_baru = uniqid().'_bunga.'.$ext;
        $path = "uploads/".$nama_baru;

        // pindahkan file
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $path)) {

            // hapus foto lama
            if (file_exists("uploads/".$foto_lama)) {
                unlink("uploads/".$foto_lama);
            }

            // update nama + foto
            $conn->query("
                UPDATE images 
                SET original_name='$nama', filename='$nama_baru' 
                WHERE id='$id'
            ");

        }
    } else {
        // update nama saja
        $conn->query("
            UPDATE images 
            SET original_name='$nama' 
            WHERE id='$id'
        ");
    }

    $_SESSION['success'] = "🌸 Perubahan berhasil disimpan";
    header("Location: tampil.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Bunga 🌸</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Sacramento&display=swap" rel="stylesheet">

<style>
body{
    background:linear-gradient(135deg,#ffd6e7,#ffeaf4,#ffe3f0);
    font-family:'Poppins',sans-serif;
    padding-top:80px;
    text-align:center;
}

.card{
    width:420px;
    margin:auto;
    background:#ffffffcc;
    padding:30px;
    border-radius:22px;
    box-shadow:0 4px 18px rgba(0,0,0,.12);
}

h1{
    font-family:'Sacramento',cursive;
    font-size:50px;
    color:#d63384;
    margin-bottom:10px;
}

img{
    width:120px;
    border-radius:15px;
    margin:15px 0;
    border:3px solid #ffb3d9;
}

input{
    width:100%;
    padding:12px;
    border-radius:14px;
    border:1px solid #ff9ec7;
    font-size:14px;
}

/* BUTTON GROUP */
.btn-group{
    display:flex;
    gap:14px;
    margin-top:25px;
}

.btn-save,
.btn-cancel{
    flex:1;
    height:48px;
    box-sizing:border-box;
    border-radius:30px;
    font-weight:600;
    font-size:14px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:6px;
}

/* SIMPAN */
.btn-save{
    background:linear-gradient(135deg,#ff7eb3,#ff4d88);
    color:white;
    border:none;
    cursor:pointer;
}

.btn-save:hover{
    opacity:.9;
}

/* BATAL */
.btn-cancel{
    background:#fff;
    color:#d63384;
    border:1.5px solid #ff9ec7;
    text-decoration:none;
}

.btn-cancel:hover{
    background:#fff5fb;
}

.upload-label{
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:8px;
    margin-top:20px;
    cursor:pointer;
}

.upload-label input{
    display:none;
}

.upload-label span{
    font-size:13px;
    color:#d63384;
    font-weight:600;
}

#preview{
    width:140px;
    height:140px;
    object-fit:cover;
    border-radius:18px;
    border:3px dashed #ff9ec7;
    padding:5px;
    transition:.3s;
}

.upload-label:hover #preview{
    transform:scale(1.05);
}
</style>
</head>

<body>

<div class="card">
    <h1>Edit Bunga 🌸</h1>

    <img src="uploads/<?= htmlspecialchars($data['filename']) ?>">

    <form method="POST" enctype="multipart/form-data">

    <input type="text" name="nama" 
           value="<?= $data['original_name']; ?>" required>

    <!-- PREVIEW FOTO -->
    <label class="upload-label">
        <img src="uploads/<?= $data['filename']; ?>" id="preview">
        <span>Ganti Foto 🌸</span>
        <input type="file" name="foto" accept="image/*" 
               onchange="previewImage(this)">
    </label>

    <div class="btn-group">
        <button class="btn-save">💾 Simpan</button>
        <a href="tampil.php" class="btn-cancel">↩ Batal</a>
    </div>
</form>
</div>
</body>
<script>
function previewImage(input){
    if(input.files && input.files[0]){
        const reader = new FileReader();
        reader.onload = function(e){
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</html>