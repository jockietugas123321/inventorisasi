<?php
session_start();
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';
$role = $_SESSION['role'] ?? '';
if (!cek_akses($role, 'barang', 'update')) {
    echo "<script>Swal.fire({icon:'error',title:'Akses Ditolak',text:'Anda tidak memiliki hak untuk mengedit data ini!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
    exit;
}
require_once __DIR__ . '/../../include/koneksi.php';
$kode = $_GET['kode'] ?? '';
if (!$kode) die('Kode barang tidak valid');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_barang = trim($_POST['kode_barang'] ?? '');
    $nama_barang = trim($_POST['nama_barang'] ?? '');
    $detail_barang = trim($_POST['detail_barang'] ?? '');
    $lokasi_penyimpanan = trim($_POST['lokasi_penyimpanan'] ?? '');
    $kode_kategori = trim($_POST['kode_kategori'] ?? '');
    $kondisi = trim($_POST['kondisi'] ?? 'baik');
    $nilai_awal = floatval($_POST['nilai_awal'] ?? 0);
    $tahun_perolehan = trim($_POST['tahun_perolehan'] ?? '');
    $status = trim($_POST['status'] ?? 'aktif');
    $dokumentasi = trim($_POST['dokumentasi'] ?? '');
    if ($kode_barang && $nama_barang && $kode_kategori) {
        $stmt = $conn->prepare("UPDATE barang SET nama_barang=?, detail_barang=?, lokasi_penyimpanan=?, kode_kategori=?, kondisi=?, nilai_awal=?, tahun_perolehan=?, status=?, dokumentasi=? WHERE kode_barang=?");
        $stmt->bind_param('sssssdssss', $nama_barang, $detail_barang, $lokasi_penyimpanan, $kode_kategori, $kondisi, $nilai_awal, $tahun_perolehan, $status, $dokumentasi, $kode_barang);
        if ($stmt->execute()) {
            echo "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Data berhasil diupdate!',showConfirmButton:false,timer:1500}).then(()=>{window.location='index.php';});</script>";
            exit;
        } else {
            echo "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data gagal diupdate!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
            exit;
        }
    } else {
        echo "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data tidak lengkap!',showConfirmButton:true});</script>";
    }
}
$q = $conn->query("SELECT * FROM barang WHERE kode_barang='".$conn->real_escape_string($kode)."'");
$row = $q->fetch_assoc();
$kategori = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori");
$title = 'Edit Barang';
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
<div class="form-container">
  <div class="form-box">
    <h2>Edit Barang</h2>
    <form method="post">
      <label for="kode_barang">Kode Barang</label>
      <input type="text" id="kode_barang" name="kode_barang" value="<?= htmlspecialchars($row['kode_barang']) ?>" readonly required>
      <label for="nama_barang">Nama Barang</label>
      <input type="text" id="nama_barang" name="nama_barang" value="<?= htmlspecialchars($row['nama_barang']) ?>" required>
      <label for="detail_barang">Detail Barang</label>
      <textarea id="detail_barang" name="detail_barang" required><?= htmlspecialchars($row['detail_barang']) ?></textarea>
      <label for="kode_kategori">Kategori</label>
      <select id="kode_kategori" name="kode_kategori" required>
        <option value="">Pilih Kategori</option>
        <?php while($k = $kategori->fetch_assoc()): ?>
        <option value="<?= $k['kode_kategori'] ?>" <?= $row['kode_kategori']==$k['kode_kategori']?'selected':'' ?>><?= htmlspecialchars($k['nama_kategori']) ?></option>
        <?php endwhile; ?>
      </select>
      <label for="tahun_perolehan">Tahun Perolehan</label>
      <input type="text" id="tahun_perolehan" name="tahun_perolehan" value="<?= htmlspecialchars($row['tahun_perolehan']) ?>">
      <label for="lokasi_penyimpanan">Lokasi Penyimpanan</label>
      <input type="text" id="lokasi_penyimpanan" name="lokasi_penyimpanan" value="<?= htmlspecialchars($row['lokasi_penyimpanan']) ?>">
      <label for="kondisi">Kondisi</label>
      <select id="kondisi" name="kondisi">
        <option value="baik" <?= $row['kondisi']==='baik'?'selected':'' ?>>Baik</option>
        <option value="rusak_ringan" <?= $row['kondisi']==='rusak_ringan'?'selected':'' ?>>Rusak Ringan</option>
        <option value="rusak_berat" <?= $row['kondisi']==='rusak_berat'?'selected':'' ?>>Rusak Berat</option>
      </select>
      <label for="nilai_awal">Nilai Awal</label>
      <input type="number" id="nilai_awal" name="nilai_awal" step="0.01" value="<?= htmlspecialchars($row['nilai_awal']) ?>">
      <label for="status">Status</label>
      <select id="status" name="status">
        <option value="aktif" <?= $row['status']==='aktif'?'selected':'' ?>>Aktif</option>
        <option value="mutasi" <?= $row['status']==='mutasi'?'selected':'' ?>>Mutasi</option>
        <option value="dihapus" <?= $row['status']==='dihapus'?'selected':'' ?>>Dihapus</option>
      </select>
      <label for="dokumentasi">Dokumentasi (path gambar)</label>
      <input type="text" id="dokumentasi" name="dokumentasi" value="<?= htmlspecialchars($row['dokumentasi']) ?>">
      <div class="form-actions">
        <button type="submit" class="btn-main">Simpan</button>
        <a href="index.php" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
