<?php
include_once __DIR__ . '/../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';
require_once __DIR__ . '/../../include/koneksi.php';
$kode = $_GET['kode'] ?? '';
if (!$kode) {
    echo "<script>Swal.fire('Gagal', 'Kode tidak valid!', 'error').then(()=>window.location='mutasi.php');</script>";
    exit;
}
$stmt = $conn->prepare("DELETE FROM mutasi WHERE kode_mutasi=?");
$stmt->bind_param('s', $kode);
if ($stmt->execute()) {
    echo "<script>Swal.fire('Berhasil', 'Data berhasil dihapus!', 'success').then(()=>window.location='mutasi.php');</script>";
} else {
    echo "<script>Swal.fire('Gagal', 'Data gagal dihapus!', 'error').then(()=>window.location='mutasi.php');</script>";
}
exit;
