<?php
session_start();
require_once __DIR__ . '/../../include/koneksi.php';
include '../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';
$role = $_SESSION['role'] ?? '';
$showSwal = '';
if (!cek_akses($role, 'barang', 'create')) {
    $showSwal = "<script>Swal.fire({icon:'error',title:'Akses Ditolak',text:'Anda tidak memiliki hak untuk menambah data ini!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$showSwal) {
    $kode = trim($_POST['kode_barang'] ?? '');
    $uraian = trim($_POST['uraian_barang'] ?? '');
    $id_kategori = intval($_POST['id_kategori'] ?? 0);
    $tahun = trim($_POST['tahun_pengadaan'] ?? '');
    $lokasi = trim($_POST['lokasi'] ?? '');
    $kondisi = trim($_POST['kondisi'] ?? '');
    $nilai = floatval($_POST['nilai'] ?? 0);
    $nup = trim($_POST['nup'] ?? '');
    $spesifikasi = trim($_POST['spesifikasi'] ?? '');
    if ($kode && $uraian && $id_kategori) {
        $stmt = $conn->prepare("INSERT INTO barang (kode_barang, uraian_barang, id_kategori, tahun_pengadaan, lokasi, kondisi, nilai, nup, spesifikasi) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssisssdss', $kode, $uraian, $id_kategori, $tahun, $lokasi, $kondisi, $nilai, $nup, $spesifikasi);
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Data berhasil ditambahkan!',showConfirmButton:false,timer:1500}).then(()=>{window.location='index.php';});</script>";
        } else {
            $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data gagal ditambahkan!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data tidak lengkap!',showConfirmButton:true});</script>";
    }
}
$kategori = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori");
$title = 'Tambah Barang';
header('Location: index.php');
exit;
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
    <h2>Tambah Barang</h2>
    <form method="post">
      <label for="kode_barang">Kode Barang</label>
      <input type="text" id="kode_barang" name="kode_barang" required>
      <label for="uraian_barang">Uraian Barang</label>
      <input type="text" id="uraian_barang" name="uraian_barang" required>
      <label for="id_kategori">Kategori</label>
      <select id="id_kategori" name="id_kategori" required>
        <option value="">Pilih Kategori</option>
        <?php while($k = $kategori->fetch_assoc()): ?>
        <option value="<?= $k['id_kategori'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
        <?php endwhile; ?>
      </select>
      <label for="tahun_pengadaan">Tahun Pengadaan</label>
      <input type="text" id="tahun_pengadaan" name="tahun_pengadaan">
      <label for="lokasi">Lokasi</label>
      <input type="text" id="lokasi" name="lokasi">
      <label for="kondisi">Kondisi</label>
      <input type="text" id="kondisi" name="kondisi">
      <label for="nilai">Nilai</label>
      <input type="number" id="nilai" name="nilai" step="0.01">
      <label for="nup">NUP</label>
      <input type="text" id="nup" name="nup">
      <label for="spesifikasi">Spesifikasi</label>
      <textarea id="spesifikasi" name="spesifikasi"></textarea>
      <div class="form-actions">
        <button type="submit" class="btn-main">Simpan</button>
        <a href="index.php" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
