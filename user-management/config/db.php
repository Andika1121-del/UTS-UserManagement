<?php
$host = 'localhost';
$user = 'root';
$pass = '';            // kalau pakai Laragon default kosong
$db   = 'user_management';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>