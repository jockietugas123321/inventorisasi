<?php
include '../../include/cek_login.php';
require_once __DIR__ . '/../../include/koneksi.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';
$role = $_SESSION['role'] ?? '';
$result = null;
if (!cek_akses($role, 'barang', 'delete')) {
    $result = 'denied';
} else {
    $kode = $_GET['kode'] ?? '';
    if ($kode) {
        $stmt = $conn->prepare("DELETE FROM barang WHERE kode_barang=?");
        $stmt->bind_param("s", $kode);
        if ($stmt->execute()) {
            $result = 'success';
        } else {
            $result = 'fail';
        }
    } else {
        $result = 'fail';
    }
}
?><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Barang</title>
    <?php include_once __DIR__ . '/../../include/sweetalert.php'; ?>
</head>
<body>
<script>
<?php if ($result === 'denied'): ?>
Swal.fire({icon:'error',title:'Akses Ditolak',text:'Anda tidak memiliki hak untuk menghapus data ini!',showConfirmButton:true}).then(()=>{window.location='index.php';});
<?php elseif ($result === 'success'): ?>
Swal.fire({icon:'success',title:'Berhasil',text:'Data berhasil dihapus!',showConfirmButton:false,timer:1500}).then(()=>{window.location='index.php';});
<?php else: ?>
Swal.fire({icon:'error',title:'Gagal',text:'Data gagal dihapus!',showConfirmButton:true}).then(()=>{window.location='index.php';});
<?php endif; ?>
</script>
</body>
</html>
