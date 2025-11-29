<?php
include 'koneksi.php';

// Ambil semua data gambar
$result = $conn->query("SELECT * FROM images ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🌸 Data Bunga Aesthetic 🌸</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Sacramento&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #ffd6e7, #ffeaf4, #ffe3f0);
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding-bottom: 50px;
        }

        /* Header Title */
        h2 {
            text-align: center;
            margin-top: 40px;
            font-family: 'Sacramento', cursive;
            font-size: 60px;
            color: #d63384;
            letter-spacing: 2px;
            text-shadow: 2px 3px rgba(255, 182, 193, 0.6);
        }

        .subtext {
            text-align: center;
            margin-top: -20px;
            margin-bottom: 30px;
            font-size: 16px;
            color: #a62d68;
        }

        .btn-add {
            display: inline-block;
            background: #ff8bb3;
            color: white;
            padding: 10px 22px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            margin-bottom: 20px;
        }

        .btn-add:hover {
            background: #d63384;
            transform: scale(1.07);
        }

        /* Table Card */
        .table-wrapper {
            width: 85%;
            margin: auto;
            background: #ffffffcc;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            padding: 25px;
            backdrop-filter: blur(5px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 20px;
            overflow: hidden;
        }

        th {
            background: #ffb3d9;
            padding: 14px;
            font-size: 16px;
            color: #660033;
        }

        td {
            padding: 12px;
            text-align: center;
            color: #4a4a4a;
            border-bottom: 1px solid #fce3ef;
        }

        tr:hover {
            background: #fff5fb;
        }

        img {
            width: 100px;
            height: 120px;
            object-fit: cover;
            border-radius: 15px;
            border: 3px solid #ffb3d9;
            box-shadow: 0 3px 5px rgba(255, 192, 203, 0.6);
            transition: 0.3s;
        }

        img:hover {
            transform: scale(1.05);
        }

        .delete-btn {
            color: white;
            background: #ff4d6d;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 14px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }

        .delete-btn:hover {
            background: #b3002d;
            transform: scale(1.08);
        }
    </style>
</head>

<body>

<h2>Data Bunga 🌸</h2>
<div class="subtext">Koleksi foto bunga yang cantik & aesthetic dari kamu 💗</div>

<center>
    <a href="upload_form.php" class="btn-add">+ Tambah Data Bunga</a>
</center>

<div class="table-wrapper">
    <table>
        <tr>
            <th>NO</th>
            <th>NAMA</th>
            <th>FOTO</th>
            <th>DELETE</th>
        </tr>

        <?php 
        if ($result->num_rows > 0) {
            $no = 1;
            while ($row = $result->fetch_assoc()) :
        ?>

        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['original_name']; ?></td>
            <td>
                <img src="uploads/<?= $row['filename']; ?>" alt="foto">
            </td>
            <td>
                <a class="delete-btn" 
                href="delete.php?id=<?= $row['id']; ?>"
                onclick="return confirm('Yakin mau hapus bunga ini beb? 🌸');">
                    Hapus
                </a>
            </td>
        </tr>

        <?php endwhile; } else { ?>

        <tr>
            <td colspan="4">Belum ada data bunga 🌸</td>
        </tr>

        <?php } ?>

    </table>
</div>

</body>
</html>