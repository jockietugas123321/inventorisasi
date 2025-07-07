<?php
include_once __DIR__ . '/../../include/cek_login.php';
session_start();
require_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';
$role = $_SESSION['role'] ?? '';
$showSwal = '';
if (!cek_akses($role, 'stockopname', 'create')) {
    $showSwal = "<script>Swal.fire({icon:'error',title:'Akses Ditolak',text:'Anda tidak memiliki hak untuk menambah data ini!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
}
require_once __DIR__ . '/../../include/koneksi.php';
$barang = $conn->query("SELECT kode_barang, nama_barang FROM barang ORDER BY nama_barang");
$pegawai = $conn->query("SELECT kode_pegawai, nama_pegawai FROM pegawai WHERE aktif=1 ORDER BY nama_pegawai");
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$showSwal) {
    $kode_barang = $_POST['kode_barang'] ?? '';
    $jumlah_sistem = intval($_POST['jumlah_sistem'] ?? 0);
    $jumlah_fisik = intval($_POST['jumlah_fisik'] ?? 0);
    $selisih = $jumlah_fisik - $jumlah_sistem;
    $kondisi = trim($_POST['kondisi_fisik'] ?? '');
    $kode_pegawai = $_POST['kode_pegawai'] ?? '';
    $tanggal = trim($_POST['tanggal_opname'] ?? '');
    $catatan = trim($_POST['catatan'] ?? '');
    // Ambil snapshot nama_barang dan nama_pegawai
    $nama_barang = '';
    $nama_pegawai = '';
    if ($kode_barang) {
        $res = $conn->query("SELECT nama_barang FROM barang WHERE kode_barang='".$conn->real_escape_string($kode_barang)."'");
        if ($row = $res->fetch_assoc()) $nama_barang = $row['nama_barang'];
    }
    if ($kode_pegawai) {
        $res = $conn->query("SELECT nama_pegawai FROM pegawai WHERE kode_pegawai='".$conn->real_escape_string($kode_pegawai)."'");
        if ($row = $res->fetch_assoc()) $nama_pegawai = $row['nama_pegawai'];
    }
    // Generate kode_opname unik
    $kode_opname = 'OPN-' . date('Y') . '-' . strtoupper(substr(md5(uniqid()),0,6));
    if ($kode_barang && $kode_pegawai && $tanggal) {
        $stmt = $conn->prepare("INSERT INTO stock_opname (kode_opname, kode_barang, nama_barang, jumlah_sistem, jumlah_fisik, selisih, kondisi_fisik, kode_pegawai, nama_pegawai, tanggal_opname, catatan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssiiisssss', $kode_opname, $kode_barang, $nama_barang, $jumlah_sistem, $jumlah_fisik, $selisih, $kondisi, $kode_pegawai, $nama_pegawai, $tanggal, $catatan);
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire({icon:'success',title:'Berhasil',text:'Data berhasil ditambahkan!',showConfirmButton:false,timer:1500}).then(()=>{window.location='index.php';});</script>";
        } else {
            $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data gagal ditambahkan!',showConfirmButton:true}).then(()=>{window.location='index.php';});</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire({icon:'error',title:'Gagal',text:'Data tidak lengkap!',showConfirmButton:true});</script>";
    }
}
$title = 'Tambah Stock Opname';
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
    <h2>Tambah Stock Opname</h2>
    <form method="post">
      <label for="kode_barang">Barang</label>
      <select id="kode_barang" name="kode_barang" required>
        <option value="">Pilih Barang</option>
        <?php while($b = $barang->fetch_assoc()): ?>
        <option value="<?= $b['kode_barang'] ?>"><?= htmlspecialchars($b['nama_barang']) ?></option>
        <?php endwhile; ?>
      </select>
      <label for="jumlah_sistem">Jumlah Sistem</label>
      <input type="number" id="jumlah_sistem" name="jumlah_sistem">
      <label for="jumlah_fisik">Jumlah Fisik</label>
      <input type="number" id="jumlah_fisik" name="jumlah_fisik">
      <label for="kondisi_fisik">Kondisi Fisik</label>
      <input type="text" id="kondisi_fisik" name="kondisi_fisik">
      <label for="kode_pegawai">Pegawai</label>
      <select id="kode_pegawai" name="kode_pegawai" required>
        <option value="">Pilih Pegawai</option>
        <?php while($p = $pegawai->fetch_assoc()): ?>
        <option value="<?= $p['kode_pegawai'] ?>"><?= htmlspecialchars($p['nama_pegawai']) ?></option>
        <?php endwhile; ?>
      </select>
      <label for="tanggal_opname">Tanggal Opname</label>
      <input type="date" id="tanggal_opname" name="tanggal_opname" required>
      <label for="catatan">Catatan</label>
      <textarea id="catatan" name="catatan"></textarea>
      <div class="form-actions">
        <button type="submit" class="btn-main">Simpan</button>
        <a href="index.php" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
