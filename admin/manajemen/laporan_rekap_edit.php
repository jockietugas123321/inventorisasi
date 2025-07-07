<?php
include_once __DIR__ . '/../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';
require_once __DIR__ . '/../../include/koneksi.php';
$kode = $_GET['kode'] ?? '';
$showSwal = '';
if (!$kode) {
    echo "<script>Swal.fire('Gagal', 'Kode tidak valid!', 'error').then(()=>window.location='laporan_rekap.php');</script>";
    exit;
}
$q = $conn->query("SELECT * FROM laporan_rekap_barang WHERE kode_laporan='".$conn->real_escape_string($kode)."'");
$row = $q->fetch_assoc();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal_laporan'] ?? '';
    $total_barang = intval($_POST['total_barang'] ?? 0);
    $total_nilai_awal = floatval($_POST['total_nilai_awal'] ?? 0);
    $total_nilai_buku = floatval($_POST['total_nilai_buku'] ?? 0);
    $total_baik = intval($_POST['total_baik'] ?? 0);
    $total_rusak_ringan = intval($_POST['total_rusak_ringan'] ?? 0);
    $total_rusak_berat = intval($_POST['total_rusak_berat'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');
    if ($tanggal) {
        $stmt = $conn->prepare("UPDATE laporan_rekap_barang SET tanggal_laporan=?, total_barang=?, total_nilai_awal=?, total_nilai_buku=?, total_baik=?, total_rusak_ringan=?, total_rusak_berat=?, keterangan=? WHERE kode_laporan=?");
        $stmt->bind_param('siddiisss', $tanggal, $total_barang, $total_nilai_awal, $total_nilai_buku, $total_baik, $total_rusak_ringan, $total_rusak_berat, $keterangan, $kode);
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire('Berhasil', 'Data berhasil diupdate!', 'success').then(()=>window.location='laporan_rekap.php');</script>";
        } else {
            $showSwal = "<script>Swal.fire('Gagal', 'Data gagal diupdate!', 'error').then(()=>window.location='laporan_rekap.php');</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire('Gagal', 'Tanggal wajib diisi!', 'error');</script>";
    }
}
$title = 'Edit Laporan Rekap';
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title><?= $title ?></title>
<link rel="stylesheet" href="../../style.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?= $showSwal ?>
<div class="form-container">
  <div class="form-box">
    <h2>Edit Laporan Rekap</h2>
    <form method="post">
      <label for="tanggal_laporan">Tanggal Laporan</label>
      <input type="date" id="tanggal_laporan" name="tanggal_laporan" value="<?= htmlspecialchars($row['tanggal_laporan']) ?>" required>
      <label for="total_barang">Total Barang</label>
      <input type="number" id="total_barang" name="total_barang" value="<?= htmlspecialchars($row['total_barang']) ?>" required>
      <label for="total_nilai_awal">Total Nilai Awal</label>
      <input type="number" id="total_nilai_awal" name="total_nilai_awal" value="<?= htmlspecialchars($row['total_nilai_awal']) ?>" required>
      <label for="total_nilai_buku">Total Nilai Buku</label>
      <input type="number" id="total_nilai_buku" name="total_nilai_buku" value="<?= htmlspecialchars($row['total_nilai_buku']) ?>" required>
      <label for="total_baik">Total Baik</label>
      <input type="number" id="total_baik" name="total_baik" value="<?= htmlspecialchars($row['total_baik']) ?>" required>
      <label for="total_rusak_ringan">Total Rusak Ringan</label>
      <input type="number" id="total_rusak_ringan" name="total_rusak_ringan" value="<?= htmlspecialchars($row['total_rusak_ringan']) ?>" required>
      <label for="total_rusak_berat">Total Rusak Berat</label>
      <input type="number" id="total_rusak_berat" name="total_rusak_berat" value="<?= htmlspecialchars($row['total_rusak_berat']) ?>" required>
      <label for="keterangan">Keterangan</label>
      <textarea id="keterangan" name="keterangan"><?= htmlspecialchars($row['keterangan']) ?></textarea>
      <div class="form-actions">
        <button type="submit" class="btn-main">Simpan</button>
        <a href="laporan_rekap.php" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
