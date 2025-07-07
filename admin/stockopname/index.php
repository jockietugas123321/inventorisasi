<?php
session_start();
require_once __DIR__ . '/../../include/auth.php';
$role = $_SESSION['role'] ?? '';
if (!cek_akses($role, 'stockopname', 'read')) { die('Akses ditolak'); }
require_once __DIR__ . '/../../include/koneksi.php';
include_once __DIR__ . '/../../include/cek_login.php';
$title = 'Stock Opname - Inventarisasi Peralatan';
$namaPegawai = isset($_SESSION['nama_pegawai']) ? $_SESSION['nama_pegawai'] : '';
$search = trim($_GET['search'] ?? '');
$filter_pegawai = $_GET['pegawai'] ?? '';
$filter_barang = $_GET['barang'] ?? '';
$where = [];
if ($search) {
    $searchSql = $conn->real_escape_string($search);
    $where[] = "(nama_barang LIKE '%$searchSql%' OR kondisi_fisik LIKE '%$searchSql%' OR nama_pegawai LIKE '%$searchSql%')";
}
if ($filter_pegawai) {
    $where[] = "kode_pegawai = '".$conn->real_escape_string($filter_pegawai)."'";
}
if ($filter_barang) {
    $where[] = "kode_barang = '".$conn->real_escape_string($filter_barang)."'";
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$perPage = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$total = $conn->query("SELECT COUNT(*) as jml FROM stock_opname $whereSql")->fetch_assoc()['jml'];
$totalPages = ceil($total / $perPage);
$q = $conn->query("SELECT * FROM stock_opname $whereSql ORDER BY tanggal_opname DESC, kode_opname DESC LIMIT $perPage OFFSET $offset");
$pegawaiList = $conn->query("SELECT kode_pegawai, nama_pegawai FROM pegawai WHERE aktif=1 ORDER BY nama_pegawai");
$barangList = $conn->query("SELECT kode_barang, nama_barang FROM barang ORDER BY nama_barang");

// Notifikasi alert jika ada parameter msg di URL
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    $alert = '';
    if ($msg === 'sukses_tambah') {
        $alert = '<div class="alert alert-success">Data berhasil ditambahkan.</div>';
    } elseif ($msg === 'sukses_edit') {
        $alert = '<div class="alert alert-success">Data berhasil diupdate.</div>';
    } elseif ($msg === 'sukses_hapus') {
        $alert = '<div class="alert alert-success">Data berhasil dihapus.</div>';
    }
    if ($alert) echo $alert;
}

$canEdit = cek_akses($role, 'stockopname', 'update');
$canDelete = cek_akses($role, 'stockopname', 'delete');
$canCreate = cek_akses($role, 'stockopname', 'create');
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
    <div class="page-title">Stock Opname</div>
    <div class="desc">Tabel hasil pencocokan barang fisik dan sistem (jumlah, selisih, kondisi).</div>
    <?php if ($canCreate): ?>
        <a href="tambah.php" class="add-btn">+ Tambah Stock Opname</a>
    <?php endif; ?>
    <form class="filter-bar" method="get" action="" style="margin-bottom:24px;gap:0;align-items:flex-end;background:#e3f0ff;padding:18px 24px 12px 24px;border-radius:12px;box-shadow:0 2px 12px #0E5C7110;display:flex;flex-wrap:wrap;">
        <div style="display:flex;flex-direction:column;margin-right:24px;min-width:200px;">
            <label for="search" style="font-size:1rem;color:#223468;font-weight:700;margin-bottom:6px;letter-spacing:0.2px;">🔍 Cari Barang/Pegawai</label>
            <input type="text" id="search" name="search" placeholder="Nama/Kondisi/Pegawai..." value="<?= htmlspecialchars($search) ?>" style="padding:10px 14px;border-radius:8px;border:1.5px solid #bcd;font-size:1.05rem;outline:none;transition:border 0.2s;">
        </div>
        <div style="display:flex;flex-direction:column;margin-right:24px;min-width:180px;">
            <label for="barang" style="font-size:1rem;color:#223468;font-weight:700;margin-bottom:6px;letter-spacing:0.2px;">📦 Barang</label>
            <select id="barang" name="barang" style="padding:10px 14px;border-radius:8px;border:1.5px solid #bcd;font-size:1.05rem;outline:none;transition:border 0.2s;">
                <option value="">Semua Barang</option>
                <?php $barangList->data_seek(0); while($b = $barangList->fetch_assoc()): ?>
                    <option value="<?= $b['kode_barang'] ?>" <?= $filter_barang==$b['kode_barang']?'selected':'' ?>><?= htmlspecialchars($b['nama_barang']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div style="display:flex;flex-direction:column;margin-right:24px;min-width:180px;">
            <label for="pegawai" style="font-size:1rem;color:#223468;font-weight:700;margin-bottom:6px;letter-spacing:0.2px;">👤 Pegawai</label>
            <select id="pegawai" name="pegawai" style="padding:10px 14px;border-radius:8px;border:1.5px solid #bcd;font-size:1.05rem;outline:none;transition:border 0.2s;">
                <option value="">Semua Pegawai</option>
                <?php $pegawaiList->data_seek(0); while($p = $pegawaiList->fetch_assoc()): ?>
                    <option value="<?= $p['kode_pegawai'] ?>" <?= $filter_pegawai==$p['kode_pegawai']?'selected':'' ?>><?= htmlspecialchars($p['nama_pegawai']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" style="height:44px;display:flex;align-items:center;gap:10px;background:#0E5C71;color:#fff;border:none;border-radius:8px;padding:0 28px;font-weight:800;font-size:1.08rem;box-shadow:0 2px 8px #0E5C7120;transition:background 0.2s;letter-spacing:0.5px;">
            <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='none' viewBox='0 0 24 24'><path stroke='#fff' stroke-width='2' d='M21 21l-4.35-4.35m2.02-5.17a7.19 7.19 0 11-14.38 0 7.19 7.19 0 0114.38 0z'/></svg>
            Filter
        </button>
    </form>
    <div style="overflow-x:auto;">
    <table class="invent-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Opname</th>
                <th>Nama Barang</th>
                <th>Jumlah Sistem</th>
                <th>Jumlah Fisik</th>
                <th>Selisih</th>
                <th>Kondisi</th>
                <th>Pegawai</th>
                <th>Tanggal</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($q->num_rows == 0): ?>
            <tr><td colspan="11" style="text-align:center;color:#888;">Tidak ada data.</td></tr>
        <?php endif; $no = $offset+1; while ($row = $q->fetch_assoc()): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['kode_opname']) ?></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= htmlspecialchars($row['jumlah_sistem']) ?></td>
                <td><?= htmlspecialchars($row['jumlah_fisik']) ?></td>
                <td><?= htmlspecialchars($row['selisih']) ?></td>
                <td><?= htmlspecialchars($row['kondisi_fisik']) ?></td>
                <td><?= htmlspecialchars($row['nama_pegawai']) ?></td>
                <td><?= htmlspecialchars($row['tanggal_opname']) ?></td>
                <td><?= htmlspecialchars($row['catatan']) ?></td>
                <td>
                    <div style="display:flex;gap:8px;justify-content:center;align-items:center;">
                    <?php if ($canEdit): ?>
                        <a href="edit.php?kode=<?= urlencode($row['kode_opname']) ?>" class="action-btn action-edit">Edit</a>
                    <?php else: ?>
                        <a href="#" class="action-btn action-edit" onclick="aksesDitolakSwal();return false;">Edit</a>
                    <?php endif; ?>
                    <?php if ($canDelete): ?>
                        <a href="hapus.php?kode=<?= urlencode($row['kode_opname']) ?>" class="action-btn action-del" onclick="return confirm('Yakin hapus data?')">Hapus</a>
                    <?php else: ?>
                        <a href="#" class="action-btn action-del" onclick="aksesDitolakSwal();return false;">Hapus</a>
                    <?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    </div>
    <div class="pagination">
        <?php for($i=1;$i<=$totalPages;$i++): ?>
            <?php if($i==$page): ?><span class="active"><?= $i ?></span><?php else: ?>
                <a href="?<?= http_build_query(array_merge($_GET,["page"=>$i])) ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
</div>
<?php include_once __DIR__ . '/../../include/sweetalert.php'; ?>
<script src="../../include/akses_alert.js"></script>
</body>
</html>
