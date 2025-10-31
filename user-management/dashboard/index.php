<?php
include '../auth/session_check.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin Gudang</title>
<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* ====== DASHBOARD STYLES ====== */
.dashboard-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  background: linear-gradient(135deg, var(--bg-a), var(--bg-b));
  text-align: center;
  color: #111827;
}

.dashboard-card {
  background: var(--card-bg);
  border-radius: 18px;
  box-shadow: 0 14px 40px rgba(6, 24, 44, 0.14);
  padding: 32px 40px;
  width: 360px;
  transition: transform .2s ease, box-shadow .2s ease;
}

.dashboard-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 20px 50px rgba(6,24,44,0.18);
}

.dashboard-card h2 {
  font-size: 22px;
  font-weight: 600;
  margin-bottom: 12px;
  color: #111827;
}

.dashboard-card p {
  font-size: 15px;
  color: var(--muted);
  margin-bottom: 20px;
}

.dashboard-links {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.dashboard-links a {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: var(--primary);
  color: #fff;
  text-decoration: none;
  padding: 10px 16px;
  border-radius: 12px;
  font-size: 15px;
  transition: background .2s, transform .1s;
}

.dashboard-links a:hover {
  background: var(--primary-dark);
  transform: translateY(-2px);
}

.logout {
  background: #EF4444;
}

.logout:hover {
  background: #B91C1C;
}
</style>
</head>
<body>
  <div class="dashboard-container">
    <div class="dashboard-card">
      <h2>Selamat datang 👋</h2>
      <p><strong><?= htmlspecialchars($_SESSION['user_name']); ?></strong><br>Anda berhasil login sebagai admin gudang.</p>

      <div class="dashboard-links">
        <a href="products.php"><i class="fa-solid fa-box"></i> Kelola Produk</a>
        <a href="../auth/logout.php" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      </div>
    </div>
  </div>
</body>
</html>
