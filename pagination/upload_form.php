<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Foto Bunga 🌸</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Sacramento&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #ffd6e7, #ffeaf4, #ffe3f0);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding-top: 70px;
        }

        h2 {
            text-align: center;
            font-family: 'Sacramento', cursive;
            font-size: 55px;
            color: #d63384;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .subtext {
            text-align: center;
            margin-top: -10px;
            margin-bottom: 35px;
            font-size: 17px;
            color: #a62d68;
        }

        .card {
            width: 55%;
            background: #ffffffcc;
            margin: auto;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.1);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.8s ease;
        }

        label {
            font-weight: 600;
            color: #a62d68;
            font-size: 16px;
        }

        input[type="file"] {
            display: block;
            margin-top: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn {
            display: inline-block;
            background: #ff8bb3;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            font-size: 15px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn:hover {
            background: #d63384;
            transform: scale(1.07);
        }

        .btn-back {
            display: inline-block;
            background: #d63384;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            text-decoration: none;
            margin-top: 20px;
            font-size: 14px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #a61e63;
            transform: scale(1.05);
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
    </style>
</head>

<body>

<h2>Upload Foto Bunga 🌸</h2>
<div class="subtext">Tambahkan foto bunga cantik ke koleksimu 💗🌷</div>

<div class="card">

    <form action="upload_process.php" method="POST" enctype="multipart/form-data">
        <label>Pilih Foto Bunga :</label>
        <input type="file" name="gambar" required>

        <button type="submit" class="btn">🌸 Upload Sekarang</button>
    </form>

    <br>
    <a href="tampil.php" class="btn-back">⬅ Kembali ke Daftar Foto</a>
</div>

</body>
</html>