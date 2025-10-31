<?php
include "../config/db.php";

$success = false;
$status = "";
$link = "login.html"; // arah ke halaman login kalau sukses

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Cek apakah email sudah ada
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $status = "Email sudah terdaftar. Silakan gunakan email lain.";
        $success = false;
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, status, created_at) VALUES (?, ?, ?, 'aktif', NOW())");

        if ($stmt) {
            $stmt->bind_param("sss", $name, $email, $password);
            if ($stmt->execute()) {
                $status = "Registrasi berhasil! Silakan login untuk melanjutkan.";
                $success = true;
            } else {
                $status = "Terjadi kesalahan: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $status = "Gagal memproses data: " . $conn->error;
        }
    }
    $check->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Status Registrasi</title>
<link rel="stylesheet" href="../assets/css/styles.css">
<style>
/* ============================
   STATUS BOX iOS STYLE
============================ */
body {
  background: var(--bg-body, #f5f5f7);
  font-family: 'SF Pro Text', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.form-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

.status-box {
  width: 360px;
  background: #fff;
  border-radius: 20px;
  padding: 36px 28px;
  box-shadow: 0 16px 40px rgba(0, 0, 0, 0.08);
  text-align: center;
  animation: fadeInUp 0.6s ease forwards;
  opacity: 0;
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(25px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Icon animasi iOS-style */
.status-icon {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  margin: 0 auto 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 32px;
  font-weight: bold;
  animation: popIn 0.6s ease forwards;
  transform: scale(0.5);
  opacity: 0;
}

@keyframes popIn {
  0% { transform: scale(0.5); opacity: 0; }
  60% { transform: scale(1.2); opacity: 1; }
  100% { transform: scale(1); opacity: 1; }
}

.status-icon.success {
  background: #e6f7ef;
  color: #28a745;
}

.status-icon.error {
  background: #ffebe6;
  color: #c53030;
}

.status-box h2 {
  font-size: 22px;
  color: <?= $success ? '#28a745' : '#c53030' ?>;
  margin-bottom: 10px;
  font-weight: 600;
}

.status-box p {
  color: #444;
  font-size: 15px;
  margin-bottom: 24px;
  line-height: 1.6;
}

.status-box a {
  display: inline-block;
  text-decoration: none;
  background: var(--primary, #007aff);
  color: #fff;
  padding: 10px 18px;
  border-radius: 12px;
  font-weight: 600;
  transition: background 0.3s, transform 0.2s;
}

.status-box a:hover {
  background: var(--primary-dark, #0056b3);
  transform: translateY(-2px);
}
</style>
</head>
<body>
<div class="form-wrapper">
  <div class="status-box">
    <div class="status-icon <?= $success ? 'success' : 'error' ?>">
      <?= $success ? '✅' : '❌' ?>
    </div>
    <h2><?= $success ? "Berhasil!" : "Gagal!" ?></h2>
    <p><?= htmlspecialchars($status) ?></p>
    <?php if ($success): ?>
      <a href="<?= $link ?>">➡️  Ke Halaman Login</a>
    <?php else: ?>
      <a href="register.html">🔁 Coba Lagi</a>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
