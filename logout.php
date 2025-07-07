<?php
session_start();
// Hapus semua session
session_unset();
session_destroy();
// Hapus cookie PHPSESSID agar browser benar-benar menghapus session
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
// Redirect ke halaman login
header('Location: /inventorisasi/login.php');
exit;
