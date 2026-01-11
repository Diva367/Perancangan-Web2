<?php
include "koneksi.php";

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM pelanggan WHERE email='$email' LIMIT 1");
    $user  = mysqli_fetch_assoc($query);

    if ($user && password_verify($pass, $user['password'])) {

        session_regenerate_id(true);
        $_SESSION['login'] = true;
        $_SESSION['user']  = $user;

        header("Location: beranda.php");
        exit;

    } else {
        echo "<script>alert('Login gagal! Email atau password salah');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Pelanggan | GOKILAU</title>

<style>
* {
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: linear-gradient(135deg, #3A7BD5, #00D2FF);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-card {
    background: #ffffff;
    width: 380px;
    padding: 32px;
    border-radius: 18px;
    box-shadow: 0 20px 45px rgba(30, 60, 114, 0.35);
    animation: fadeIn 0.8s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.brand {
    text-align: center;
    margin-bottom: 22px;
}

.brand h2 {
    color: #1E3C72;
    margin: 0;
    letter-spacing: 1px;
}

.brand p {
    color: #6b7c93;
    font-size: 14px;
    margin-top: 5px;
}

.form-group {
    margin-bottom: 16px;
    position: relative;
}

.form-group input {
    width: 100%;
    padding: 13px 42px 13px 14px;
    border-radius: 10px;
    border: 1.5px solid #c9d8f0;
    outline: none;
    font-size: 14px;
    transition: 0.3s;
}

.form-group input:focus {
    border-color: #3A7BD5;
    box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.15);
}

.toggle-password {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 17px;
    color: #3A7BD5;
}

button {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    border: none;
    color: white;
    font-weight: bold;
    border-radius: 10px;
    cursor: pointer;
    font-size: 15px;
    transition: 0.3s;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(30, 60, 114, 0.35);
}

.register-text {
    margin-top: 18px;
    text-align: center;
    font-size: 14px;
    color: #6b7c93;
}

.register-text a {
    color: #3A7BD5;
    text-decoration: none;
    font-weight: 600;
}

.register-text a:hover {
    text-decoration: underline;
}

.footer-text {
    text-align: center;
    margin-top: 18px;
    font-size: 12px;
    color: #9aa9c2;
}
</style>

</head>
<body>

<div class="login-card">

    <div class="brand">
        <h2>GOKILAU</h2>
        <p>Perawatan Kendaraan Online</p>
    </div>

    <form method="post">

        <div class="form-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="form-group">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <span class="toggle-password" onclick="togglePassword()">👁</span>
        </div>

        <button name="login">Login</button>

    </form>

    <div class="register-text">
        Belum punya akun?
        <a href="register.php">Daftar sekarang</a>
    </div>

    <div class="footer-text">
        © 2025 GOKILAU SYSTEM
    </div>

</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>