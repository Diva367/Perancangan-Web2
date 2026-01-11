<?php
session_start();
session_destroy();
header("Location: pemilik_login.php");
exit;