<?php
include '../../include/cek_login.php';
session_start();
require_once __DIR__ . '/../../include/koneksi.php';
require_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';
$role = $_SESSION['role'] ?? '';
$showSwal = '';
if (!cek_akses($role, 'laporan', 'update')) {
    $showSwal = "<script>Swal.fire({icon:'error',title:'Akses Ditolak',text:'Anda tidak memiliki hak untuk mengedit data ini!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
}
$id = intval($_GET['id'] ?? 0);
if ($id < 1) $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'ID tidak valid!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
$pegawai = $conn->query("SELECT * FROM pegawai WHERE aktif=1 ORDER BY nama_pegawai");
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$showSwal) {
    $periode = trim($_POST['periode'] ?? '');
    $tanggal_cetak = trim($_POST['tanggal_cetak'] ?? '');
    $total_barang = intval($_POST['total_barang'] ?? 0);
    $baik = intval($_POST['barang_baik'] ?? 0);
    $rr = intval($_POST['barang_rusak_ringan'] ?? 0);
    $rb = intval($_POST['barang_rusak_berat'] ?? 0);
    $lokasi = trim($_POST['lokasi'] ?? '');
    $id_pegawai = intval($_POST['id_pegawai'] ?? 0);
    if ($periode && $tanggal_cetak && $id_pegawai) {
        $stmt = $conn->prepare("UPDATE laporan SET periode=?, tanggal_cetak=?, total_barang=?, barang_baik=?, barang_rusak_ringan=?, barang_rusak_berat=?, lokasi=?, id_pegawai=? WHERE id_laporan=?");
        $stmt->bind_param('ssiiiiiii', $periode, $tanggal_cetak, $total_barang, $baik, $rr, $rb, $lokasi, $id_pegawai, $id);
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Data berhasil diupdate!',showConfirmButton:false,timer:1500}).then(()=>{window.location='index.php';});</script>";
        } else {
            $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data gagal diupdate!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data tidak lengkap!',showConfirmButton:true});</script>";
    }
}
$q = $conn->query("SELECT * FROM laporan WHERE id_laporan=$id");
$row = $q->fetch_assoc();
$title = 'Edit Laporan';
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
    <h2>Edit Laporan</h2>
    <form method="post">
      <label for="periode">Periode</label>
      <input type="text" id="periode" name="periode" value="<?= htmlspecialchars($row['periode']) ?>" required>
      <label for="tanggal_cetak">Tanggal Cetak</label>
      <input type="date" id="tanggal_cetak" name="tanggal_cetak" value="<?= htmlspecialchars($row['tanggal_cetak']) ?>" required>
      <label for="total_barang">Total Barang</label>
      <input type="number" id="total_barang" name="total_barang" value="<?= htmlspecialchars($row['total_barang']) ?>" required>
      <label for="barang_baik">Barang Baik</label>
      <input type="number" id="barang_baik" name="barang_baik" value="<?= htmlspecialchars($row['barang_baik']) ?>" required>
      <label for="barang_rusak_ringan">Rusak Ringan</label>
      <input type="number" id="barang_rusak_ringan" name="barang_rusak_ringan" value="<?= htmlspecialchars($row['barang_rusak_ringan']) ?>" required>
      <label for="barang_rusak_berat">Rusak Berat</label>
      <input type="number" id="barang_rusak_berat" name="barang_rusak_berat" value="<?= htmlspecialchars($row['barang_rusak_berat']) ?>" required>
      <label for="lokasi">Lokasi</label>
      <input type="text" id="lokasi" name="lokasi" value="<?= htmlspecialchars($row['lokasi']) ?>" required>
      <label for="id_pegawai">Pegawai</label>
      <select id="id_pegawai" name="id_pegawai" required>
        <option value="">Pilih Pegawai</option>
        <?php while($p = $pegawai->fetch_assoc()): ?>
        <option value="<?= $p['id_pegawai'] ?>" <?= $row['id_pegawai']==$p['id_pegawai']?'selected':'' ?>><?= htmlspecialchars($p['nama_pegawai']) ?></option>
        <?php endwhile; ?>
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
