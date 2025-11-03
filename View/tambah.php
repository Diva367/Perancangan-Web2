<?php include('koneksi.php'); ?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Data</title>
</head>
<body>
<h2>Tambah Data Pengguna</h2>
<form method="POST" action="">
    Nama: <input type="text" name="nama" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    <input type="submit" name="submit" value="Simpan">
    <a href="index.php">Kembali</a>
</form>

<?php
if (isset($_POST['submit'])) {
    $nama  = $_POST['nama'];
    $email = $_POST['email'];

    // Cari ID terkecil yang kosong
    $result = mysqli_query($koneksi, "SELECT id FROM users ORDER BY id ASC");
    $next_id = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['id'] == $next_id) {
            $next_id++;
        } else {
            break; // ketemu celah kosong
        }
    }

    $query = mysqli_query($koneksi, "INSERT INTO users (id, nama, email) VALUES ('$next_id', '$nama', '$email')");

    if ($query) {
        echo "<script>alert('Data berhasil disimpan!');window.location='index.php';</script>";
    } else {
        echo "Gagal menambah data: " . mysqli_error($koneksi);
    }
}
?>
</body>
</html>
