<?php
include "koneksi.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// SESUAIKAN PATH:
// - jika register.php sejajar dengan folder vendor → "vendor/autoload.php"
// - jika di folder auth/ → "../vendor/autoload.php"
require "vendor/autoload.php";

if (isset($_POST['register'])) {
    $username = htmlspecialchars($_POST['username']);
    $email    = htmlspecialchars($_POST['email']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $password = $_POST['password'];

    // cek email sudah terdaftar
    $cek = mysqli_query($conn, "SELECT * FROM pelanggan WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Email sudah terdaftar');</script>";
    } else {

        $hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($conn, "INSERT INTO pelanggan VALUES ('','$username','$email','$hash', '$$no_telp')");

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'xxxxxxxxx@gmail.com';      // ganti
            $mail->Password   = 'xxxxxxxxxxxxxxxxxxx';        // ganti
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('xxxxxxxxx@gmail.com', 'GOKILAU SYSTEM');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Registrasi Akun GOKILAU';
            $mail->Body = "
            <div style='font-family:Segoe UI'>
                <h2 style='color:#1E3C72'>Registrasi Berhasil</h2>
                <p>Halo <b>$username</b>, akun kamu berhasil dibuat.</p>
                <hr>
                <p><b>Email:</b> $email</p>
                <p><b>Username:</b> $username</p>
                <p><b>No Telp:</b> $no_telp</p>
                <p><b>Password:</b> $password</p>
                <br>
                <small>Silakan login dan lakukan pemesanan perawatan kendaraan.</small>
            </div>";

            $mail->send();
            echo "<script>alert('Registrasi berhasil! Cek email kamu');window.location='login.php';</script>";

        } catch (Exception $e) {
            echo "<script>alert('Email gagal dikirim');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register Pelanggan | GOKILAU</title>

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

.register-card {
    background: #ffffff;
    width: 400px;
    padding: 32px;
    border-radius: 18px;
    box-shadow: 0 20px 45px rgba(30, 60, 114, 0.35);
    animation: fadeIn 0.8s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.brand {
    text-align: center;
    margin-bottom: 22px;
}

.brand h2 {
    color: #1E3C72;
    margin: 0;
}

.brand p {
    color: #6b7c93;
    font-size: 14px;
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
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(30, 60, 114, 0.35);
}

.login-text {
    margin-top: 18px;
    text-align: center;
    font-size: 14px;
    color: #6b7c93;
}

.login-text a {
    color: #3A7BD5;
    text-decoration: none;
    font-weight: 600;
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

<div class="register-card">

    <div class="brand">
        <h2>GOKILAU</h2>
        <p>Registrasi Pelanggan</p>
    </div>

    <form method="post">

        <div class="form-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>

        <div class="form-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>
        
        <div class="form-group">
        <input type="tel" name="no_telp" placeholder="No. Telepon (WhatsApp)"required>
        </div>

        <div class="form-group">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <span class="toggle-password" onclick="togglePassword()">👁</span>
        </div>

        <button name="register">Daftar</button>

    </form>

    <div class="login-text">
        Sudah punya akun?
        <a href="login.php">Login sekarang</a>
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