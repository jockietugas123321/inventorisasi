<?php
$host = 'localhost';
$user = 'root'; // Ganti jika user MySQL Anda berbeda
$pass = '';
$db   = 'inventorisasi'; // Ganti ke nama database baru

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Koneksi database gagal: ' . $conn->connect_error);
}
?>
