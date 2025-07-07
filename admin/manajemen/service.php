<?php
include_once __DIR__ . '/../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/Sidebar.php';
require_once __DIR__ . '/../../include/koneksi.php';

$title = 'Manajemen Service Barang/Peralatan';

// Ambil barang rusak ringan/berat untuk dropdown
$barangRusak = $conn->query("SELECT kode_barang, nama_barang FROM barang WHERE kondisi IN ('rusak_ringan','rusak_berat') ORDER BY nama_barang ASC");
$barangOptions = [];
while($b = $barangRusak->fetch_assoc()) {
    $barangOptions[] = $b;
}

// Handle CRUD actions (add, edit, delete)
$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';

// Add Service
if ($action == 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_barang = $_POST['kode_barang'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_service = $_POST['tanggal_service'];
    $biaya = $_POST['biaya'];
    $status = $_POST['status'];
    $query = "INSERT INTO service (kode_barang, deskripsi, tanggal_service, biaya, status) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssds', $kode_barang, $deskripsi, $tanggal_service, $biaya, $status);
    $stmt->execute();
    header('Location: service.php?success=add');
    exit;
}
// Edit Service
if ($action == 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
    $kode_barang = $_POST['kode_barang'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_service = $_POST['tanggal_service'];
    $biaya = $_POST['biaya'];
    $status = $_POST['status'];
    $query = "UPDATE service SET kode_barang=?, deskripsi=?, tanggal_service=?, biaya=?, status=? WHERE id_service=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssdsd', $kode_barang, $deskripsi, $tanggal_service, $biaya, $status, $id);
    $stmt->execute();
    header('Location: service.php?success=edit');
    exit;
}
// Delete Service
if ($action == 'delete' && $id) {
    $conn->query("DELETE FROM service WHERE id_service='$id'");
    header('Location: service.php?success=delete');
    exit;
}
// Fetch for edit
$editData = null;
if ($action == 'edit' && $id) {
    $res = $conn->query("SELECT * FROM service WHERE id_service='$id'");
    $editData = $res->fetch_assoc();
}
// Fetch all service data
$services = $conn->query("SELECT s.*, b.nama_barang FROM service s LEFT JOIN barang b ON s.kode_barang=b.kode_barang ORDER BY tanggal_service DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../style.css">
    <style>
        body { background: #f6f8fa; }
        .main-content { padding: 32px 0 32px 0; max-width: 1100px; margin: 0 auto; }
        .card { border-radius: 14px; box-shadow: 0 2px 12px #0001; border: none; }
        .card-header { background: #f8fafc; border-bottom: 1px solid #e5e7eb; font-weight: 600; font-size: 1.15rem; }
        .form-label { font-weight: 500; }
        .form-control, .form-select { border-radius: 8px; }
        .btn { border-radius: 8px; }
        .table { margin-bottom: 0; }
        .table th, .table td { vertical-align: middle !important; white-space: nowrap; }
        .table-hover tbody tr:hover { background: #f0f4f8; }
        .badge { font-size: .98rem; padding: .45em .8em; border-radius: 8px; }
        .table-responsive { border-radius: 12px; }
        @media (max-width: 768px) {
            .main-content { padding: 16px 0; }
            .card { padding: 0; }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include_once __DIR__ . '/../../include/Sidebar.php'; ?>
<div class="main-content">
    <div class="row g-4 align-items-stretch">
        <div class="col-lg-5 col-md-6">
            <div class="card mb-4 service-form">
                <div class="card-header">
                    <?= $editData ? 'Edit Service Barang' : 'Tambah Service Barang' ?>
                </div>
                <div class="card-body">
                    <form method="post" action="service.php?action=<?= $editData ? 'edit&id=' . $editData['id_service'] : 'add' ?>">
                        <div class="mb-3">
                            <label class="form-label">Barang Rusak</label>
                            <select name="kode_barang" class="form-select" required>
                                <option value="">-- Pilih Barang Rusak --</option>
                                <?php foreach($barangOptions as $barang): ?>
                                    <option value="<?= htmlspecialchars($barang['kode_barang']) ?>" <?= (isset($editData['kode_barang']) && $editData['kode_barang'] == $barang['kode_barang']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($barang['kode_barang']) ?> - <?= htmlspecialchars($barang['nama_barang']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Service</label>
                            <input type="text" name="deskripsi" class="form-control" required placeholder="Deskripsi service" value="<?= $editData['deskripsi'] ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Service</label>
                            <input type="date" name="tanggal_service" class="form-control" required value="<?= $editData['tanggal_service'] ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Biaya Service (Rp)</label>
                            <input type="number" name="biaya" class="form-control" min="0" step="0.01" required placeholder="0" value="<?= $editData['biaya'] ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="proses" <?= (isset($editData['status']) && $editData['status']=='proses') ? 'selected' : '' ?>>Proses</option>
                                <option value="selesai" <?= (isset($editData['status']) && $editData['status']=='selesai') ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">Simpan</button>
                            <?php if($editData): ?>
                                <a href="service.php" class="btn btn-secondary">Batal</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-md-6">
            <div class="card service-table">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Service</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal</th>
                                    <th>Biaya</th>
                                    <th>Status</th>
                                    <th style="min-width:110px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $services->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['kode_barang']) ?></td>
                                    <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($row['tanggal_service'])) ?></td>
                                    <td align="right">Rp <?= number_format($row['biaya'],0,',','.') ?></td>
                                    <td>
                                        <?php if($row['status']=='proses'): ?>
                                            <span class="badge bg-warning text-dark">Proses</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Selesai</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="service.php?action=edit&id=<?= $row['id_service'] ?>" class="btn btn-sm btn-info me-1">Edit</a>
                                        <button class="btn btn-sm btn-danger" onclick="return hapusService(<?= $row['id_service'] ?>)">Hapus</button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function hapusService(id) {
    Swal.fire({
        title: 'Hapus Data?',
        text: 'Data service akan dihapus secara permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = 'service.php?action=delete&id=' + id;
        }
    });
    return false;
}
// SweetAlert for success
<?php if(isset($_GET['success'])): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '<?php if($_GET['success']=="add") echo "Data service berhasil ditambah."; if($_GET['success']=="edit") echo "Data service berhasil diubah."; if($_GET['success']=="delete") echo "Data service berhasil dihapus."; ?>',
        timer: 1800,
        showConfirmButton: false
    });
<?php endif; ?>
</script>
</body>
</html>
