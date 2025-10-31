<?php
session_start();

// kalau belum login → arahkan ke login
if (!isset($_SESSION['user_name'])) {
    header("Location: http://localhost/user-management/auth/login.html");
    exit();
}
?>
