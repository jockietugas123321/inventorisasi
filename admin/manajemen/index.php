<?php
include_once __DIR__ . '/../../include/cek_login.php';
include_once __DIR__ . '/../../include/auth.php';
include_once __DIR__ . '/../../include/Sidebar.php';
include_once __DIR__ . '/../../include/sweetalert.php';
require_once __DIR__ . '/../../include/koneksi.php';

$title = 'Manajemen Aset: Penyusutan, Pengadaan, Mutasi, Laporan Rekap';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="../../style.css">
    <style>
        .manajemen-menu { display: flex; gap: 32px; justify-content: center; margin-top: 48px; flex-wrap: wrap; }
        .manajemen-card { background: #fff; border-radius: 12px; box-shadow: 0 2px 8px #0001; padding: 32px 40px; min-width: 220px; text-align: center; transition: box-shadow .2s; }
        .manajemen-card:hover { box-shadow: 0 4px 16px #0002; }
        .manajemen-card h3 { margin: 0 0 12px 0; }
        .manajemen-card a { display: inline-block; margin-top: 12px; color: #1976d2; text-decoration: none; font-weight: bold; }
        .main-content { margin-left: 240px; padding: 32px 24px; }
        @media (max-width: 900px) { .main-content { margin-left: 0; padding: 16px 4vw; } }
    </style>
</head>
<body>
<?php include_once __DIR__ . '/../../include/Sidebar.php'; ?>
<div class="main-content">
    <h1 style="text-align:center;">Manajemen Aset</h1>
    <div class="manajemen-menu">
        <div class="manajemen-card">
            <h3>Penyusutan</h3>
            <div>Mengelola perhitungan dan riwayat penyusutan aset.</div>
            <a href="penyusutan.php">Kelola Penyusutan</a>
        </div>
        <div class="manajemen-card">
            <h3>Pengadaan</h3>
            <div>Catat dan kelola proses pengadaan barang/aset baru.</div>
            <a href="pengadaan.php">Kelola Pengadaan</a>
        </div>
        <div class="manajemen-card">
            <h3>Mutasi</h3>
            <div>Kelola mutasi/pemindahan aset antar lokasi/pegawai.</div>
            <a href="mutasi.php">Kelola Mutasi</a>
        </div>
        <div class="manajemen-card">
            <h3>Service</h3>
            <div>Kelola transaksi service untuk barang/peralatan yang kondisinya tidak baik.</div>
            <a href="service.php">Kelola Service</a>
        </div>
    </div>
</div>
</body>
</html>
