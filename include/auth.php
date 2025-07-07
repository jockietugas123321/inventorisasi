<?php
// Fungsi pembatasan akses berdasarkan role
function cek_akses($role, $halaman, $aksi = 'read') {
    // Daftar hak akses per role
    $akses = [
        'admin' => [
            '*' => ['read','create','update','delete'] // akses penuh ke semua halaman dan aksi
        ],
        'satker' => [
            'laporan' => ['read','create','update','delete'],
            'stockopname' => ['read','create','update','delete'],
            '*' => ['read'] // selain laporan & stockopname hanya read
        ],
        'kepala_bagian' => [
            'laporan' => ['read'],
            'stockopname' => ['read'],
            '*' => ['read'] // selain laporan & stockopname hanya read
        ]
    ];
    if (!isset($akses[$role])) return false;
    // admin akses penuh
    if ($role === 'admin') return true;
    // cek akses halaman spesifik
    if (isset($akses[$role][$halaman]) && in_array($aksi, $akses[$role][$halaman])) {
        return true;
    }
    // cek akses default (hanya read selain menu utama)
    if (isset($akses[$role]['*']) && in_array($aksi, $akses[$role]['*'])) {
        return true;
    }
    return false;
}
// Cara pakai di setiap halaman:
// $role = $_SESSION['role'] ?? '';
// if (!cek_akses($role, 'laporan', 'read')) { die('Akses ditolak'); }
