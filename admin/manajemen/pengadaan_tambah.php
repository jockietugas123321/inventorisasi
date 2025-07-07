<?php
include_once __DIR__ . '/../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';
require_once __DIR__ . '/../../include/koneksi.php';
$barang = $conn->query("SELECT kode_barang, nama_barang FROM barang WHERE status='aktif' ORDER BY nama_barang");
$kategori = $conn->query("SELECT kode_kategori, nama_kategori FROM kategori ORDER BY nama_kategori");
$showSwal = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_pengadaan = 'PNG-' . date('Y') . '-' . strtoupper(substr(md5(uniqid()),0,6));
    $kode_barang = $_POST['kode_barang'] ?? '';
    $nama_barang = '';
    $kode_kategori = $_POST['kode_kategori'] ?? '';
    $nama_kategori = '';
    $tgl_pengadaan = $_POST['tgl_pengadaan'] ?? '';
    $supplier = trim($_POST['supplier'] ?? '');
    $nilai = floatval($_POST['nilai'] ?? 0);
    $dokumen = trim($_POST['dokumen'] ?? '');
    $keterangan = trim($_POST['keterangan'] ?? '');
    if ($kode_barang) {
        $res = $conn->query("SELECT nama_barang FROM barang WHERE kode_barang='".$conn->real_escape_string($kode_barang)."'");
        if ($row = $res->fetch_assoc()) $nama_barang = $row['nama_barang'];
    }
    if ($kode_kategori) {
        $res = $conn->query("SELECT nama_kategori FROM kategori WHERE kode_kategori='".$conn->real_escape_string($kode_kategori)."'");
        if ($row = $res->fetch_assoc()) $nama_kategori = $row['nama_kategori'];
    }
    if ($kode_barang && $kode_kategori && $tgl_pengadaan) {
        $stmt = $conn->prepare("INSERT INTO pengadaan (kode_pengadaan, kode_barang, nama_barang, kode_kategori, nama_kategori, tgl_pengadaan, supplier, nilai, dokumen, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssss', $kode_pengadaan, $kode_barang, $nama_barang, $kode_kategori, $nama_kategori, $tgl_pengadaan, $supplier, $nilai, $dokumen, $keterangan);
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire('Berhasil', 'Data berhasil ditambahkan!', 'success').then(()=>window.location='pengadaan.php');</script>";
        } else {
            $showSwal = "<script>Swal.fire('Gagal', 'Data gagal ditambahkan!', 'error').then(()=>window.location='pengadaan.php');</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire('Gagal', 'Data tidak lengkap!', 'error');</script>";
    }
}
$title = 'Tambah Pengadaan';
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
    <h2>Tambah Pengadaan</h2>
    <form method="post">
      <label for="kode_barang">Barang</label>
      <select id="kode_barang" name="kode_barang" required>
        <option value="">Pilih Barang</option>
        <?php while($b = $barang->fetch_assoc()): ?>
        <option value="<?= $b['kode_barang'] ?>"><?= htmlspecialchars($b['nama_barang']) ?></option>
        <?php endwhile; ?>
      </select>
      <label for="kode_kategori">Kategori</label>
      <select id="kode_kategori" name="kode_kategori" required>
        <option value="">Pilih Kategori</option>
        <?php while($k = $kategori->fetch_assoc()): ?>
        <option value="<?= $k['kode_kategori'] ?>"><?= htmlspecialchars($k['nama_kategori']) ?></option>
        <?php endwhile; ?>
      </select>
      <label for="tgl_pengadaan">Tanggal Pengadaan</label>
      <input type="date" id="tgl_pengadaan" name="tgl_pengadaan" required>
      <label for="supplier">Supplier</label>
      <input type="text" id="supplier" name="supplier">
      <label for="nilai">Nilai</label>
      <input type="number" id="nilai" name="nilai">
      <label for="dokumen">Dokumen</label>
      <input type="text" id="dokumen" name="dokumen">
      <label for="keterangan">Keterangan</label>
      <textarea id="keterangan" name="keterangan"></textarea>
      <div class="form-actions">
        <button type="submit" class="btn-main">Simpan</button>
        <a href="pengadaan.php" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
