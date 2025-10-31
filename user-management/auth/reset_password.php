<?php
include '../config/db.php';

$status = "";
$success = false;
$showForm = false;

// Ambil token dari URL atau POST
$token = $_GET['token'] ?? $_POST['token'] ?? '';

if (!empty($token)) {

    // Jika form dikirim (POST)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];

        if ($password !== $confirm) {
            $status = "❌ Password dan konfirmasi tidak sama!";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET password=?, reset_token=NULL WHERE reset_token=?");
            $stmt->bind_param("ss", $hash, $token);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $status = "✅ Password berhasil diubah! Silakan <a href='login.html'>login</a>.";
                $success = true;
            } else {
                $status = "⚠️ Token tidak valid atau sudah digunakan!";
            }
        }

    } else {
        // Cek token valid di database
        $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $showForm = true;
        } else {
            $status = "⚠️ Token tidak valid atau sudah kadaluarsa!";
        }
    }

} else {
    $status = "❗ Token tidak ditemukan di URL!";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
<link rel="stylesheet" href="../assets/style.css"> <!-- pastikan namanya style.css -->
<style>
body {
  background: linear-gradient(135deg, #6a11cb, #2575fc);
  font-family: 'Poppins', sans-serif;
  margin: 0;
  padding: 0;
}
.form-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}
.form-box {
  background: #ffffff;
  width: 380px;
  padding: 35px 25px;
  border-radius: 20px;
  box-shadow: 0 10px 35px rgba(0,0,0,0.2);
  text-align: center;
  animation: fadeIn 0.6s ease;
}
@keyframes fadeIn {
  from {opacity: 0; transform: translateY(20px);}
  to {opacity: 1; transform: translateY(0);}
}
.form-box h2 {
  font-size: 26px;
  margin-bottom: 25px;
  color: #333;
}
input[type="password"] {
  width: 100%;
  padding: 12px 15px;
  margin-bottom: 15px;
  border: 1px solid #ddd;
  border-radius: 10px;
  font-size: 14px;
  outline: none;
  transition: 0.3s;
}
input[type="password"]:focus {
  border-color: #6a11cb;
  box-shadow: 0 0 5px rgba(106,17,203,0.3);
}
button {
  background: #6a11cb;
  color: #fff;
  border: none;
  padding: 12px 25px;
  font-size: 15px;
  border-radius: 10px;
  cursor: pointer;
  transition: 0.3s ease;
  width: 100%;
}
button:hover {
  background: #2575fc;
}
.status {
  font-size: 15px;
  margin-bottom: 18px;
  color: <?= $success ? '#28a745' : '#dc3545' ?>;
  background: <?= $success ? '#e9f9ee' : '#fdeaea' ?>;
  padding: 10px;
  border-radius: 8px;
}
a {
  color: #6a11cb;
  text-decoration: none;
  font-weight: 500;
}
a:hover { text-decoration: underline; }
</style>
</head>
<body>
<div class="form-wrapper">
  <div class="form-box">
    <h2>Reset Password</h2>
    <?php if(!empty($status)): ?>
      <div class="status"><?= $status ?></div>
    <?php endif; ?>

    <?php if($showForm): ?>
    <form method="POST" action="">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <input type="password" name="password" placeholder="Password baru" required>
      <input type="password" name="confirm" placeholder="Konfirmasi password" required>
      <button type="submit">Ubah Password</button>
    </form>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
