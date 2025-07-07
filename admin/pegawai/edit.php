<?php
include_once __DIR__ . '/../../include/cek_login.php';
require_once __DIR__ . '/../../include/koneksi.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';

$kode = $_GET['kode'] ?? null;
$showSwal = '';
if ($kode) {
    $result = $conn->query("SELECT * FROM pegawai WHERE kode_pegawai='".$conn->real_escape_string($kode)."'");
    $row = $result->fetch_assoc();
    if (!$row) { $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data pegawai tidak ditemukan.',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>"; }
} else {
    $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Kode pegawai tidak ditemukan.',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
}
$role = $_SESSION['role'] ?? '';
if (!cek_akses($role, 'pegawai', 'update')) {
    $showSwal = "<script>Swal.fire({icon:'error',title:'Akses Ditolak',text:'Anda tidak memiliki hak untuk mengedit data ini!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$showSwal) {
    $nama = trim($_POST['nama_pegawai'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role_pegawai = trim($_POST['role'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $kontak = trim($_POST['kontak'] ?? '');
    $aktif = intval($_POST['aktif'] ?? 1);
    if ($nama && $username && $role_pegawai) {
        if ($password) {
            $password_md5 = md5($password);
            $stmt = $conn->prepare("UPDATE pegawai SET nama_pegawai=?, username=?, password=?, role=?, jabatan=?, kontak=?, aktif=? WHERE kode_pegawai=?");
            $stmt->bind_param('ssssssis', $nama, $username, $password_md5, $role_pegawai, $jabatan, $kontak, $aktif, $kode);
        } else {
            $stmt = $conn->prepare("UPDATE pegawai SET nama_pegawai=?, username=?, role=?, jabatan=?, kontak=?, aktif=? WHERE kode_pegawai=?");
            $stmt->bind_param('sssssis', $nama, $username, $role_pegawai, $jabatan, $kontak, $aktif, $kode);
        }
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Data berhasil diupdate!',showConfirmButton:false,timer:1500}).then(()=>{window.location='index.php';});</script>";
        } else {
            $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data gagal diupdate!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data tidak lengkap!',showConfirmButton:true});</script>";
    }
}
$title = 'Edit Pegawai';
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
    <h2>Edit Pegawai</h2>
    <form method="post">
      <label for="nama_pegawai">Nama Pegawai</label>
      <input type="text" id="nama_pegawai" name="nama_pegawai" value="<?= htmlspecialchars($row['nama_pegawai']) ?>" required>
      <label for="username">Username</label>
      <input type="text" id="username" name="username" value="<?= htmlspecialchars($row['username']) ?>" required>
      <label for="password">Password (isi jika ingin ganti)</label>
      <input type="password" id="password" name="password">
      <label for="role">Role</label>
      <select id="role" name="role" required>
        <option value="">Pilih Role</option>
        <option value="admin" <?= $row['role']==='admin'?'selected':'' ?>>Admin</option>
        <option value="satker" <?= $row['role']==='satker'?'selected':'' ?>>Satker</option>
        <option value="kepala_bagian" <?= $row['role']==='kepala_bagian'?'selected':'' ?>>Kepala Bagian</option>
      </select>
      <label for="jabatan">Jabatan</label>
      <input type="text" id="jabatan" name="jabatan" value="<?= htmlspecialchars($row['jabatan']) ?>">
      <label for="kontak">Kontak</label>
      <input type="text" id="kontak" name="kontak" value="<?= htmlspecialchars($row['kontak']) ?>">
      <label for="aktif">Aktif</label>
      <select id="aktif" name="aktif">
        <option value="1" <?= $row['aktif']==1?'selected':'' ?>>Aktif</option>
        <option value="0" <?= $row['aktif']==0?'selected':'' ?>>Nonaktif</option>
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