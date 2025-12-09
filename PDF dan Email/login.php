<?php
include 'koneksi.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check = $conn->query("SELECT * FROM users WHERE username='$username'");

    if ($check->num_rows > 0) {
        $data = $check->fetch_assoc();

        if (password_verify($password, $data['password'])) {
            $_SESSION['user'] = $username;
            header("Location: tampil.php");
            exit;
        } else {
            $message = "<div class='error'>💔 Password salah!</div>";
        }
    } else {
        $message = "<div class='error'>💔 Username tidak ditemukan!</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login 🌸</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Sacramento&display=swap" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #ffd6e7, #ffeaf4, #ffe3f0);
    font-family: 'Poppins';
    text-align: center;
    padding-top: 80px;
}
.card {
    width: 40%;
    margin: auto;
    background: #ffffffcc;
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
}
input {
    width: 80%;
    padding: 10px;
    margin-top: 10px;
    border-radius: 10px;
    border: 1px solid #ff9ec7;
}
button {
    padding: 10px 20px;
    margin-top: 20px;
    background: #ff8bb3;
    border: none;
    color: white;
    border-radius: 20px;
    font-weight: bold;
    cursor: pointer;
}
button:hover {
    background: #d63384;
}
.error {
    margin-bottom: 15px;
    padding: 12px;
    border-radius: 10px;
    background: #ffd6d6;
    color: #b00020;
    font-weight: bold;
}
a {
    color: #d63384;
    text-decoration: none;
    font-weight: bold;
}
</style>
</head>

<body>
<h1 style="font-family:Sacramento;font-size:50px;color:#d63384;">Login Dulu Beb 🌸</h1>

<div class="card">
    <?= $message ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Username kamu" required><br>
        <input type="password" name="password" placeholder="Password kamu" required><br>
        <button type="submit">Masuk 💗</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar dulu beb 💗</a></p>
</div>

</body>
</html>