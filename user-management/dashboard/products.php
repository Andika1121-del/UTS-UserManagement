<?php
include 'session_check.php';
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM products WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Produk</title>
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
  max-width: 1100px;
  margin: 40px auto;
  background: #fff;
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 8px 30px rgba(0,0,0,0.1);
}

h2 {
  color: #333;
  text-align: center;
  margin-bottom: 25px;
}

.top-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.top-bar a {
  background: linear-gradient(135deg, #2575fc, #6a11cb);
  color: #fff;
  padding: 10px 18px;
  border-radius: 8px;
  text-decoration: none;
  transition: 0.3s ease;
}

.top-bar a:hover {
  background: linear-gradient(135deg, #6a11cb, #2575fc);
}

table {
  width: 100%;
  border-collapse: collapse;
  font-size: 15px;
  margin-top: 10px;
}

th, td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: center;
}

th {
  background: #2575fc;
  color: white;
}

tr:nth-child(even) {
  background: #f2f6ff;
}

tr:hover {
  background: #e8f0ff;
}

.actions a {
  text-decoration: none;
  margin: 0 5px;
  font-weight: 500;
}

.actions a.edit {
  color: #28a745;
}

.actions a.delete {
  color: #dc3545;
}

.actions a:hover {
  text-decoration: underline;
}

/* ===== Modal Konfirmasi Hapus ===== */
.modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.4);
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: #fff;
  padding: 25px;
  border-radius: 14px;
  text-align: center;
  width: 90%;
  max-width: 380px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.2);
  animation: fadeIn 0.25s ease;
}

.modal-content i {
  font-size: 40px;
  color: #dc3545;
  margin-bottom: 10px;
}

.modal h3 {
  margin-top: 0;
  color: #222;
}

.modal p {
  color: #555;
  font-size: 14px;
  margin-bottom: 20px;
}

.modal-buttons {
  display: flex;
  justify-content: space-between;
}

.modal-buttons button {
  flex: 1;
  margin: 0 5px;
  padding: 10px;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s;
}

.modal-buttons .delete-btn {
  background: #dc3545;
  color: #fff;
}

.modal-buttons .delete-btn:hover {
  background: #b02a37;
}

.modal-buttons button:not(.delete-btn) {
  background: #e9ecef;
}

.modal-buttons button:not(.delete-btn):hover {
  background: #d6d8db;
}

@keyframes fadeIn {
  from { transform: scale(0.9); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
</style>
</head>
<body>
  <div class="container">
    <div class="top-bar">
      <h2>📦 Manajemen Produk</h2>
      <div>
        <a href="add_product.php"><i class="fa-solid fa-plus"></i> Tambah Produk</a>
        <a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a>
        <a href="../auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      </div>
    </div>

    <table>
      <tr>
        <th>ID</th>
        <th>Nama Produk</th>
        <th>Deskripsi</th>
        <th>Stok</th>
        <th>Harga</th>
        <th>Aksi</th>
      </tr>
      <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
        <td><?= $row['stok'] ?></td>
        <td>Rp<?= number_format($row['harga'], 2, ',', '.') ?></td>
        <td class="actions">
          <a href="edit_product.php?id=<?= $row['id'] ?>" class="edit"><i class="fa-solid fa-pen-to-square"></i> Edit</a> |
          <a href="#" class="delete" onclick="confirmDelete(<?= $row['id'] ?>)">
            <i class="fa-solid fa-trash"></i> Hapus
          </a>
        </td>
      </tr>
      <?php endwhile; ?>
    </table>
  </div>

  <!-- Modal Konfirmasi Hapus -->
  <div id="deleteModal" class="modal">
      <div class="modal-content">
          <i class="fa-solid fa-triangle-exclamation"></i>
          <h3>Hapus Produk?</h3>
          <p>Apakah kamu yakin ingin menghapus produk ini?</p>
          <div class="modal-buttons">
              <button onclick="closeModal()">Batal</button>
              <button id="confirmBtn" class="delete-btn">Hapus</button>
          </div>
      </div>
  </div>

  <script>
  let productToDelete = null;

  function confirmDelete(id) {
      productToDelete = id;
      document.getElementById('deleteModal').style.display = 'flex';
  }

  function closeModal() {
      document.getElementById('deleteModal').style.display = 'none';
      productToDelete = null;
  }

  document.getElementById('confirmBtn').addEventListener('click', () => {
      if (productToDelete) {
          window.location.href = `delete_product.php?id=${productToDelete}`;
      }
  });

  // Tutup modal kalau klik di luar
  window.onclick = function(event) {
      const modal = document.getElementById('deleteModal');
      if (event.target === modal) {
          closeModal();
      }
  }
  </script>
</body>
</html>
