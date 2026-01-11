<?php
include "koneksi.php";

$error = '';

if (isset($_POST['login'])) {

    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    $q = mysqli_query($conn, "
        SELECT * FROM pemilik
        WHERE username='$user'
        LIMIT 1
    ");

    if (mysqli_num_rows($q) === 1) {
        $data = mysqli_fetch_assoc($q);

        if (password_verify($pass, $data['password'])) {
            $_SESSION['pemilik_login'] = true;
            $_SESSION['pemilik'] = $data;

            header("Location: pemilik_dashboard.php");
            exit;
        }
    }

    $error = "Username atau password salah!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Pemilik | GOKILAU</title>

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
    align-items: center;
    justify-content: center;
}

/* CARD */
.login-card {
    background: #fff;
    padding: 35px 32px;
    width: 100%;
    max-width: 380px;
    border-radius: 18px;
    box-shadow: 0 20px 45px rgba(0,0,0,0.25);
    text-align: center;
}

/* TITLE */
.login-card h2 {
    margin: 0;
    color: #1E3C72;
}

.login-card p {
    font-size: 14px;
    color: #6b7c93;
    margin: 8px 0 24px;
}

/* FORM */
.form-group {
    text-align: left;
    margin-bottom: 16px;
}

.form-group label {
    font-size: 13px;
    font-weight: 600;
    color: #1E3C72;
    display: block;
    margin-bottom: 6px;
}

.form-group input {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #c9d8f0;
    font-size: 14px;
    outline: none;
    transition: 0.2s;
}

.form-group input:focus {
    border-color: #3A7BD5;
    box-shadow: 0 0 0 2px rgba(58,123,213,0.15);
}

/* ERROR */
.error {
    background: #ffecec;
    color: #c0392b;
    padding: 10px;
    border-radius: 10px;
    font-size: 13px;
    margin-bottom: 16px;
}

/* BUTTON */
.btn-login {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: none;
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    color: white;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.btn-login:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* FOOTER */
.login-footer {
    margin-top: 18px;
    font-size: 12px;
    color: #9aa9c2;
}

.password-box {
    position: relative;
}

.password-box input {
    padding-right: 44px;
}

.toggle-eye {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    cursor: pointer;
    color: #3A7BD5;
    user-select: none;
    line-height: 1;
}

.toggle-eye:hover {
    opacity: 0.7;
}
</style>
</head>

<body>

<div class="login-card">

    <h2>🔑 Login Pemilik</h2>
    <p>Akses khusus pemilik GOKILAU</p>

    <?php if ($error) { ?>
        <div class="error"><?= $error ?></div>
    <?php } ?>

    <form method="post">

        <div class="form-group">
            <label>Username</label>
            <input type="text"
                   name="username"
                   placeholder="Masukkan username"
                   required>
        </div>

        <div class="form-group"> 
        <label>Password</label> 
        <div class="password-box">
    <input type="password"
           name="password"
           id="password"
           placeholder="Masukkan password"
           required>
    <span class="toggle-eye" onclick="togglePassword()">👁</span>
</div>
        </div>

        <button class="btn-login" name="login">
            🔓 Masuk
        </button>

    </form>

    <div class="login-footer">
        © 2025 GOKILAU SYSTEM
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