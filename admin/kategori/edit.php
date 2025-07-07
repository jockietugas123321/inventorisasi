<?php
session_start();
require_once __DIR__ . '/../../include/koneksi.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';
$role = $_SESSION['role'] ?? '';
$showSwal = '';
if (!cek_akses($role, 'kategori', 'update')) {
    $showSwal = "<script>Swal.fire({icon:'error',title:'Akses Ditolak',text:'Anda tidak memiliki hak untuk mengedit data ini!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
}
$kode = $_GET['kode'] ?? '';
if (!$kode) $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Kode tidak valid!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$showSwal) {
    $nama = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    if ($nama) {
        $stmt = $conn->prepare("UPDATE kategori SET nama_kategori=?, deskripsi=? WHERE kode_kategori=?");
        $stmt->bind_param('sss', $nama, $deskripsi, $kode);
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Data berhasil diupdate!',showConfirmButton:false,timer:1500}).then(()=>{window.location='index.php';});</script>";
        } else {
            $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data gagal diupdate!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data tidak lengkap!',showConfirmButton:true});</script>";
    }
}
$q = $conn->query("SELECT * FROM kategori WHERE kode_kategori='".$conn->real_escape_string($kode)."'");
$row = $q->fetch_assoc();
$title = 'Edit Kategori';
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
    <h2>Edit Kategori</h2>
    <form method="post">
      <label for="nama_kategori">Nama Kategori</label>
      <input type="text" id="nama_kategori" name="nama_kategori" value="<?= htmlspecialchars($row['nama_kategori']) ?>" required>
      <label for="deskripsi">Deskripsi</label>
      <textarea id="deskripsi" name="deskripsi"><?= htmlspecialchars($row['deskripsi']) ?></textarea>
      <div class="form-actions">
        <button type="submit" class="btn-main">Simpan</button>
        <a href="index.php" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
