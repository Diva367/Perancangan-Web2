<?php
include 'koneksi.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // login pakai EMAIL
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($check->num_rows > 0) {
        $data = $check->fetch_assoc();

        if (password_verify($password, $data['password'])) {
            $_SESSION['email'] = $data['email'];
            $_SESSION['user']  = $data['username']; // buat ditampilkan di tampil.php
            header("Location: tampil.php");
            exit;
        } else {
            $message = "<div class='alert error'>💔 Password salah!</div>";
        }
    } else {
        $message = "<div class='alert error'>💔 Email tidak terdaftar!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login 🌸</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Sacramento&display=swap" rel="stylesheet">

<style>
body{
    background:linear-gradient(135deg,#ffd6e7,#ffeaf4,#ffe3f0);
    font-family:'Poppins',sans-serif;
    margin:0;
    padding-top:80px;
    text-align:center;
}

h1{
    font-family:'Sacramento',cursive;
    font-size:55px;
    color:#d63384;
    margin-bottom:25px;
}

.card{
    width:420px;
    margin:auto;
    background:#ffffffcc;
    padding:30px;
    border-radius:22px;
    box-shadow:0 4px 18px rgba(0,0,0,.12);
}

input{
    width:100%;
    padding:12px;
    margin-top:12px;
    border-radius:14px;
    border:1px solid #ff9ec7;
    font-size:14px;
}

.password-wrapper{
    position:relative;
}

.toggle-password{
    position:absolute;
    right:14px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:14px;
    color:#d63384;
    user-select:none;
}

button{
    width:100%;
    padding:12px;
    margin-top:22px;
    background:#ff8bb3;
    border:none;
    color:white;
    border-radius:20px;
    font-weight:600;
    font-size:15px;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#d63384;
    transform:scale(1.03);
}

.alert{
    padding:14px;
    border-radius:14px;
    margin-bottom:18px;
    font-weight:600;
}

.alert.error{
    background:#ffd6d6;
    color:#b00020;
}

p{
    margin-top:20px;
}

a{
    color:#d63384;
    font-weight:600;
    text-decoration:none;
}
</style>
</head>

<body>

<h1>Login Dulu Beb 🌸</h1>

<div class="card">

    <?= $message ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email kamu" required>

        <div class="password-wrapper">
            <input type="password" name="password" id="password" placeholder="Password kamu" required>
            <span class="toggle-password" onclick="togglePassword()">👁️</span>
        </div>

        <button type="submit">Masuk 💗</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar dulu beb 💗</a></p>

</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>