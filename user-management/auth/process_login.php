<?php
session_start();

$success = false;
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "../config/db.php";

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            $success = true;
            $status = "Login berhasil! Selamat datang, " . htmlspecialchars($user['name']);
        } else {
            $status = "Password salah!";
        }
    } else {
        $status = "Email tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Proses Login</title>
<link rel="stylesheet" href="../assets/style.css">
<style>
body {
  background: linear-gradient(145deg, #f2f4f8, #e6ebf2);
  font-family: 'SF Pro Text', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

/* Box status utama */
.status-box {
  background: #fff;
  width: 360px;
  padding: 35px 28px;
  border-radius: 20px;
  box-shadow: 0 20px 45px rgba(0,0,0,0.12);
  text-align: center;
  animation: fadeIn 0.6s ease;
  position: relative;
  overflow: hidden;
}

/* Efek gradasi lembut iOS */
.status-box::before {
  content: '';
  position: absolute;
  top: -60%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle at 30% 30%, rgba(0,122,255,0.15), transparent 60%);
  z-index: 0;
  opacity: 0.5;
}

.status-box h2 {
  font-size: 26px;
  font-weight: 600;
  color: <?= $success ? '#28a745' : '#dc3545' ?>;
  margin-bottom: 15px;
  position: relative;
  z-index: 1;
}

.status-box p {
  font-size: 15px;
  margin-bottom: 20px;
  color: #444;
  position: relative;
  z-index: 1;
}

.status-box a {
  text-decoration: none;
  color: #007aff;
  font-weight: 500;
  position: relative;
  z-index: 1;
}

.status-box a:hover {
  color: #0056b3;
}

/* Countdown modern */
.countdown {
  font-size: 42px;
  font-weight: 700;
  color: #007aff;
  text-shadow: 0 0 12px rgba(0,122,255,0.4);
  margin-top: 10px;
  animation: pulse 1s ease-in-out infinite;
  position: relative;
  z-index: 1;
}

/* Animasi fade-in awal box */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Efek angka countdown */
@keyframes pulse {
  0% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.4); opacity: 0.8; }
  100% { transform: scale(1); opacity: 1; }
}

/* Animasi glow biru terakhir */
@keyframes glowOut {
  from { box-shadow: 0 0 0 rgba(0,122,255,0); }
  to { box-shadow: 0 0 40px rgba(0,122,255,0.6); }
}

/* Keterangan kecil */
.redirect-note {
  font-size: 13px;
  color: #666;
  margin-top: 5px;
}
</style>
</head>
<body>
<div class="status-box" id="box">
  <h2><?= $success ? "Sukses!" : "Gagal!" ?></h2>
  <p><?= htmlspecialchars($status) ?></p>

  <?php if ($success): ?>
    <p class="redirect-note">Anda akan diarahkan ke dashboard dalam</p>
    <div id="countdown" class="countdown">3</div>
  <?php else: ?>
    <a href="login.html">🔙 Kembali ke Login</a>
  <?php endif; ?>
</div>

<?php if ($success): ?>
<script>
let timeLeft = 3;
const countdown = document.getElementById('countdown');
const box = document.getElementById('box');

const timer = setInterval(() => {
  timeLeft--;
  countdown.textContent = timeLeft;

  // Efek tambahan: pas 1, munculkan glow
  if (timeLeft === 1) {
    box.style.animation = "glowOut 1s ease-in-out forwards";
  }

  if (timeLeft <= 0) {
    clearInterval(timer);
    countdown.style.opacity = 0;
    setTimeout(() => {
      window.location.href = "../dashboard/index.php";
    }, 500);
  }
}, 1000);
</script>
<?php endif; ?>
</body>
</html>
