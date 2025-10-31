<?php
include '../config/db.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $stmt = $conn->prepare("UPDATE users SET status='ACTIVE', activation_code=NULL WHERE activation_code=?");
    $stmt->bind_param("s", $code);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Akun berhasil diaktivasi! <a href='login.html'>Login sekarang</a>";
    } else {
        echo "Tautan aktivasi tidak valid!";
    }
} else {
    echo "Kode aktivasi tidak ditemukan!";
}
?>
