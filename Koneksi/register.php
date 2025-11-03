<?php
include 'config.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Username sudah digunakan!');</script>";
    } else {
        $query = mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
        if ($query) {
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='index.php';</script>";
        } else {
            echo "<script>alert('Registrasi gagal!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Registrasi</title>
<style>
body { font-family: Arial; background: #e0f0ff; text-align: center; padding: 50px; }
form { background: white; display: inline-block; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
input { margin: 10px; padding: 10px; width: 80%; }
button { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
button:hover { background: #0056b3; }
</style>
</head>
<body>
<h2>Form Registrasi</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" name="register">Daftar</button><br><br>
    <a href="index.php">Sudah punya akun? Login</a>
</form>
</body>
</html>
