<?php
include_once __DIR__ . '/../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/Sidebar.php';
include_once __DIR__ . '/../../include/sweetalert.php';
require_once __DIR__ . '/../../include/koneksi.php';

$title = 'Penyusutan Aset';
$penyusutan = $conn->query("SELECT * FROM penyusutan ORDER BY tahun DESC, nama_barang");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="../../style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { margin: 0; background: linear-gradient(135deg, #e3f0ff 0%, #fff 100%); font-family: 'Segoe UI', 'Roboto', Arial, sans-serif; }
        .main-content { margin-left: 240px; padding: 48px 5vw 40px 5vw; min-height: 100vh; }
        .page-title { font-size: 2rem; font-weight: 900; color: #223468; margin-bottom: 18px; letter-spacing: 0.5px; }
        .desc { color: #0E5C71; margin-bottom: 24px; font-size: 1.13rem; font-weight: 500; }
        .add-btn { display:inline-block; margin-bottom:18px; background:#0E5C71; color:#fff; border:none; border-radius:8px; padding:10px 24px; font-weight:700; font-size:1.08rem; text-decoration:none; transition:background 0.2s; }
        .add-btn:hover { background:#223468; color:#fff; }
        .invent-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 16px #0E5C7110; }
        .invent-table th, .invent-table td { padding: 16px 20px; border-bottom: 1px solid #e3e3e3; text-align: left; }
        .invent-table th { background: #e3f0ff; color: #223468; font-weight: 800; font-size: 1.08rem; }
        .invent-table tr:last-child td { border-bottom: none; }
        .action-btn { padding: 7px 16px; border-radius: 7px; font-weight: 600; border: none; cursor: pointer; margin: 0 2px; font-size: 1rem; text-decoration:none; }
        .action-edit { background: #e3f0ff; color: #0E5C71; }
        .action-del { background: #ffe3e3; color: #c00; }
        .action-edit:hover { background: #bcd; color: #223468; }
        .action-del:hover { background: #ffbdbd; color: #900; }
        @media (max-width: 900px) { .main-content { margin-left: 0; padding: 24px 8px; } .invent-table th, .invent-table td { padding: 10px 8px; font-size:0.98rem; } }
        @media (max-width: 600px) { .invent-table th, .invent-table td { font-size:0.92rem; } }
    </style>
</head>
<body>
<?php include_once __DIR__ . '/../../include/Sidebar.php'; ?>
<div class="main-content">
    <div class="page-title">Penyusutan Aset</div>
    <div class="desc">Riwayat perhitungan dan pencatatan penyusutan aset. Data diurutkan dari tahun terbaru.</div>
    <a href="penyusutan_tambah.php" class="add-btn">+ Tambah Penyusutan</a>
    <div style="overflow-x:auto;">
    <table class="invent-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Penyusutan</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Tahun</th>
                <th>Nilai Perolehan</th>
                <th>Akumulasi Penyusutan</th>
                <th>Nilai Buku</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php $no=1; while($b = $penyusutan->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($b['kode_penyusutan']) ?></td>
                <td><?= htmlspecialchars($b['kode_barang']) ?></td>
                <td><?= htmlspecialchars($b['nama_barang']) ?></td>
                <td><?= htmlspecialchars($b['tahun']) ?></td>
                <td>Rp <?= number_format($b['nilai_awal'],0,',','.') ?></td>
                <td>Rp <?= number_format($b['akumulasi'],0,',','.') ?></td>
                <td>Rp <?= number_format($b['nilai_buku'],0,',','.') ?></td>
                <td><?= htmlspecialchars($b['keterangan']) ?></td>
                <td>
                    <div style="display:flex;gap:8px;justify-content:center;align-items:center;">
                        <a href="penyusutan_edit.php?kode=<?= urlencode($b['kode_penyusutan']) ?>" class="action-btn action-edit">Edit</a>
                        <a href="penyusutan_hapus.php?kode=<?= urlencode($b['kode_penyusutan']) ?>" class="action-btn action-del" onclick="return confirm('Yakin hapus data?')">Hapus</a>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>
