<?php
session_start();
unset($_SESSION['admin_login']);
unset($_SESSION['admin']);
session_destroy();
header("Location: admin_login.php");