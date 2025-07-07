<?php
include_once __DIR__ . '/../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';
require_once __DIR__ . '/../../include/koneksi.php';
$kode = $_GET['kode'] ?? '';
$showSwal = '';
if (!$kode) {
    echo "<script>Swal.fire('Gagal', 'Kode tidak valid!', 'error').then(()=>window.location='mutasi.php');</script>";
    exit;
}
$q = $conn->query("SELECT * FROM mutasi WHERE kode_mutasi='".$conn->real_escape_string($kode)."'");
$row = $q->fetch_assoc();
$barang = $conn->query("SELECT kode_barang, nama_barang, lokasi_penyimpanan FROM barang WHERE status='aktif' ORDER BY nama_barang");
$pegawai = $conn->query("SELECT kode_pegawai, nama_pegawai FROM pegawai WHERE aktif=1 ORDER BY nama_pegawai");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    if ($kode_barang) {
        $res = $conn->query("SELECT nama_barang FROM barang WHERE kode_barang='".$conn->real_escape_string($kode_barang)."'");
        if ($r = $res->fetch_assoc()) $nama_barang = $r['nama_barang'];
    }
    if ($kode_pegawai_asal) {
        $res = $conn->query("SELECT nama_pegawai FROM pegawai WHERE kode_pegawai='".$conn->real_escape_string($kode_pegawai_asal)."'");
        if ($r = $res->fetch_assoc()) $nama_pegawai_asal = $r['nama_pegawai'];
    }
    if ($kode_pegawai_tujuan) {
        $res = $conn->query("SELECT nama_pegawai FROM pegawai WHERE kode_pegawai='".$conn->real_escape_string($kode_pegawai_tujuan)."'");
        if ($r = $res->fetch_assoc()) $nama_pegawai_tujuan = $r['nama_pegawai'];
    }
    if ($kode_barang && $lokasi_asal && $lokasi_tujuan && $kode_pegawai_asal && $kode_pegawai_tujuan && $tgl_mutasi) {
        $stmt = $conn->prepare("UPDATE mutasi SET kode_barang=?, nama_barang=?, lokasi_asal=?, lokasi_tujuan=?, kode_pegawai_asal=?, nama_pegawai_asal=?, kode_pegawai_tujuan=?, nama_pegawai_tujuan=?, tgl_mutasi=?, keterangan=? WHERE kode_mutasi=?");
        $stmt->bind_param('sssssssssss', $kode_barang, $nama_barang, $lokasi_asal, $lokasi_tujuan, $kode_pegawai_asal, $nama_pegawai_asal, $kode_pegawai_tujuan, $nama_pegawai_tujuan, $tgl_mutasi, $keterangan, $kode);
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire('Berhasil', 'Data berhasil diupdate!', 'success').then(()=>window.location='mutasi.php');</script>";
        } else {
            $showSwal = "<script>Swal.fire('Gagal', 'Data gagal diupdate!', 'error').then(()=>window.location='mutasi.php');</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire('Gagal', 'Data tidak lengkap!', 'error');</script>";
    }
}
$title = 'Edit Mutasi Aset';
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
    <h2>Edit Mutasi Aset</h2>
    <form method="post">
      <label for="kode_barang">Barang</label>
      <select id="kode_barang" name="kode_barang" required>
        <option value="">Pilih Barang</option>
        <?php while($b = $barang->fetch_assoc()): ?>
        <option value="<?= $b['kode_barang'] ?>" <?= $row['kode_barang']==$b['kode_barang']?'selected':'' ?>><?= htmlspecialchars($b['nama_barang']) ?> (<?= htmlspecialchars($b['lokasi_penyimpanan']) ?>)</option>
        <?php endwhile; ?>
      </select>
      <label for="lokasi_asal">Lokasi Asal</label>
      <input type="text" id="lokasi_asal" name="lokasi_asal" value="<?= htmlspecialchars($row['lokasi_asal']) ?>" required>
      <label for="lokasi_tujuan">Lokasi Tujuan</label>
      <input type="text" id="lokasi_tujuan" name="lokasi_tujuan" value="<?= htmlspecialchars($row['lokasi_tujuan']) ?>" required>
      <label for="kode_pegawai_asal">Pegawai Asal</label>
      <select id="kode_pegawai_asal" name="kode_pegawai_asal" required>
        <option value="">Pilih Pegawai</option>
        <?php while($p = $pegawai->fetch_assoc()): ?>
        <option value="<?= $p['kode_pegawai'] ?>" <?= $row['kode_pegawai_asal']==$p['kode_pegawai']?'selected':'' ?>><?= htmlspecialchars($p['nama_pegawai']) ?></option>
        <?php endwhile; ?>
      </select>
      <label for="kode_pegawai_tujuan">Pegawai Tujuan</label>
      <select id="kode_pegawai_tujuan" name="kode_pegawai_tujuan" required>
        <option value="">Pilih Pegawai</option>
        <?php $pegawai2 = $conn->query("SELECT kode_pegawai, nama_pegawai FROM pegawai WHERE aktif=1 ORDER BY nama_pegawai"); while($p2 = $pegawai2->fetch_assoc()): ?>
        <option value="<?= $p2['kode_pegawai'] ?>" <?= $row['kode_pegawai_tujuan']==$p2['kode_pegawai']?'selected':'' ?>><?= htmlspecialchars($p2['nama_pegawai']) ?></option>
        <?php endwhile; ?>
      </select>
      <label for="tgl_mutasi">Tanggal Mutasi</label>
      <input type="date" id="tgl_mutasi" name="tgl_mutasi" value="<?= htmlspecialchars($row['tgl_mutasi']) ?>" required>
      <label for="keterangan">Keterangan</label>
      <textarea id="keterangan" name="keterangan"><?= htmlspecialchars($row['keterangan']) ?></textarea>
      <div class="form-actions">
        <button type="submit" class="btn-main">Simpan</button>
        <a href="mutasi.php" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
