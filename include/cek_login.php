<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: /inventorisasi/login.php');
    exit;
}
?>
