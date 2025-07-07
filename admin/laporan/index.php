<?php
session_start();
require_once __DIR__ . '/../../include/koneksi.php';
include '../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
$role = $_SESSION['role'] ?? '';
$kode_pegawai = $_SESSION['kode_pegawai'] ?? '';
$namaPegawai = $_SESSION['nama_pegawai'] ?? '';
if (!cek_akses($role, 'laporan', 'read')) {
    echo "<script>Swal.fire('Akses Ditolak', 'Anda tidak memiliki hak untuk melihat data ini!', 'error').then(()=>window.location='../index.php');</script>";
    exit;
}
$title = 'Laporan Transaksi Inventarisasi';
$tab = $_GET['tab'] ?? 'barang';
$search = trim($_GET['search'] ?? '');
$status_filter = $_GET['status'] ?? '';
$tahun = $_GET['tahun'] ?? '';
$bulan = $_GET['bulan'] ?? '';

function tabActive($t, $tab) { return $t==$tab ? 'active' : ''; }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/fontawesome-all.min.css">
    <script src="../../assets/js/jquery.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/sweetalert2.all.min.js"></script>
    <script src="../../assets/js/script.js"></script>
    <style>
        body { background: linear-gradient(120deg, #f4f6fb 60%, #e9ecef 100%); }
        .main-content { margin-left: 250px; min-height: 100vh; padding: 2.5rem 1.5rem; transition: margin .2s; }
        @media (max-width: 991px) { .main-content { margin-left: 0; } }
        .page-title { font-size: 2.2rem; font-weight: 700; color: #2c3e50; margin-bottom: .5rem; letter-spacing: -1px; }
        .desc { color: #666; font-size: 1.13rem; margin-bottom: 1.5rem; }
        .nav-tabs { margin-bottom: 2rem; border-bottom: 2px solid #e3e6f0; }
        .nav-tabs .nav-link { font-size: 1.08rem; font-weight: 500; color: #2c3e50; border-radius: 8px 8px 0 0; margin-right: 2px; transition: background .2s; }
        .nav-tabs .nav-link.active { background: #fff; border-color: #e3e6f0 #e3e6f0 #fff; color: #007bff; box-shadow: 0 2px 8px #0001; }
        .invent-table { overflow-x: auto; background: #fff; border-radius: 18px; box-shadow: 0 4px 18px #0002; padding: 1.5rem 2rem; margin-bottom: 2rem; }
        table { min-width: 950px; font-size: 1.04rem; }
        thead.table-light th { position: sticky; top: 0; background: #f8f9fa; z-index: 2; font-weight: 600; letter-spacing: .5px; }
        .table-bordered th, .table-bordered td { border: 1px solid #e3e6f0 !important; }
        .table-hover tbody tr:hover { background: #eaf6ff; transition: background .2s; }
        .table-striped tbody tr:nth-of-type(odd) { background: #f9fbfd; }
        .filter-bar { background: #fff; border-radius: 14px; box-shadow: 0 2px 8px #0001; padding: 1.2rem 2rem; margin-bottom: 2rem; display: flex; flex-wrap: wrap; gap: 1.2rem; align-items: end; }
        .filter-bar label { font-weight: 500; color: #2c3e50; }
        .filter-bar .form-control, .filter-bar .form-select { min-width: 150px; font-size: 1.08rem; border-radius: 8px; }
        .filter-bar .btn { font-size: 1.08rem; border-radius: 8px; }
        @media (max-width: 600px) { .main-content, .filter-bar { flex-direction: column; gap: .7rem; } .main-content { padding: 0 .5rem; } }
        .avatar-circle {
            display: inline-block;
            width: 28px; height: 28px;
            background: #e3e6f0;
            color: #2c3e50;
            border-radius: 50%;
            text-align: center;
            line-height: 28px;
            font-weight: bold;
            font-size: 1rem;
        }
        .badge { font-size: .98rem; padding: .45em .8em; border-radius: 8px; letter-spacing: .5px; }
        td, th { vertical-align: middle !important; }
        .table td { white-space: nowrap; text-overflow: ellipsis; overflow: hidden; max-width: 220px; }
        .table th { background: #f8f9fa; }
        /* Action button style for consistency */
        .btn-action {
            padding: .35rem .7rem;
            font-size: .98rem;
            border-radius: 7px;
            margin-right: 3px;
            background: #f4f6fb;
            color: #007bff;
            border: 1px solid #e3e6f0;
            transition: background .2s, color .2s;
        }
        .btn-action:hover {
            background: #007bff;
            color: #fff;
        }
        .table thead th, .table tfoot th { border-top: none !important; }
        .table tfoot td { background: #f8f9fa; font-weight: 600; }
    </style>
</head>
<body>
<?php include_once __DIR__ . '/../../include/Sidebar.php'; ?>
<div class="main-content">
    <div class="page-title"><i class="fas fa-file-alt"></i> <?php echo $title; ?></div>
    <div class="desc">Laporan seluruh transaksi: Barang, Stock Opname, Mutasi, Pengadaan, dan Penyusutan. Pilih tab untuk melihat detail masing-masing laporan.</div>
    <ul class="nav nav-tabs" id="laporanTab" role="tablist">
      <li class="nav-item"><a class="nav-link <?php echo tabActive('barang',$tab); ?>" href="?tab=barang">Barang</a></li>
      <li class="nav-item"><a class="nav-link <?php echo tabActive('stockopname',$tab); ?>" href="?tab=stockopname">Stock Opname</a></li>
      <li class="nav-item"><a class="nav-link <?php echo tabActive('mutasi',$tab); ?>" href="?tab=mutasi">Mutasi</a></li>
      <li class="nav-item"><a class="nav-link <?php echo tabActive('pengadaan',$tab); ?>" href="?tab=pengadaan">Pengadaan</a></li>
      <li class="nav-item"><a class="nav-link <?php echo tabActive('penyusutan',$tab); ?>" href="?tab=penyusutan">Penyusutan</a></li>
    </ul>
    <div class="tab-content">
    <?php if($tab=='barang'): ?>
        <form class="filter-bar mb-3" method="get" action="">
            <input type="hidden" name="tab" value="barang">
            <div>
                <label for="search">Cari Barang</label>
                <input type="text" class="form-control" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Kode/Nama/Lokasi">
            </div>
            <div>
                <label for="status">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="all" <?php if($status_filter==''||$status_filter=='all')echo'selected';?>>Semua</option>
                    <option value="aktif" <?php if($status_filter=='aktif')echo'selected';?>>Aktif</option>
                    <option value="baik" <?php if($status_filter=='baik')echo'selected';?>>Baik</option>
                    <option value="rusak" <?php if($status_filter=='rusak')echo'selected';?>>Rusak</option>
                    <option value="hilang" <?php if($status_filter=='hilang')echo'selected';?>>Hilang</option>
                    <option value="dipinjam" <?php if($status_filter=='dipinjam')echo'selected';?>>Dipinjam</option>
                    <option value="siap_dilelang" <?php if($status_filter=='siap_dilelang')echo'selected';?>>Siap Dilelang</option>
                    <option value="dilelang" <?php if($status_filter=='dilelang')echo'selected';?>>Dilelang</option>
                    <option value="terjual" <?php if($status_filter=='terjual')echo'selected';?>>Terjual</option>
                    <option value="disumbangkan" <?php if($status_filter=='disumbangkan')echo'selected';?>>Disumbangkan</option>
                    <option value="didistribusikan" <?php if($status_filter=='didistribusikan')echo'selected';?>>Didistribusikan</option>
                    <option value="belum_didaftarkan" <?php if($status_filter=='belum_didaftarkan')echo'selected';?>>Belum Terdaftar</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                <a href="?tab=barang" class="btn btn-secondary"><i class="fas fa-sync"></i> Reset</a>
            </div>
        </form>
        <div class="invent-table table-responsive">
            <table class="table table-bordered table-hover table-striped align-middle mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Lokasi Penyimpanan</th>
                        <th>Status</th>
                        <th>Nilai Awal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $where = $search ? "WHERE (kode_barang LIKE '%".$conn->real_escape_string($search)."%' OR nama_barang LIKE '%".$conn->real_escape_string($search)."%' OR lokasi_penyimpanan LIKE '%".$conn->real_escape_string($search)."%')" : '';
                    if ($status_filter && $status_filter != 'all') {
                        $where .= ($where ? ' AND ' : 'WHERE ') . "status='".$conn->real_escape_string($status_filter)."'";
                    } else {
                        $where .= ($where ? ' AND ' : 'WHERE ') . "status != 'non_aktif'";
                    }
                    $result = $conn->query("SELECT * FROM barang $where ORDER BY kode_barang ASC");
                    while ($row = $result->fetch_assoc()) {
                        $status = $row['status'];
                        $badge = 'secondary';
                        if ($status == 'baik' || $status == 'aktif') $badge = 'success';
                        elseif ($status == 'rusak' || $status == 'rusak_ringan') $badge = 'warning';
                        elseif ($status == 'rusak_berat' || $status == 'hilang') $badge = 'danger';
                        elseif ($status == 'dipinjam') $badge = 'info';
                        elseif ($status == 'dilelang' || $status == 'siap_dilelang') $badge = 'primary';
                        elseif ($status == 'terjual') $badge = 'dark';
                        elseif ($status == 'disumbangkan' || $status == 'didistribusikan') $badge = 'light';
                        elseif ($status == 'belum_didaftarkan') $badge = 'secondary';
                        echo "<tr>
                            <td>".$row['kode_barang']."</td>
                            <td>".htmlspecialchars($row['nama_barang'])."</td>
                            <td>".htmlspecialchars($row['lokasi_penyimpanan'])."</td>
                            <td><span class='badge bg-".$badge." text-capitalize'>".str_replace('_',' ',$status)."</span></td>
                            <td align='right'>".number_format($row['nilai_awal'], 2, ',', '.')."</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php elseif($tab=='stockopname'): ?>
        <div class="invent-table">
            <table class="table table-bordered table-hover table-striped align-middle mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        <th>Kode Opname</th>
                        <th>Tanggal Opname</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Sistem</th>
                        <th>Jumlah Fisik</th>
                        <th>Selisih</th>
                        <th>Kondisi Fisik</th>
                        <th>Petugas</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cekTable = $conn->query("SHOW TABLES LIKE 'stock_opname'");
                    if ($cekTable && $cekTable->num_rows > 0) {
                        $result = $conn->query("SELECT * FROM stock_opname");
                        while ($row = $result->fetch_assoc()) {
                            $kode_opname = isset($row['kode_opname']) ? $row['kode_opname'] : '-';
                            $tanggal_opname = isset($row['tanggal_opname']) ? date('d-m-Y', strtotime($row['tanggal_opname'])) : '-';
                            $kode_barang = isset($row['kode_barang']) ? $row['kode_barang'] : '-';
                            $nama_barang = isset($row['nama_barang']) ? $row['nama_barang'] : '-';
                            $jumlah_sistem = isset($row['jumlah_sistem']) ? $row['jumlah_sistem'] : '0';
                            $jumlah_fisik = isset($row['jumlah_fisik']) ? $row['jumlah_fisik'] : '0';
                            $selisih = isset($row['selisih']) ? $row['selisih'] : ($jumlah_fisik - $jumlah_sistem);
                            $kondisi_fisik = isset($row['kondisi_fisik']) ? $row['kondisi_fisik'] : '-';
                            $petugas = isset($row['nama_pegawai']) ? $row['nama_pegawai'] : (isset($row['kode_pegawai']) ? $row['kode_pegawai'] : '-');
                            $catatan = isset($row['catatan']) ? $row['catatan'] : '-';
                            // Kondisi badge
                            $badge = 'secondary';
                            if ($kondisi_fisik == 'baik') $badge = 'success';
                            elseif ($kondisi_fisik == 'rusak_ringan') $badge = 'warning';
                            elseif ($kondisi_fisik == 'rusak_berat') $badge = 'danger';
                            // Selisih badge
                            $selisih_badge = ($selisih == 0) ? '<span class="badge bg-success">0</span>' : '<span class="badge bg-danger">'.$selisih.'</span>';
                            // Petugas avatar (initials)
                            $petugas_avatar = ($petugas && $petugas != '-') ? '<span class="avatar-circle me-1">'.strtoupper(substr($petugas,0,1)).'</span>' : '';
                            // Catatan tooltip
                            $catatan_display = (strlen($catatan) > 20) ? '<span data-bs-toggle="tooltip" title="'.htmlspecialchars($catatan).'">'.htmlspecialchars(substr($catatan,0,20)).'...</span>' : htmlspecialchars($catatan);
                    ?>
                            <tr>
                                <td><?php echo $kode_opname; ?></td>
                                <td><?php echo $tanggal_opname; ?></td>
                                <td><?php echo $kode_barang; ?></td>
                                <td><?php echo $nama_barang; ?></td>
                                <td align='right'><?php echo $jumlah_sistem; ?></td>
                                <td align='right'><?php echo $jumlah_fisik; ?></td>
                                <td align='center'><?php echo $selisih_badge; ?></td>
                                <td><span class='badge bg-<?php echo $badge; ?> text-capitalize'><?php echo str_replace('_',' ',$kondisi_fisik); ?></span></td>
                                <td><?php echo $petugas_avatar; ?><span><?php echo htmlspecialchars($petugas); ?></span></td>
                                <td><?php echo $catatan_display; ?></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="10" class="text-center text-danger">Tabel stock_opname tidak ditemukan di database.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <script>
        $(function () { $('[data-bs-toggle="tooltip"]').tooltip(); });
        </script>
    <?php elseif($tab=='mutasi'): ?>
        <div class="invent-table">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Lokasi Asal</th>
                        <th>Lokasi Tujuan</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM mutasi");
                    while ($row = $result->fetch_assoc()) {
                        $tanggal = isset($row['tanggal']) ? date('d-m-Y', strtotime($row['tanggal'])) : '-';
                        $status = isset($row['status']) ? $row['status'] : '-';
                        echo "<tr>
                            <td>".$tanggal."</td>
                            <td>".$row['kode_barang']."</td>
                            <td>".$row['nama_barang']."</td>
                            <td>".$row['lokasi_asal']."</td>
                            <td>".$row['lokasi_tujuan']."</td>
                            <td>".$status."</td>
                            <td>".$row['keterangan']."</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php elseif($tab=='pengadaan'): ?>
        <div class="invent-table">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total</th>
                        <th>Sumber Dana</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM pengadaan");
                    while ($row = $result->fetch_assoc()) {
                        $tanggal = isset($row['tanggal']) ? date('d-m-Y', strtotime($row['tanggal'])) : '-';
                        $jumlah = isset($row['jumlah']) ? $row['jumlah'] : '0';
                        $harga_satuan = isset($row['harga_satuan']) ? number_format($row['harga_satuan'], 2, ',', '.') : '0,00';
                        $total = isset($row['total']) ? number_format($row['total'], 2, ',', '.') : '0,00';
                        $sumber_dana = isset($row['sumber_dana']) ? $row['sumber_dana'] : '-';
                        echo "<tr>
                            <td>".$tanggal."</td>
                            <td>".$row['kode_barang']."</td>
                            <td>".$row['nama_barang']."</td>
                            <td align='right'>".$jumlah."</td>
                            <td align='right'>".$harga_satuan."</td>
                            <td align='right'>".$total."</td>
                            <td>".$sumber_dana."</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php elseif($tab=='penyusutan'): ?>
        <div class="invent-table">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Nilai Awal</th>
                        <th>Nilai Penyusutan</th>
                        <th>Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM penyusutan ORDER BY kode_barang ASC");
                    while ($row = $result->fetch_assoc()) {
                        $nilai_awal = isset($row['nilai_awal']) ? $row['nilai_awal'] : 0;
                        $nilai_penyusutan = isset($row['nilai_penyusutan']) ? $row['nilai_penyusutan'] : 0;
                        $nilai_akhir = isset($row['nilai_akhir']) ? $row['nilai_akhir'] : ($nilai_awal - $nilai_penyusutan);
                        echo "<tr>
                            <td>".$row['kode_barang']."</td>
                            <td>".$row['nama_barang']."</td>
                            <td align='right'>".number_format($nilai_awal, 2, ',', '.')."</td>
                            <td align='right'>".number_format($nilai_penyusutan, 2, ',', '.')."</td>
                            <td align='right'>".number_format($nilai_akhir, 2, ',', '.')."</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
    </div>
</div>
</body>
</html>