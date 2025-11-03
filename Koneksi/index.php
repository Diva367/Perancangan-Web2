<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['username'] = $username;
        header("Location: home.php");
        exit;
    } else {
        echo "<script>alert('Username atau password salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login</title>
<style>
body { font-family: Arial; background: #d1f7d1; text-align: center; padding: 50px; }
form { background: white; display: inline-block; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px #ccc; }
input { margin: 10px; padding: 10px; width: 80%; }
button { padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
button:hover { background: #1e7e34; }
</style>
</head>
<body>
<h2>Login ke Akun</h2>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit" name="login">Login</button><br><br>
    <a href="register.php">Belum punya akun? Daftar</a>
</form>
</body>
</html>
