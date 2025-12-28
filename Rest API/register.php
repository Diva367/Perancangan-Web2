<?php
require 'vendor/autoload.php';
include 'koneksi.php';

use PHPMailer\PHPMailer\PHPMailer;

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email    = $_POST['email'];
    $username = $_POST['username'];
    $password_plain = $_POST['password'];
    $password_hash  = password_hash($password_plain, PASSWORD_DEFAULT);

    // cek email
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($check->num_rows > 0) {
        $message = "<div class='alert error'>💔 Email sudah terdaftar!</div>";
    } else {

        // simpan ke database
        $conn->query("INSERT INTO users (email, username, password)
                      VALUES ('$email', '$username', '$password_hash')");

        // kirim email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'xxxxxx@gmail.com';   // GANTI
            $mail->Password   = 'xxxxxxxxxxxxxxxx';     // GANTI
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            $mail->setFrom('oktad9126@gmail.com', 'Sistem Bunga Aesthetic');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = '[REGISTER] Akun Sistem Bunga 🌸';
            $mail->Body = "
                <div style='font-family:Poppins,sans-serif'>
                    <h2 style='color:#d63384;'>🌸 Registrasi Berhasil</h2>

                    <p>Halo <b>$username</b>,</p>
                    <p>Akun kamu berhasil dibuat.</p>

                    <hr>

                    <h3>📌 Informasi Akun</h3>
                    <ul>
                        <li><b>Email:</b> $email</li>
                        <li><b>Username:</b> $username</li>
                        <li><b>Password:</b> <b>$password_plain</b></li>
                    </ul>

                    <p style='color:#b00020;font-size:13px'>
                        ⚠️ Simpan informasi ini dengan baik.<br>
                        Password hanya dikirim sekali melalui email.
                    </p>

                    <hr>
                    <small>Sistem Bunga Aesthetic 🌸</small>
                </div>
            ";

            $mail->send();

            // 🔥 PESAN HALAMAN (TANPA TAMPIL USERNAME & PASSWORD)
            $message = "
            <div class='alert success'>
                🌸 Akun berhasil dibuat!<br>
                <small>Silakan cek email kamu untuk melihat username & password 💌</small>
            </div>";

        } catch (Exception $e) {
            $message = "
            <div class='alert success'>
                🌸 Akun berhasil dibuat!<br>
                <small>Email gagal dikirim, silakan hubungi admin.</small>
            </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Register 🌸</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Sacramento&display=swap" rel="stylesheet">

<style>
body{
    background:linear-gradient(135deg,#ffd6e7,#ffeaf4,#ffe3f0);
    font-family:'Poppins',sans-serif;
    padding-top:80px;
}
h1{
    font-family:'Sacramento',cursive;
    font-size:55px;
    color:#d63384;
    text-align:center;
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
}
button{
    width:100%;
    padding:12px;
    margin-top:20px;
    background:#ff8bb3;
    border:none;
    color:white;
    border-radius:20px;
    font-weight:600;
    cursor:pointer;
}
button:hover{ background:#d63384; }
.alert{
    padding:14px;
    border-radius:14px;
    margin-bottom:18px;
    font-weight:600;
    text-align:center;
}
.alert.success{ background:#ffdaf0; color:#c21875; }
.alert.error{ background:#ffd6d6; color:#b00020; }
p{text-align:center;}
a{color:#d63384;font-weight:600;text-decoration:none;}
</style>
</head>

<body>

<h1>Register Akun 🌸</h1>

<div class="card">

<?= $message ?>

<form method="POST">
    <input type="email" name="email" placeholder="Email aktif" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Daftar 💗</button>
</form>

<p>Sudah punya akun? <a href="login.php">Login di sini</a></p>

</div>

</body>
</html>