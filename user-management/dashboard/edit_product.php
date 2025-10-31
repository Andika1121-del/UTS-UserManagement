<?php
include 'session_check.php';
include '../config/db.php';

$id = $_GET['id'];
$uid = $_SESSION['user_id'];

// Saat form disubmit (update data)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_produk'];
    $desc = $_POST['deskripsi'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];

    $stmt = $conn->prepare("UPDATE products 
                            SET nama_produk=?, deskripsi=?, stok=?, harga=?, updated_at=NOW() 
                            WHERE id=? AND user_id=?");
    $stmt->bind_param("ssdiis", $nama, $desc, $stok, $harga, $id, $uid);
    $stmt->execute();

    header("Location: products.php");
    exit;
}

// Ambil data produk untuk diedit
$stmt = $conn->prepare("SELECT * FROM products WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $uid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "<p>Produk tidak ditemukan atau tidak punya akses.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Produk</title>
<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body {
  font-family: "Poppins", sans-serif;
  background: #f5f7fa;
  margin: 0;
  padding: 0;
}

.container {
  max-width: 600px;
  margin: 50px auto;
  background: #fff;
  padding: 30px 40px;
  border-radius: 16px;
  box-shadow: 0 8px 30px rgba(0,0,0,0.1);
}

h2 {
  text-align: center;
  color: #333;
  margin-bottom: 25px;
}

label {
  display: block;
  margin-top: 15px;
  font-weight: 600;
  color: #444;
}

input, textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 8px;
  margin-top: 5px;
  font-size: 15px;
  transition: 0.2s;
}

input:focus, textarea:focus {
  border-color: #2575fc;
  outline: none;
  box-shadow: 0 0 4px rgba(37,117,252,0.3);
}

button {
  width: 100%;
  background: linear-gradient(135deg, #1507aaff, #0a4fb0ff);
  color: white;
  border: none;
  padding: 12px;
  font-size: 16px;
  border-radius: 10px;
  cursor: pointer;
  margin-top: 20px;
  transition: 0.3s ease;
}

button:hover {
  background: linear-gradient(135deg, #0e47f3ff, #0b0664ff);
}

.back-link {
  display: block;
  text-align: center;
  margin-top: 15px;
  color: #2575fc;
  text-decoration: none;
  font-weight: 500;
}

.back-link:hover {
  text-decoration: underline;
}
</style>
</head>
<body>
  <div class="container">
    <h2><i class="fa-solid fa-pen-to-square"></i> Edit Produk</h2>
    <form method="POST">
      <label>Nama Produk:</label>
      <input type="text" name="nama_produk" value="<?= htmlspecialchars($row['nama_produk']) ?>" required>

      <label>Deskripsi:</label>
      <textarea name="deskripsi" rows="4"><?= htmlspecialchars($row['deskripsi']) ?></textarea>

      <label>Stok:</label>
      <input type="number" name="stok" value="<?= htmlspecialchars($row['stok']) ?>" required>

      <label>Harga (Rp):</label>
      <input type="number" step="0.01" name="harga" value="<?= htmlspecialchars($row['harga']) ?>" required>

      <button type="submit"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
    </form>
    <a href="products.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Produk</a>
  </div>
</body>
</html>
