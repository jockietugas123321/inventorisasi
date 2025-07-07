<?php
include_once __DIR__ . '/../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';
require_once __DIR__ . '/../../include/koneksi.php';
$barang = $conn->query("SELECT kode_barang, nama_barang, lokasi_penyimpanan FROM barang WHERE status='aktif' ORDER BY nama_barang");
$pegawai = $conn->query("SELECT kode_pegawai, nama_pegawai FROM pegawai WHERE aktif=1 ORDER BY nama_pegawai");
$showSwal = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_mutasi = 'MTS-' . date('Y') . '-' . strtoupper(substr(md5(uniqid()),0,6));
    $kode_barang = $_POST['kode_barang'] ?? '';
    $nama_barang = '';
    $lokasi_asal = trim($_POST['lokasi_asal'] ?? '');
    $lokasi_tujuan = trim($_POST['lokasi_tujuan'] ?? '');
    $kode_pegawai_asal = $_POST['kode_pegawai_asal'] ?? '';
    $nama_pegawai_asal = '';
    $kode_pegawai_tujuan = $_POST['kode_pegawai_tujuan'] ?? '';
    $nama_pegawai_tujuan = '';
    $tgl_mutasi = $_POST['tgl_mutasi'] ?? '';
    $keterangan = trim($_POST['keterangan'] ?? '');
    // Snapshot nama_barang dan pegawai
    if ($kode_barang) {
        $res = $conn->query("SELECT nama_barang FROM barang WHERE kode_barang='".$conn->real_escape_string($kode_barang)."'");
        if ($row = $res->fetch_assoc()) $nama_barang = $row['nama_barang'];
    }
    if ($kode_pegawai_asal) {
        $res = $conn->query("SELECT nama_pegawai FROM pegawai WHERE kode_pegawai='".$conn->real_escape_string($kode_pegawai_asal)."'");
        if ($row = $res->fetch_assoc()) $nama_pegawai_asal = $row['nama_pegawai'];
    }
    if ($kode_pegawai_tujuan) {
        $res = $conn->query("SELECT nama_pegawai FROM pegawai WHERE kode_pegawai='".$conn->real_escape_string($kode_pegawai_tujuan)."'");
        if ($row = $res->fetch_assoc()) $nama_pegawai_tujuan = $row['nama_pegawai'];
    }
    if ($kode_barang && $lokasi_asal && $lokasi_tujuan && $kode_pegawai_asal && $kode_pegawai_tujuan && $tgl_mutasi) {
        $stmt = $conn->prepare("INSERT INTO mutasi (kode_mutasi, kode_barang, nama_barang, lokasi_asal, lokasi_tujuan, kode_pegawai_asal, nama_pegawai_asal, kode_pegawai_tujuan, nama_pegawai_tujuan, tgl_mutasi, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssssssss', $kode_mutasi, $kode_barang, $nama_barang, $lokasi_asal, $lokasi_tujuan, $kode_pegawai_asal, $nama_pegawai_asal, $kode_pegawai_tujuan, $nama_pegawai_tujuan, $tgl_mutasi, $keterangan);
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire('Berhasil', 'Data berhasil ditambahkan!', 'success').then(()=>window.location='mutasi.php');</script>";
        } else {
            $showSwal = "<script>Swal.fire('Gagal', 'Data gagal ditambahkan!', 'error').then(()=>window.location='mutasi.php');</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire('Gagal', 'Data tidak lengkap!', 'error');</script>";
    }
}
$title = 'Tambah Mutasi Aset';
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
    <h2>Tambah Mutasi Aset</h2>
    <form method="post">
      <label for="kode_barang">Barang</label>
      <select id="kode_barang" name="kode_barang" required>
        <option value="">Pilih Barang</option>
        <?php while($b = $barang->fetch_assoc()): ?>
        <option value="<?= $b['kode_barang'] ?>"><?= htmlspecialchars($b['nama_barang']) ?> (<?= htmlspecialchars($b['lokasi_penyimpanan']) ?>)</option>
        <?php endwhile; ?>
      </select>
      <label for="lokasi_asal">Lokasi Asal</label>
      <input type="text" id="lokasi_asal" name="lokasi_asal" required>
      <label for="lokasi_tujuan">Lokasi Tujuan</label>
      <input type="text" id="lokasi_tujuan" name="lokasi_tujuan" required>
      <label for="kode_pegawai_asal">Pegawai Asal</label>
      <select id="kode_pegawai_asal" name="kode_pegawai_asal" required>
        <option value="">Pilih Pegawai</option>
        <?php while($p = $pegawai->fetch_assoc()): ?>
        <option value="<?= $p['kode_pegawai'] ?>"><?= htmlspecialchars($p['nama_pegawai']) ?></option>
        <?php endwhile; ?>
      </select>
      <label for="kode_pegawai_tujuan">Pegawai Tujuan</label>
      <select id="kode_pegawai_tujuan" name="kode_pegawai_tujuan" required>
        <option value="">Pilih Pegawai</option>
        <?php $pegawai2 = $conn->query("SELECT kode_pegawai, nama_pegawai FROM pegawai WHERE aktif=1 ORDER BY nama_pegawai"); while($p2 = $pegawai2->fetch_assoc()): ?>
        <option value="<?= $p2['kode_pegawai'] ?>"><?= htmlspecialchars($p2['nama_pegawai']) ?></option>
        <?php endwhile; ?>
      </select>
      <label for="tgl_mutasi">Tanggal Mutasi</label>
      <input type="date" id="tgl_mutasi" name="tgl_mutasi" required>
      <label for="keterangan">Keterangan</label>
      <textarea id="keterangan" name="keterangan"></textarea>
      <div class="form-actions">
        <button type="submit" class="btn-main">Simpan</button>
        <a href="mutasi.php" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
