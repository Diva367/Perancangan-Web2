<?php
include "koneksi.php";

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = $_POST['password'];

    $q = mysqli_query($conn, "SELECT * FROM admin WHERE email='$email' LIMIT 1");
    $admin = mysqli_fetch_assoc($q);

    if ($admin && password_verify($pass, $admin['password'])) {

        session_regenerate_id(true);
        $_SESSION['admin_login'] = true;
        $_SESSION['admin'] = $admin;

        header("Location: admin_dashboard.php");
        exit;

    } else {
        echo "<script>alert('Login admin gagal!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Login | GOKILAU</title>

<style>
* {
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.admin-card {
    background: #ffffff;
    width: 380px;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.45);
    animation: slideIn 0.8s ease;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(25px); }
    to { opacity: 1; transform: translateY(0); }
}

.admin-header {
    text-align: center;
    margin-bottom: 25px;
}

.admin-header h2 {
    color: #1E3C72;
    margin: 0;
    letter-spacing: 1px;
}

.admin-header p {
    font-size: 13px;
    color: #6b7c93;
    margin-top: 6px;
}

/* INPUT */
.form-group {
    margin-bottom: 16px;
    position: relative;
}

.form-group input {
    width: 100%;
    padding: 13px 45px 13px 13px;
    border-radius: 10px;
    border: 1.5px solid #c9d8f0;
    font-size: 14px;
    transition: 0.3s;
}

.form-group input:focus {
    border-color: #1E3C72;
    box-shadow: 0 0 0 3px rgba(30,60,114,0.15);
    outline: none;
}

.toggle-password {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 17px;
    color: #1E3C72;
    user-select: none;
}

/* BUTTON */
button {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, #1E3C72, #3A7BD5);
    border: none;
    color: white;
    font-weight: bold;
    border-radius: 12px;
    cursor: pointer;
    font-size: 15px;
    transition: 0.3s;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(30,60,114,0.45);
}

.admin-footer {
    margin-top: 18px;
    text-align: center;
    font-size: 12px;
    color: #9aa9c2;
}
</style>

</head>
<body>

<div class="admin-card">

    <div class="admin-header">
        <h2>ADMIN PANEL</h2>
        <p>Akses terbatas untuk pengelola bengkel</p>
    </div>

    <form method="post">

        <div class="form-group">
            <input type="email" name="email" placeholder="Email Admin" required>
        </div>

        <div class="form-group">
            <input type="password" name="password" id="adminPassword" placeholder="Password" required>
            <span class="toggle-password" onclick="toggleAdminPassword()">👁</span>
        </div>

        <button name="login">Masuk Admin</button>

    </form>

    <div class="admin-footer">
        © 2025 GOKILAU – Admin System
    </div>

</div>

<script>
function toggleAdminPassword() {
    const pass = document.getElementById("adminPassword");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>