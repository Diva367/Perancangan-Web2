<?php
session_start();
include "db.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $cek = mysqli_query($koneksi, "SELECT * FROM login WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $data = mysqli_fetch_assoc($cek);
        if (password_verify($password, $data['password'])) {
            $_SESSION['username'] = $data['username'];
            header("Location: home.php");
            exit;
        } else {
            $message = "<p class='error'>Password salah!</p>";
        }
    } else {
        $message = "<p class='error'>Username tidak ditemukan!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Proses Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h3>Proses Login</h3>
    <?php if (isset($message)) echo $message; ?>
    <form method="post" action="">
        <label>Username :</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password :</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" name="login" value="Login">
    </form>
    <br>
    <a href="register.php">Belum punya akun? Daftar di sini</a>
</body>
</html>
