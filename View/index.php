<?php include('koneksi.php'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>CRUD Tanpa Auto Increment</title>
<style>
    body { font-family: Arial, sans-serif; background: #f4f8ff; }
    table { border-collapse: collapse; width: 70%; margin: 30px auto; background: white; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
    th { background: #007bff; color: white; }
    a.button { padding: 6px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
    a.button:hover { background: #0056b3; }
    .add-btn { text-align: center; margin: 20px; }
</style>
</head>
<body>

<h2 style="text-align:center;">Daftar Pengguna</h2>

<div class="add-btn">
    <a href="tambah.php" class="button">+ Tambah Data</a>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Aksi</th>
    </tr>

    <?php
    $query = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id ASC");
    while ($data = mysqli_fetch_array($query)) {
        echo "<tr>
                <td>".$data['id']."</td>
                <td>".$data['nama']."</td>
                <td>".$data['email']."</td>
                <td>
                    <a href='edit.php?id=".$data['id']."' class='button'>Edit</a>
                    <a href='hapus.php?id=".$data['id']."' class='button' style='background:red;'>Hapus</a>
                </td>
              </tr>";
    }
    ?>
</table>

</body>
</html>
