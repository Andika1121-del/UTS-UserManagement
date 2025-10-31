<?php
include 'session_check.php';
include '../config/db.php';

$id = $_GET['id'];
$uid = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM products WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $uid);
$stmt->execute();

header("Location: products.php");
exit;
?>
