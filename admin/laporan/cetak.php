<?php
include '../../include/cek_login.php';
require_once __DIR__ . '/../../include/koneksi.php';
require_once __DIR__ . '/../../vendor/autoload.php';
use Mpdf\Mpdf;

// Ambil filter dari GET
$search = trim($_GET['search'] ?? '');
$filter_pegawai = intval($_GET['pegawai'] ?? 0);
$filter_periode = trim($_GET['periode'] ?? '');
$where = [];
if ($search) {
    $searchSql = $conn->real_escape_string($search);
    $where[] = "(l.periode LIKE '%$searchSql%' OR l.lokasi LIKE '%$searchSql%' OR p.nama_pegawai LIKE '%$searchSql%')";
}
if ($filter_pegawai) {
    $where[] = "l.id_pegawai = $filter_pegawai";
}
if ($filter_periode) {
    $where[] = "l.periode = '$filter_periode'";
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$q = $conn->query("SELECT l.*, p.nama_pegawai FROM laporan l LEFT JOIN pegawai p ON l.id_pegawai = p.id_pegawai $whereSql ORDER BY l.periode DESC, l.id_laporan DESC");

$html = '<h2 style="text-align:center;color:#0E5C71;">Laporan Inventaris</h2>';
$html .= '<table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse:collapse;font-size:12px;">';
$html .= '<thead><tr style="background:#e3f0ff;color:#223468;"><th>No</th><th>Periode</th><th>Tanggal Cetak</th><th>Total Barang</th><th>Baik</th><th>Rusak Ringan</th><th>Rusak Berat</th><th>Lokasi</th><th>Pegawai Bertugas</th></tr></thead><tbody>';
$no = 1;
while ($row = $q->fetch_assoc()) {
    $html .= '<tr>';
    $html .= '<td style="color:#223468;">' . $no++ . '</td>';
    $html .= '<td>' . htmlspecialchars($row['periode']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['tanggal_cetak']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['total_barang']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['barang_baik']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['barang_rusak_ringan']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['barang_rusak_berat']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['lokasi']) . '</td>';
    $html .= '<td>' . htmlspecialchars($row['nama_pegawai']) . '</td>';
    $html .= '</tr>';
}
if ($no == 1) {
    $html .= '<tr><td colspan="9" style="text-align:center;color:#888;">Tidak ada data.</td></tr>';
}
$html .= '</tbody></table>';

$mpdf = new Mpdf([
    'format' => 'A4',
    'orientation' => 'L',
    'margin_top' => 16,
    'margin_bottom' => 16,
    'margin_left' => 10,
    'margin_right' => 10
]);
$mpdf->SetTitle('Laporan Inventaris');
$mpdf->WriteHTML($html);
$mpdf->Output('laporan-inventaris.pdf', \Mpdf\Output\Destination::INLINE);
exit;
