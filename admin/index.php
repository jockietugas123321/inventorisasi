<?php
include_once __DIR__ . '/../include/cek_login.php';
include_once __DIR__ . '/../include/koneksi.php';
$title = 'Dashboard Admin - Inventarisasi Peralatan';
$namaPegawai = isset($_SESSION['nama_pegawai']) ? $_SESSION['nama_pegawai'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Query ringkasan aset
$qTotal = $conn->query("SELECT COUNT(*) as total FROM barang");
$totalBarang = $qTotal ? (int)$qTotal->fetch_assoc()['total'] : 0;
$qBaik = $conn->query("SELECT COUNT(*) as baik FROM barang WHERE kondisi='baik'");
$totalBaik = $qBaik ? (int)$qBaik->fetch_assoc()['baik'] : 0;
$qRusakRingan = $conn->query("SELECT COUNT(*) as ringan FROM barang WHERE kondisi='rusak_ringan'");
$totalRingan = $qRusakRingan ? (int)$qRusakRingan->fetch_assoc()['ringan'] : 0;
$qRusakBerat = $conn->query("SELECT COUNT(*) as berat FROM barang WHERE kondisi='rusak_berat'");
$totalBerat = $qRusakBerat ? (int)$qRusakBerat->fetch_assoc()['berat'] : 0;
$qNilai = $conn->query("SELECT SUM(nilai_awal) as total_nilai FROM barang");
$totalNilai = $qNilai ? (float)$qNilai->fetch_assoc()['total_nilai'] : 0;

// Total barang rusak
$totalRusak = $totalRingan + $totalBerat;

// Data untuk grafik
$grafikData = [
    'Baik' => $totalBaik,
    'Rusak Ringan' => $totalRingan,
    'Rusak Berat' => $totalBerat
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="../../style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { margin: 0; background: linear-gradient(135deg, #e3f0ff 0%, #fff 100%); font-family: 'Segoe UI', 'Roboto', Arial, sans-serif; }
        .admin-layout { min-height: 100vh; }
        .admin-content {
            margin-left: 250px;
            padding: 56px 5vw 40px 5vw;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
            box-sizing: border-box;
            max-width: 100vw;
        }
        .dashboard-header {
            width: 100%;
            max-width: 1200px;
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin-bottom: 32px;
            align-items: center;
        }
        .dashboard-title {
            font-size: 2.2rem;
            font-weight: 900;
            color: #223468;
            letter-spacing: 0.5px;
            margin-bottom: 0;
        }
        .dashboard-welcome {
            font-size: 1.13rem;
            color: #0E5C71;
            font-weight: 600;
            margin-left: auto;
        }
        .dashboard-cards {
            width: 100%;
            max-width: 1200px;
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            margin-bottom: 32px;
            justify-content: center;
        }
        .dashboard-card {
            background: linear-gradient(120deg, #fff 60%, #e3f0ff 100%);
            border-radius: 18px;
            box-shadow: 0 2px 16px #0E5C7110;
            padding: 32px 36px 28px 36px;
            min-width: 220px;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            position: relative;
            overflow: hidden;
            flex: 1 1 220px;
            max-width: 270px;
        }
        .dashboard-card .card-label {
            font-size: 1.08rem;
            color: #0E5C71;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .dashboard-card .card-value {
            font-size: 2.1rem;
            font-weight: 900;
            color: #223468;
            margin-bottom: 0;
        }
        .dashboard-card .card-icon {
            position: absolute;
            right: 18px;
            top: 18px;
            font-size: 2.5rem;
            color: #e3f0ff;
            opacity: 0.7;
        }
        .dashboard-section {
            width: 100%;
            max-width: 900px;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 16px #0E5C7110;
            padding: 32px 36px;
            margin-bottom: 32px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .dashboard-section-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #223468;
            margin-bottom: 18px;
            text-align: center;
        }
        .dashboard-chart-container {
            width: 100%;
            max-width: 700px;
            margin: 0 auto;
        }
        @media (max-width: 1200px) {
            .admin-content, .dashboard-header, .dashboard-cards, .dashboard-section { max-width: 98vw; }
        }
        @media (max-width: 900px) {
            .admin-content { margin-left: 0; padding: 24px 2vw; }
            .dashboard-cards { flex-direction: column; gap: 16px; }
            .dashboard-section { padding: 18px 8px; }
        }
        @media (max-width: 600px) {
            .dashboard-card { padding: 18px 10px; min-width: 140px; max-width: 100vw; }
            .dashboard-section { padding: 8px 2px; }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php include_once __DIR__ . '/../include/Sidebar.php'; ?>
        <main class="admin-content">
            <div class="dashboard-header">
                <div class="dashboard-title">Dashboard Inventarisasi</div>
                <?php if ($namaPegawai): ?>
                <div class="dashboard-welcome">Selamat datang, <?= htmlspecialchars($namaPegawai) ?>!</div>
                <?php endif; ?>
            </div>
            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <span class="card-label">Total Barang</span>
                    <span class="card-value"><?= number_format($totalBarang) ?></span>
                    <span class="card-icon">📦</span>
                </div>
                <div class="dashboard-card">
                    <span class="card-label">Nilai Aset</span>
                    <span class="card-value">Rp <?= number_format($totalNilai,0,',','.') ?></span>
                    <span class="card-icon">💰</span>
                </div>
                <div class="dashboard-card">
                    <span class="card-label">Barang Baik</span>
                    <span class="card-value"><?= number_format($totalBaik) ?></span>
                    <span class="card-icon">✅</span>
                </div>
                <div class="dashboard-card">
                    <span class="card-label">Barang Rusak</span>
                    <span class="card-value"><?= number_format($totalRusak) ?></span>
                    <span class="card-icon">⚠️</span>
                </div>
            </div>
            <div class="dashboard-section">
                <div class="dashboard-section-title">Distribusi Kondisi Barang</div>
                <div class="dashboard-chart-container">
                    <canvas id="kondisiChart"></canvas>
                </div>
            </div>
            <div class="dashboard-section">
                <div class="dashboard-section-title">Statistik Barang per Kategori</div>
                <div class="dashboard-chart-container" style="max-width:700px;">
                    <canvas id="kategoriBarChart"></canvas>
                </div>
            </div>
        </main>
    </div>
    <script>
    const ctx = document.getElementById('kondisiChart').getContext('2d');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Baik', 'Rusak Ringan', 'Rusak Berat'],
            datasets: [{
                data: [<?= $totalBaik ?>, <?= $totalRusakRingan ?>, <?= $totalRusakBerat ?>],
                backgroundColor: ['#4fc3f7', '#ffd54f', '#e57373'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 16 } } },
                title: { display: false }
            }
        }
    });

    // Bar Chart Kategori
    const ctxBar = document.getElementById('kategoriBarChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: <?= json_encode($kategoriLabel) ?>,
            datasets: [{
                label: 'Jumlah Barang',
                data: <?= json_encode($kategoriData) ?>,
                backgroundColor: '#4fc3f7',
                borderRadius: 8,
                maxBarThickness: 48
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: false }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 15 } } },
                y: { beginAtZero: true, ticks: { font: { size: 15 } } }
            }
        }
    });
    </script>
</body>
</html>
