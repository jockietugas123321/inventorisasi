<?php
include_once __DIR__ . '/../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
require_once __DIR__ . '/../../include/koneksi.php';
include_once __DIR__ . '/../../include/sweetalert.php';

$role = $_SESSION['role'] ?? '';
$showSwal = '';
if (!cek_akses($role, 'pegawai', 'create')) {
    $showSwal = "<script>Swal.fire({icon:'error',title:'Akses Ditolak',text:'Anda tidak memiliki hak untuk menambah data ini!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_pegawai = trim($_POST['kode_pegawai'] ?? '');
    $nama = trim($_POST['nama_pegawai'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role_pegawai = trim($_POST['role'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $kontak = trim($_POST['kontak'] ?? '');
    $aktif = intval($_POST['aktif'] ?? 1);
    if ($kode_pegawai && $nama && $username && $password && $role_pegawai) {
        $password_md5 = md5($password);
        $stmt = $conn->prepare("INSERT INTO pegawai (kode_pegawai, nama_pegawai, username, password, role, jabatan, kontak, aktif) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssssi', $kode_pegawai, $nama, $username, $password_md5, $role_pegawai, $jabatan, $kontak, $aktif);
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Data berhasil ditambahkan!',showConfirmButton:false,timer:1500}).then(()=>{window.location='index.php';});</script>";
        } else {
            $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data gagal ditambahkan!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data tidak lengkap!',showConfirmButton:true});</script>";
    }
}
$title = 'Tambah Pegawai';
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
</style>
</head>
<body>
<?= $showSwal ?>
<div class="form-container">
  <div class="form-box">
    <h2>Tambah Pegawai</h2>
    <form method="post">
      <label for="kode_pegawai">Kode Pegawai</label>
      <input type="text" id="kode_pegawai" name="kode_pegawai" required>
      <label for="nama_pegawai">Nama Pegawai</label>
      <input type="text" id="nama_pegawai" name="nama_pegawai" required>
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>
      <label for="role">Role</label>
      <select id="role" name="role" required>
        <option value="">Pilih Role</option>
        <option value="admin">Admin</option>
        <option value="satker">Satker</option>
        <option value="kepala_bagian">Kepala Bagian</option>
      </select>
      <label for="jabatan">Jabatan</label>
      <input type="text" id="jabatan" name="jabatan">
      <label for="kontak">Kontak</label>
      <input type="text" id="kontak" name="kontak">
      <label for="aktif">Aktif</label>
      <select id="aktif" name="aktif">
        <option value="1">Aktif</option>
        <option value="0">Nonaktif</option>
      </select>
      <div class="form-actions">
        <button type="submit" class="btn-main">Simpan</button>
        <a href="index.php" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>