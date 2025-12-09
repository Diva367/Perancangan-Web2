<?php
include 'koneksi.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // cek username sudah ada atau belum
    $check = $conn->query("SELECT * FROM users WHERE username='$username'");

    if ($check->num_rows > 0) {
        $message = "<div class='error'>💔 Username sudah dipakai!</div>";
    } else {
        $conn->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
        $message = "<div class='success'>🌸 Akun berhasil dibuat! Silakan login.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register 🌸</title>
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
.success, .error {
    margin-bottom: 15px;
    padding: 12px;
    border-radius: 10px;
    font-weight: bold;
}
.success {
    background: #ffdaf0;
    color: #c21875;
}
.error {
    background: #ffd6d6;
    color: #b00020;
}
a {
    color: #d63384;
    text-decoration: none;
    font-weight: bold;
}
</style>
</head>

<body>
<h1 style="font-family:Sacramento;font-size:50px;color:#d63384;">Register Akun 🌸</h1>

<div class="card">
    <?= $message ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Buat username" required><br>
        <input type="password" name="password" placeholder="Buat password" required><br>
        <button type="submit">Daftar 💗</button>
    </form>

    <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>

</body>
</html>