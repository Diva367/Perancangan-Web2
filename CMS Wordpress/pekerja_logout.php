<?php
session_start();

// hapus semua session
session_destroy();

// kembali ke halaman login pekerja
header("Location: pekerja_login.php");
exit;