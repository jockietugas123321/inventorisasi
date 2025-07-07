<?php
include_once __DIR__ . '/../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/sweetalert.php';
require_once __DIR__ . '/../../include/koneksi.php';
$kode = $_GET['kode'] ?? '';
$showSwal = '';
if (!$kode) {
    echo "<script>Swal.fire('Gagal', 'Kode tidak valid!', 'error').then(()=>window.location='penyusutan.php');</script>";
    exit;
}
$q = $conn->query("SELECT * FROM penyusutan WHERE kode_penyusutan='".$conn->real_escape_string($kode)."'");
$row = $q->fetch_assoc();
$barang = $conn->query("SELECT kode_barang, nama_barang FROM barang WHERE status='aktif' ORDER BY nama_barang");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_barang = $_POST['kode_barang'] ?? '';
    $nama_barang = '';
    $tahun = $_POST['tahun'] ?? '';
    $nilai_awal = floatval($_POST['nilai_awal'] ?? 0);
    $akumulasi = floatval($_POST['akumulasi'] ?? 0);
    $nilai_buku = floatval($_POST['nilai_buku'] ?? 0);
    $keterangan = trim($_POST['keterangan'] ?? '');
    if ($kode_barang) {
        $res = $conn->query("SELECT nama_barang FROM barang WHERE kode_barang='".$conn->real_escape_string($kode_barang)."'");
        if ($r = $res->fetch_assoc()) $nama_barang = $r['nama_barang'];
    }
    if ($kode_barang && $tahun) {
        $stmt = $conn->prepare("UPDATE penyusutan SET kode_barang=?, nama_barang=?, tahun=?, nilai_awal=?, akumulasi=?, nilai_buku=?, keterangan=? WHERE kode_penyusutan=?");
        $stmt->bind_param('ssiddsss', $kode_barang, $nama_barang, $tahun, $nilai_awal, $akumulasi, $nilai_buku, $keterangan, $kode);
        if ($stmt->execute()) {
            $showSwal = "<script>Swal.fire('Berhasil', 'Data berhasil diupdate!', 'success').then(()=>window.location='penyusutan.php');</script>";
        } else {
            $showSwal = "<script>Swal.fire('Gagal', 'Data gagal diupdate!', 'error').then(()=>window.location='penyusutan.php');</script>";
        }
    } else {
        $showSwal = "<script>Swal.fire('Gagal', 'Data tidak lengkap!', 'error');</script>";
    }
}
$title = 'Edit Penyusutan';
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
    <h2>Edit Penyusutan</h2>
    <form method="post">
      <label for="kode_barang">Barang</label>
      <select id="kode_barang" name="kode_barang" required>
        <option value="">Pilih Barang</option>
        <?php while($b = $barang->fetch_assoc()): ?>
        <option value="<?= $b['kode_barang'] ?>" <?= $row['kode_barang']==$b['kode_barang']?'selected':'' ?>><?= htmlspecialchars($b['nama_barang']) ?></option>
        <?php endwhile; ?>
      </select>
      <label for="tahun">Tahun</label>
      <input type="number" id="tahun" name="tahun" min="2000" max="<?= date('Y') ?>" value="<?= htmlspecialchars($row['tahun']) ?>" required>
      <label for="nilai_awal">Nilai Perolehan</label>
      <input type="number" id="nilai_awal" name="nilai_awal" step="0.01" value="<?= htmlspecialchars($row['nilai_awal']) ?>" required>
      <label for="akumulasi">Akumulasi Penyusutan</label>
      <input type="number" id="akumulasi" name="akumulasi" step="0.01" value="<?= htmlspecialchars($row['akumulasi']) ?>" required>
      <label for="nilai_buku">Nilai Buku</label>
      <input type="number" id="nilai_buku" name="nilai_buku" step="0.01" value="<?= htmlspecialchars($row['nilai_buku']) ?>" required>
      <label for="keterangan">Keterangan</label>
      <textarea id="keterangan" name="keterangan"><?= htmlspecialchars($row['keterangan']) ?></textarea>
      <div class="form-actions">
        <button type="submit" class="btn-main">Simpan</button>
        <a href="penyusutan.php" class="btn-cancel">Batal</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
