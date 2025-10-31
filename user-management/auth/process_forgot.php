<?php
include '../config/db.php';

$status = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $status = "❌ Password dan konfirmasi tidak sama!";
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $query = "UPDATE users SET password=? WHERE email=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $hash, $email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $status = "✅ Password berhasil diubah! Silakan login kembali.";
            $success = true;
        } else {
            $status = "⚠️ Email tidak ditemukan dalam database!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Status Reset Password</title>
<link rel="stylesheet" href="../assets/style.css">
<style>
/* 🌈 Style Modern Reset Password */
body {
  font-family: 'SF Pro Text', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  background: linear-gradient(135deg, #74b9ff, #a29bfe);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0;
}

.form-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
}

.status-box {
  background: #fff;
  width: 360px;
  padding: 30px;
  border-radius: 20px;
  box-shadow: 0 15px 40px rgba(0,0,0,0.15);
  text-align: center;
  animation: fadeIn 0.5s ease;
}

.status-icon {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 0 auto 20px auto;
  font-size: 30px;
  color: #fff;
}

.status-icon.success {
  background: #28a745;
  box-shadow: 0 0 20px rgba(40, 167, 69, 0.3);
}

.status-icon.error {
  background: #dc3545;
  box-shadow: 0 0 20px rgba(220, 53, 69, 0.3);
}

h2 {
  font-size: 24px;
  color: #333;
  margin-bottom: 10px;
}

p {
  font-size: 16px;
  color: #555;
  margin-bottom: 20px;
}

.links-vertical {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.links-vertical a {
  color: #007bff;
  text-decoration: none;
  font-weight: 500;
}

.links-vertical a:hover {
  color: #0056b3;
  text-decoration: underline;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(15px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="form-wrapper">
    <div class="status-box">
      <div class="status-icon <?= $success ? 'success' : 'error' ?>">
        <i class="<?= $success ? 'fas fa-check' : 'fas fa-xmark' ?>"></i>
      </div>
      <h2><?= $success ? "Sukses!" : "Gagal!" ?></h2>
      <p><?= htmlspecialchars($status) ?></p>

      <div class="links-vertical">
        <a href="login.html">🔑 Kembali ke Login</a>
      </div>
    </div>
  </div>
</body>
</html>
