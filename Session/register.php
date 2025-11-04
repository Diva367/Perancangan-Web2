<?php
session_start();
include "db.php";

if (isset($_POST['signup'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $retype   = $_POST['retype'];
    $gender   = $_POST['gender'];

    if ($password !== $retype) {
        $message = "<p class='error'>Password tidak cocok!</p>";
    } else {
        $cek = mysqli_query($koneksi, "SELECT * FROM login WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            $message = "<p class='error'>Username sudah digunakan!</p>";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO login (username, password, gender) VALUES ('$username', '$hashed', '$gender')";
            if (mysqli_query($koneksi, $query)) {
                $message = "<p class='success'>User berhasil terdaftar</p>";
            } else {
                $message = "<p class='error'>Gagal mendaftar!</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h3>==============================================</h3>
    <h3>Pendaftaran User</h3>
    <?php if (isset($message)) echo $message; ?>
    <form method="post" action="">
        <label>Username :</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password :</label><br>
        <input type="password" name="password" required><br><br>

        <label>Re-type Password :</label><br>
        <input type="password" name="retype" required><br><br>

        <label>Gender :</label><br>
        <input type="radio" name="gender" value="Laki-laki" required> Laki-laki
        <input type="radio" name="gender" value="Perempuan" required> Perempuan
        <br><br>

        <input type="submit" name="signup" value="Sign Up">
    </form>
    <br>
    <a href="login.php">Sudah punya akun? Login di sini</a>
</body>
</html>
