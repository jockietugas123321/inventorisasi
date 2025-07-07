<?php
include '../../include/cek_login.php';
require_once __DIR__ . '/../../include/koneksi.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';
$role = $_SESSION['role'] ?? '';
$showSwal = '';
if (!cek_akses($role, 'laporan', 'delete')) {
    $showSwal = "<script>Swal.fire({icon:'error',title:'Akses Ditolak',text:'Anda tidak memiliki hak untuk menghapus data ini!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
} else {
    $id = intval($_GET['id'] ?? 0);
    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM laporan WHERE id_laporan=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Data berhasil dihapus!',showConfirmButton:false,timer:1500}).then(()=>{window.location='index.php';});</script>";
        } else {
            $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data gagal dihapus!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'ID tidak valid!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
    }
}
?><!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <title>Hapus Laporan</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
<?= $showSwal ?>
</body>
</html>
