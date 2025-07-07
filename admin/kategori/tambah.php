<?php
include_once __DIR__ . '/../../include/cek_login.php';
require_once __DIR__ . '/../../include/koneksi.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';

$role = $_SESSION['role'] ?? '';
$showSwal = '';
if (!cek_akses($role, 'kategori', 'create')) {
    $showSwal = "<script>Swal.fire({icon:'error',title:'Akses Ditolak',text:'Anda tidak memiliki hak untuk menambah data ini!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = trim($_POST['kode_kategori'] ?? '');
    $nama = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    if ($kode && $nama) {
        $stmt = $conn->prepare("INSERT INTO kategori (kode_kategori, nama_kategori, deskripsi) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $kode, $nama, $deskripsi);
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Data berhasil ditambahkan!',showConfirmButton:false,timer:1500}).then(()=>{window.location='index.php';});</script>";
        } else {
            $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data gagal ditambahkan!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data tidak lengkap!',showConfirmButton:true});</script>";
    }
}
$title = 'Tambah Kategori';
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $title ?></title>
<link rel="stylesheet" href="../../style.css">
<style>
  html, body { height: 100%; margin: 0; padding: 0; }
  body { min-height: 100vh; display: flex; align-items: flex-start; justify-content: center; background: linear-gradient(135deg,#e3f0ff 0%,#f7fbff 100%); }
  .form-container { width: 100%; display: flex; align-items: flex-start; justify-content: center; min-height: 100vh; padding-top: 48px; padding-bottom: 0; }
  .form-box { margin: 0; padding-bottom: 48px; }
  @media (max-width:600px) { .form-container { padding-top: 18px; } .form-box { padding-bottom: 18px; } }
  .btn-main, .btn-cancel, button { border: none !important; text-decoration: none !important; }
</style>
</head>
<body>
<?= $showSwal ?>
<div class="form-container">
  <div class="form-box">
    <h2>Tambah Kategori</h2>
    <form method="post">
      <label for="kode_kategori">Kode Kategori</label>
      <input type="text" id="kode_kategori" name="kode_kategori" required>
      <label for="nama_kategori">Nama Kategori</label>
      <input type="text" id="nama_kategori" name="nama_kategori" required>
      <label for="deskripsi">Deskripsi</label>
      <textarea id="deskripsi" name="deskripsi"></textarea>
      <div class="form-actions">
        <button type="submit" class="btn-main">Simpan</button>
        <a href="index.php" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
