<?php
require_once __DIR__ . '/include/koneksi.php';
session_start();
include_once __DIR__ . '/include/sweetalert.php';
// Jika sudah login, langsung redirect ke admin
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header('Location: /inventorisasi/admin/index.php');
    exit;
}
// Halaman login minimalis modern dengan data dummy dan palet warna logo
$loginError = '';
$namaPegawai = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $password) {
        $sql = "SELECT * FROM pegawai WHERE username=? AND aktif=1 LIMIT 1";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            // Cek password MD5 (karena di database masih MD5)
            if ($user && md5($password) === $user['password']) {
                $namaPegawai = $user['nama_pegawai'];
                $_SESSION['nama_pegawai'] = $namaPegawai;
                $_SESSION['role'] = $user['role'];
                $_SESSION['login'] = true;
                header('Location: admin/index.php'); // gunakan path relatif agar selalu benar
                exit;
            } else {
                $loginError = 'Username atau password salah!';
            }
        } else {
            $loginError = 'Gagal query ke database.';
        }
    } else {
        $loginError = 'Username dan password wajib diisi!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventarisasi Peralatan</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #e3f0ff 0%, #fff 100%);
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 8px 32px #0E5C7130, 0 1.5px 0 #D3C28822;
            padding: 44px 32px 36px 32px;
            max-width: 370px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .login-card:before {
            content: '';
            position: absolute;
            top: -60px; left: -60px;
            width: 120px; height: 120px;
            background: linear-gradient(135deg, #D3C288 0%, #0E5C71 100%);
            opacity: 0.08;
            border-radius: 50%;
            z-index: 0;
        }
        .login-logo {
            margin-bottom: 18px;
        }
        .login-logo img {
            height: 60px;
            border-radius: 16px;
            background: #f8f8f8;
            box-shadow: 0 2px 8px #0E5C7120;
            padding: 8px 18px;
        }
        .login-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #223468;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        .login-sub {
            font-size: 1.08rem;
            color: #0E5C71;
            margin-bottom: 18px;
            font-weight: 500;
        }
        .login-card form input[type="text"],
        .login-card form input[type="password"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 14px;
            border-radius: 8px;
            border: 1px solid #e3e3e3;
            font-size: 1rem;
            background: #f7faff;
        }
        .login-card form button {
            width: 100%;
            background: #0E5C71;
            color: #fff;
            font-weight: 700;
            border: none;
            border-radius: 24px;
            padding: 12px 0;
            font-size: 1.08rem;
            box-shadow: 0 2px 8px #0E5C7120;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            margin-top: 8px;
            cursor: pointer;
        }
        .login-card form button:hover {
            background: #1976d2;
        }
        .login-link {
            display: block;
            margin-top: 18px;
            color: #0E5C71;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php if ($loginError): ?>
<script>Swal.fire({icon:'error',title:'Login Gagal',text:'<?= addslashes($loginError) ?>',showConfirmButton:true});</script>
<?php endif; ?>
<div class="login-container">
    <div class="login-card">
        <div class="login-logo">
            <img src="assets/images/logo.png" alt="Logo Inventarisasi">
        </div>
        <div class="login-title">Inventarisasi Peralatan</div>
        <div class="login-sub">Kementerian PUPR</div>
        <form method="post" autocomplete="off">
            <input type="text" name="username" placeholder="Username" autofocus required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <a href="index.php" class="login-link">&larr; Kembali ke Beranda</a>
    </div>
</div>
</body>
</html>
