<?php
include_once __DIR__ . '/../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';
require_once __DIR__ . '/../../include/koneksi.php';
$barang = $conn->query("SELECT kode_barang, nama_barang, nilai_awal FROM barang WHERE status='aktif' ORDER BY nama_barang");
$showSwal = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_penyusutan = 'PSN-' . date('Y') . '-' . strtoupper(substr(md5(uniqid()),0,6));
    $kode_barang = $_POST['kode_barang'] ?? '';
    $nama_barang = '';
    $tahun = $_POST['tahun'] ?? '';
    $nilai_awal = floatval($_POST['nilai_awal'] ?? 0);
    $akumulasi = floatval($_POST['akumulasi'] ?? 0);
    $nilai_buku = floatval($_POST['nilai_buku'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');
    if ($kode_barang) {
        $res = $conn->query("SELECT nama_barang FROM barang WHERE kode_barang='".$conn->real_escape_string($kode_barang)."'");
        if ($row = $res->fetch_assoc()) $nama_barang = $row['nama_barang'];
    }
    if ($kode_barang && $tahun) {
        $stmt = $conn->prepare("INSERT INTO penyusutan (kode_penyusutan, kode_barang, nama_barang, tahun, nilai_awal, akumulasi, nilai_buku, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssiddds', $kode_penyusutan, $kode_barang, $nama_barang, $tahun, $nilai_awal, $akumulasi, $nilai_buku, $keterangan);
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire('Berhasil', 'Data berhasil ditambahkan!', 'success').then(()=>window.location='penyusutan.php');</script>";
        } else {
            $showSwal = "<script>Swal.fire('Gagal', 'Data gagal ditambahkan!', 'error').then(()=>window.location='penyusutan.php');</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire('Gagal', 'Data tidak lengkap!', 'error');</script>";
    }
}
$title = 'Tambah Penyusutan';
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
    <h2>Tambah Penyusutan</h2>
    <form method="post">
      <label for="kode_barang">Barang</label>
      <select id="kode_barang" name="kode_barang" required>
        <option value="">Pilih Barang</option>
        <?php while($b = $barang->fetch_assoc()): ?>
        <option value="<?= $b['kode_barang'] ?>"><?= htmlspecialchars($b['nama_barang']) ?></option>
        <?php endwhile; ?>
      </select>
      <label for="tahun">Tahun</label>
      <input type="number" id="tahun" name="tahun" min="2000" max="<?= date('Y') ?>" required>
      <label for="nilai_awal">Nilai Perolehan</label>
      <input type="number" id="nilai_awal" name="nilai_awal" step="0.01" required>
      <label for="akumulasi">Akumulasi Penyusutan</label>
      <input type="number" id="akumulasi" name="akumulasi" step="0.01" required>
      <label for="nilai_buku">Nilai Buku</label>
      <input type="number" id="nilai_buku" name="nilai_buku" step="0.01" required>
      <label for="keterangan">Keterangan</label>
      <textarea id="keterangan" name="keterangan"></textarea>
      <div class="form-actions">
        <button type="submit" class="btn-main">Simpan</button>
        <a href="penyusutan.php" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
