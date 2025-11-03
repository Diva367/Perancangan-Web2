<?php include('koneksi.php');
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id'");
$data = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Data</title>
</head>
<body>
<h2>Edit Data Pengguna</h2>
<form method="POST" action="">
    Nama: <input type="text" name="nama" value="<?php echo $data['nama']; ?>" required><br><br>
    Email: <input type="email" name="email" value="<?php echo $data['email']; ?>" required><br><br>
    <input type="submit" name="update" value="Update">
    <a href="index.php">Kembali</a>
</form>

<?php
if (isset($_POST['update'])) {
    $nama  = $_POST['nama'];
    $email = $_POST['email'];

    $update = mysqli_query($koneksi, "UPDATE users SET nama='$nama', email='$email' WHERE id='$id'");

    if ($update) {
        echo "<script>alert('Data berhasil diupdate!');window.location='index.php';</script>";
    } else {
        echo "Gagal mengedit data: " . mysqli_error($koneksi);
    }
}
?>
</body>
</html>
