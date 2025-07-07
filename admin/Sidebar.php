<?php
// Sidebar.php
// Sidebar reusable untuk seluruh halaman admin
?>
<style>
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
    @media (max-width: 900px) { .sidebar { position: static; width: 100%; height: auto; flex-direction: row; align-items: flex-start; justify-content: flex-start; box-shadow: 0 2px 16px #0E5C7120; } }
</style>
<aside class="sidebar">
    <div class="sidebar-header">Dashboard Admin</div>
    <div class="sidebar-logo">
        <img src="<?= strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? '../../assets/images/logo.png' : '../assets/images/logo.png' ?>" alt="Logo Inventarisasi">
    </div>
    <nav>
        <a href="<?= strpos($_SERVER['REQUEST_URI'], '/admin/index.php') !== false ? '#' : (strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? '../index.php' : '../admin/index.php') ?>" class="<?= strpos($_SERVER['REQUEST_URI'], 'index.php') && !preg_match('/(pegawai|kategori|barang|stockopname|laporan)/', $_SERVER['REQUEST_URI']) ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3m-4 0h4"/></svg> Home
        </a>
        <a href="<?= strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? '../pegawai/index.php' : '../admin/pegawai/index.php' ?>" class="<?= strpos($_SERVER['REQUEST_URI'], 'pegawai') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a7.5 7.5 0 0113 0"/></svg> Data Pegawai
        </a>
        <a href="<?= strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? '../kategori/index.php' : '../admin/kategori/index.php' ?>" class="<?= strpos($_SERVER['REQUEST_URI'], 'kategori') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg> Kategori Barang
        </a>
        <a href="<?= strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? '../barang/index.php' : '../admin/barang/index.php' ?>" class="<?= strpos($_SERVER['REQUEST_URI'], 'barang') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M16 3v4M8 3v4"/></svg> Data Barang
        </a>
        <a href="<?= strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? '../stockopname/index.php' : '../admin/stockopname/index.php' ?>" class="<?= strpos($_SERVER['REQUEST_URI'], 'stockopname') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 3h18v4H3z"/><rect x="3" y="7" width="18" height="13" rx="2"/></svg> Stock Opname
        </a>
        <a href="<?= strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? '../laporan/index.php' : '../admin/laporan/index.php' ?>" class="<?= strpos($_SERVER['REQUEST_URI'], 'laporan') !== false ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2"/><rect x="3" y="3" width="18" height="18" rx="2"/></svg> Laporan Inventaris
        </a>
        <a href="<?= strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? '../../login.php' : '/inventorisasi/logout.php' ?>">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7"/><path d="M3 12a9 9 0 0118 0 9 9 0 01-18 0z"/></svg> Logout
        </a>
    </nav>
    <div class="sidebar-footer">&copy; <?= date('Y') ?> Inventarisasi Peralatan</div>
</aside>
