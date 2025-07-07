<?php
include_once __DIR__ . '/../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/Sidebar.php';
include_once __DIR__ . '/../../include/sweetalert.php';
require_once __DIR__ . '/../../include/koneksi.php';
$title = 'Laporan Rekap Barang';
$rekap = $conn->query("SELECT * FROM laporan_rekap_barang ORDER BY tanggal_laporan DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="../../style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .main-content { margin-left: 240px; padding: 32px 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; }
        th { background: #f0f4fa; }
        .action-btn { padding: 6px 14px; border-radius: 7px; font-weight: 600; border: none; cursor: pointer; margin: 0 2px; }
        .action-edit { background: #e3f0ff; color: #0E5C71; }
        .action-del { background: #ffe3e3; color: #c00; }
        .action-edit:hover { background: #bcd; color: #223468; }
        .action-del:hover { background: #ffbdbd; color: #900; }
        @media (max-width: 900px) { .main-content { margin-left: 0; padding: 16px 4vw; } }
    </style>
</head>
<body>
<?php include_once __DIR__ . '/../../include/Sidebar.php'; ?>
<div class="main-content">
    <h2>Laporan Rekap Barang</h2>
    <a href="laporan_rekap_tambah.php" class="action-btn action-edit">+ Tambah Rekap</a>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Laporan</th>
                <th>Tanggal</th>
                <th>Total Barang</th>
                <th>Total Nilai Awal</th>
                <th>Total Nilai Buku</th>
                <th>Baik</th>
                <th>Rusak Ringan</th>
                <th>Rusak Berat</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php $no=1; while($r = $rekap->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($r['kode_laporan']) ?></td>
                <td><?= htmlspecialchars($r['tanggal_laporan']) ?></td>
                <td><?= number_format($r['total_barang']) ?></td>
                <td>Rp <?= number_format($r['total_nilai_awal'],0,',','.') ?></td>
                <td>Rp <?= number_format($r['total_nilai_buku'],0,',','.') ?></td>
                <td><?= number_format($r['total_baik']) ?></td>
                <td><?= number_format($r['total_rusak_ringan']) ?></td>
                <td><?= number_format($r['total_rusak_berat']) ?></td>
                <td><?= htmlspecialchars($r['keterangan']) ?></td>
                <td>
                    <a href="laporan_rekap_edit.php?kode=<?= urlencode($r['kode_laporan']) ?>" class="action-btn action-edit">Edit</a>
                    <a href="laporan_rekap_hapus.php?kode=<?= urlencode($r['kode_laporan']) ?>" class="action-btn action-del" onclick="return confirm('Yakin hapus data?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
