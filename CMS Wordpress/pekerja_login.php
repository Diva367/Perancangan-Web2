<?php
include "koneksi.php"; // pastikan path benar

$error = '';

if (isset($_POST['login_pekerja'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $q = mysqli_query($conn, "
        SELECT * FROM pekerja 
        WHERE username = '$username'
        LIMIT 1
    ");

    $data = mysqli_fetch_assoc($q);

    if ($data && password_verify($password, $data['password'])) {
        $_SESSION['pekerja_login'] = true;
        $_SESSION['pekerja'] = $data;

        header("Location: pekerja_dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login Pekerja | GOKILAU</title>

<style>
* {
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    margin: 0;
    min-height: 100vh;
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-box {
    background: #fff;
    width: 360px;
    padding: 30px 28px;
    border-radius: 18px;
    box-shadow: 0 20px 45px rgba(0,0,0,0.25);
    text-align: center;
}

.login-box h2 {
    margin: 0 0 6px;
    color: #1E3C72;
}

.login-box p {
    font-size: 13px;
    color: #6b7c93;
    margin-bottom: 20px;
}

.login-box input {
    width: 100%;
    padding: 12px 14px;
    margin-bottom: 14px;
    border-radius: 10px;
    border: 1px solid #c9d8f0;
    font-size: 14px;
    outline: none;
}

.login-box input:focus {
    border-color: #3A7BD5;
    box-shadow: 0 0 0 2px rgba(58,123,213,0.15);
}

.login-box button {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    border: none;
    color: white;
    font-size: 15px;
    font-weight: 600;
    border-radius: 12px;
    cursor: pointer;
    transition: 0.25s;
}

.login-box button:hover {
    opacity: 0.92;
    transform: translateY(-1px);
}

.error {
    background: #ffebee;
    color: #b71c1c;
    padding: 10px;
    border-radius: 10px;
    font-size: 13px;
    margin-bottom: 14px;
}

.footer {
    margin-top: 18px;
    font-size: 12px;
    color: #9aa9c2;
}

.password-box {
    position: relative;
    display: flex;
    align-items: center;
}

.toggle-eye {
    position: absolute;
    right: 14px;
    cursor: pointer;
    font-size: 18px;
    color: #3A7BD5;
}

.toggle-eye:hover {
    opacity: 0.7;
}
</style>
</head>

<body>

<div class="login-box">

    <h2>👷 Login Pekerja</h2>
    <p>Sistem Bengkel Digital GOKILAU</p>

    <?php if ($error) { ?>
        <div class="error"><?= $error ?></div>
    <?php } ?>

    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <div class="password-box">
    <input type="password" name="password" id="password" placeholder="Password" required>
    <span class="toggle-eye" onclick="togglePassword()">👁</span>
</div>

        <button type="submit" name="login_pekerja">
            🔐 Login
        </button>
    </form>

    <div class="footer">
        © 2025 GOKILAU
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const eye = document.querySelector('.toggle-eye');

    if (input.type === 'password') {
        input.type = 'text';
        eye.textContent = '👁';
    } else {
        input.type = 'password';
        eye.textContent = '👁';
    }
}
</script>
</body>
</html>