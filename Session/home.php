<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// === Hitung pengunjung ===
$filename = "counter.txt";
if (!file_exists($filename)) {
    file_put_contents($filename, 0);
}
$counter = (int)file_get_contents($filename);
$counter++;
file_put_contents($filename, $counter);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* ===== Tambahan gaya khusus halaman home ===== */
        .home-container {
            background: #fff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.1);
            max-width: 450px;
            margin: 50px auto;
            text-align: center;
            animation: fadeIn 0.8s ease;
        }

        .home-container h2 {
            color: #0077cc;
            margin-bottom: 10px;
        }

        .home-container p {
            font-size: 1.1rem;
            color: #333;
            margin: 10px 0 25px 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            margin: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-main {
            background-color: #0077cc;
            color: white;
        }

        .btn-main:hover {
            background-color: #005fa3;
        }

        .btn-logout {
            background-color: #ff4d4d;
            color: white;
        }

        .btn-logout:hover {
            background-color: #d93636;
        }

        .counter-box {
            margin-top: 30px;
            background-color: #f7fbff;
            border: 1px solid #d0e7ff;
            border-radius: 10px;
            padding: 15px;
            font-weight: 500;
            color: #004b8d;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="home-container">
        <h2>Selamat Datang 👋</h2>
        <p>Login anda : <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></p>
        
        <div>
            <a href="home.php" class="btn btn-main">🏠 Halaman Utama</a>
            <a href="logout.php" class="btn btn-logout">🚪 Logout</a>
        </div>

        <div class="counter-box">
            Total pengunjung yang telah mengakses web ini:  
            <b><?php echo $counter; ?></b>
        </div>
    </div>

</body>
</html>
