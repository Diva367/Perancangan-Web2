<?php
include 'koneksi.php';

function tampilkanPesan($jenis, $pesan) {
    $warna = $jenis == "success" ? "#ff8bb3" : "#ff4d6d";
    $emoji = $jenis == "success" ? "🌸" : "💔";
    
    echo "
    <div class='card'>
        <div class='msg' style='color: $warna;'>
            <b>$emoji $pesan</b>
        </div>
        <a href='tampil.php' class='btn'>⬅ Kembali ke Daftar Bunga</a>
    </div>
    ";
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // ambil data filename berdasarkan id
    $sql = "SELECT filename FROM images WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $filename = $data['filename'];
        $filepath = "uploads/" . $filename;

        // hapus file dari folder
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        // hapus data dari database
        $conn->query("DELETE FROM images WHERE id = $id");

        tampilkanPesan("success", "Foto bunga berhasil dihapus!");
    } else {
        tampilkanPesan("error", "Data tidak ditemukan!");
    }
} else {
    tampilkanPesan("error", "ID tidak ditemukan!");
}
?>

<!-- 🌸 CSS Aesthetic Pink -->
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
        padding: 35px;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        backdrop-filter: blur(5px);
        animation: fadeIn 0.7s ease;
    }

    .msg {
        font-size: 22px;
        margin-bottom: 25px;
    }

    .btn {
        display: inline-block;
        margin-top: 15px;
        padding: 12px 22px;
        background: #ff8bb3;
        color: white;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn:hover {
        background: #d63384;
        transform: scale(1.07);
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>