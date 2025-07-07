<?php
session_start();
require_once __DIR__ . '/../../include/koneksi.php';
include '../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
$role = $_SESSION['role'] ?? '';
if (!cek_akses($role, 'kategori', 'read')) { die('Akses ditolak'); }
$title = 'Kategori Barang - Inventarisasi Peralatan';
$namaPegawai = isset($_SESSION['nama_pegawai']) ? $_SESSION['nama_pegawai'] : '';
$search = trim($_GET['search'] ?? '');
$where = $search ? "WHERE kode_kategori LIKE '%".$conn->real_escape_string($search)."%' OR nama_kategori LIKE '%".$conn->real_escape_string($search)."%' OR deskripsi LIKE '%".$conn->real_escape_string($search)."%'" : '';
$perPage = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$total = $conn->query("SELECT COUNT(*) as jml FROM kategori $where")->fetch_assoc()['jml'];
$totalPages = ceil($total / $perPage);
$q = $conn->query("SELECT * FROM kategori $where ORDER BY kode_kategori ASC LIMIT $perPage OFFSET $offset");

// Notifikasi SweetAlert jika ada parameter msg di URL
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    if ($msg === 'sukses_tambah') {
        echo "<script>Swal.fire('Sukses', 'Data berhasil ditambahkan.', 'success');</script>";
    } elseif ($msg === 'sukses_edit') {
        echo "<script>Swal.fire('Sukses', 'Data berhasil diupdate.', 'success');</script>";
    } elseif ($msg === 'sukses_hapus') {
        echo "<script>Swal.fire('Sukses', 'Data berhasil dihapus.', 'success');</script>";
    }
}

$canEdit = cek_akses($role, 'kategori', 'update');
$canDelete = cek_akses($role, 'kategori', 'delete');
$canCreate = cek_akses($role, 'kategori', 'create');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="../../style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { margin: 0; background: linear-gradient(135deg, #e3f0ff 0%, #fff 100%); font-family: 'Segoe UI', 'Roboto', Arial, sans-serif; }
        .admin-layout { min-height: 100vh; }
        .sidebar {
            width: 250px;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            height: 100vh;
            z-index: 10;
            background: rgba(14, 92, 113, 0.7);
            backdrop-filter: blur(12px);
            box-shadow: 2px 0 24px #0E5C7130;
            display: flex;
            flex-direction: column;
            border-right: 1.5px solid #e3f0ff55;
            transition: background 0.3s;
            overflow-y: auto;
        }
        .sidebar-header { padding: 36px 0 18px 0; text-align: center; font-size: 1.35rem; font-weight: 900; letter-spacing: 1.5px; color: #fff; text-shadow: 0 2px 8px #0E5C7130; border-bottom: 1.5px solid #ffffff22; }
        .sidebar-logo { text-align: center; margin: 24px 0 18px 0; }
        .sidebar-logo img { height: 54px; background: #fff; border-radius: 16px; padding: 8px 18px; box-shadow: 0 2px 12px #0E5C7120; }
        .sidebar nav { display: flex; flex-direction: column; gap: 6px; padding: 18px 0 0 0; }
        .sidebar nav a { color: #fff; text-decoration: none; font-weight: 600; padding: 13px 36px 13px 24px; border-radius: 0 24px 24px 0; font-size: 1.08rem; display: flex; align-items: center; gap: 14px; transition: background 0.2s, color 0.2s, box-shadow 0.2s; position: relative; }
        .sidebar nav a svg { width: 20px; height: 20px; opacity: 0.85; }
        .sidebar nav a.active, .sidebar nav a:hover { background: rgba(211, 194, 136, 0.95); color: #223468; box-shadow: 0 2px 12px #D3C28833; }
        .sidebar nav a.active::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 5px; border-radius: 0 8px 8px 0; background: #0E5C71; }
        .sidebar .sidebar-footer { margin-top: auto; text-align: center; padding: 18px 0 12px 0; font-size: 0.98rem; color: #e3e3e3; letter-spacing: 0.5px; }
        .admin-content {
            margin-left: 250px;
            padding: 56px 40px 40px 40px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
            min-height: 100vh;
        }
        .page-title { font-size: 2rem; font-weight: 900; color: #223468; margin-bottom: 18px; letter-spacing: 0.5px; }
        .desc { color: #0E5C71; margin-bottom: 24px; font-size: 1.13rem; font-weight: 500; }
        .invent-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 16px #0E5C7110; }
        .invent-table th, .invent-table td { padding: 16px 20px; border-bottom: 1px solid #e3e3e3; text-align: left; }
        .invent-table th { background: #e3f0ff; color: #223468; font-weight: 800; font-size: 1.08rem; }
        .invent-table tr:last-child td { border-bottom: none; }
        .filter-bar { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 18px; align-items: center; }
        .filter-bar input { padding: 8px 12px; border-radius: 8px; border: 1px solid #bcd; font-size: 1rem; }
        .filter-bar button { background: #0E5C71; color: #fff; border: none; border-radius: 8px; padding: 8px 18px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .filter-bar button:hover { background: #223468; }
        .action-btn { display: inline-block; margin: 0 2px; padding: 6px 14px; border-radius: 7px; font-weight: 600; border: none; cursor: pointer; transition: background 0.2s, color 0.2s; }
        .action-edit { background: #e3f0ff; color: #0E5C71; }
        .action-edit:hover { background: #bcd; color: #223468; }
        .action-del { background: #ffe3e3; color: #c00; }
        .action-del:hover { background: #ffbdbd; color: #900; }
        .pagination { margin: 24px 0 0 0; display: flex; gap: 6px; flex-wrap: wrap; }
        .pagination a, .pagination span { padding: 7px 14px; border-radius: 7px; background: #e3f0ff; color: #223468; text-decoration: none; font-weight: 600; }
        .pagination .active { background: #0E5C71; color: #fff; }
        @media (max-width: 900px) { .sidebar { position: static; width: 100%; height: auto; flex-direction: row; align-items: flex-start; justify-content: flex-start; box-shadow: 0 2px 16px #0E5C7120; } .admin-content { margin-left: 0; padding: 24px 8px; } }

        /* ...CSS modern dan tanpa garis bawah tombol... */
        .action-btn, .filter-bar button, .btn-main, .btn-cancel, .btn, button, input[type=submit], input[type=button] {
            border: none !important;
            outline: none !important;
            box-shadow: none;
            text-decoration: none !important;
        }
        .action-btn:link, .action-btn:visited, .action-btn:active, .action-btn:focus, .action-btn:hover {
            text-decoration: none !important;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php include_once __DIR__ . '/../../include/Sidebar.php'; ?>
        <main class="admin-content">
            <?php if ($namaPegawai): ?>
                <div style="font-size:1.08rem;font-weight:600;color:#0E5C71;margin-bottom:14px;">Selamat datang, <?= htmlspecialchars($namaPegawai) ?>!</div>
            <?php endif; ?>
            <div class="page-title">Kategori Barang</div>
            <div class="desc">Tabel daftar kategori/jenis barang seperti elektronik, furnitur, dll.</div>
            <a href="tambah.php" style="margin-bottom:18px;display:inline-block;background:#0E5C71;color:#fff;padding:8px 18px;border-radius:8px;text-decoration:none;font-weight:600;">+ Tambah Kategori</a>
            <form class="filter-bar" method="get" action="">
                <input type="text" name="search" placeholder="Cari kode/nama/deskripsi..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit">Cari</button>
            </form>
            <table class="invent-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($q->num_rows == 0): ?>
                    <tr><td colspan="5" style="text-align:center;color:#888;">Tidak ada data.</td></tr>
                <?php endif; $no = $offset+1; while ($row = $q->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['kode_kategori']) ?></td>
                        <td><?= htmlspecialchars($row['nama_kategori']) ?></td>
                        <td><?= isset($row['deskripsi']) ? htmlspecialchars($row['deskripsi']) : '-' ?></td>
                        <td>
                            <?php if ($canEdit): ?>
                            <a href="edit.php?kode=<?= urlencode($row['kode_kategori']) ?>" class="action-btn action-edit">Edit</a>
                            <?php else: ?>
                            <a href="#" class="action-btn action-edit" onclick="aksesDitolakSwal();return false;">Edit</a>
                            <?php endif; ?>
                            <?php if ($canDelete): ?>
                            <a href="#" class="action-btn action-del" onclick="hapusKategoriSwal('<?= htmlspecialchars($row['kode_kategori']) ?>');return false;">Hapus</a>
                            <?php else: ?>
                            <a href="#" class="action-btn action-del" onclick="aksesDitolakSwal();return false;">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
            <div class="pagination">
                <?php for($i=1;$i<=$totalPages;$i++): ?>
                    <?php if($i==$page): ?><span class="active"><?= $i ?></span><?php else: ?>
                        <a href="?<?= http_build_query(array_merge($_GET,["page"=>$i])) ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </main>
    </div>
    <script src="../../include/akses_alert.js"></script>
    <script>
    function hapusKategoriSwal(kode) {
        Swal.fire({
            title: 'Yakin hapus data?',
            text: 'Data kategori yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'hapus.php?kode=' + encodeURIComponent(kode);
            }
        });
    }
    </script>
</body>
</html>
