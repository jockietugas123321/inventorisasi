<?php
include '../../include/cek_login.php';
require_once __DIR__ . '/../../include/koneksi.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';

$role = $_SESSION['role'] ?? '';
$showSwal = '';

if (!cek_akses($role, 'kategori', 'delete')) {
    $showSwal = "<script>Swal.fire({icon:'error',title:'Akses Ditolak',text:'Anda tidak memiliki hak untuk menghapus data ini!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
} else {
    $kode = $_GET['kode'] ?? '';
    if ($kode) {
        $stmt = $conn->prepare("DELETE FROM kategori WHERE kode_kategori=?");
        $stmt->bind_param("s", $kode);
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Data berhasil dihapus!',showConfirmButton:false,timer:1500}).then(()=>{window.location='index.php';});</script>";
        } else {
            $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data gagal dihapus!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Kode tidak valid!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
    }
}
?><!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <title>Hapus Kategori</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
<?= $showSwal ?>
</body>
</html>
