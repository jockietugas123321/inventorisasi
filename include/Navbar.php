<?php
session_start();
$isLogin = isset($_SESSION['login']) && $_SESSION['login'] === true;
$namaPegawai = $_SESSION['nama_pegawai'] ?? '';
// Navbar PKP style untuk landing page, lebih rapi dan responsif
?>
<nav class="pkp-navbar">
    <div class="pkp-navbar-inner">
        <div class="pkp-logo">
            <img src="assets/images/logo.png" alt="Logo PKP" height="48" style="vertical-align:middle;">
            <span class="pkp-logo-text">Kementerian PUPR</span>
        </div>
        <input type="checkbox" id="pkp-menu-toggle" class="pkp-menu-toggle" />
        <label for="pkp-menu-toggle" class="pkp-menu-icon">
            <span></span><span></span><span></span>
        </label>
        <div class="pkp-menu-wrapper">
            <ul class="pkp-menu">
                <li><a href="/inventorisasi/index.php" class="pkp-menu-item<?= basename($_SERVER['PHP_SELF'])==='index.php'?' active':'' ?>">BERANDA</a></li>
                <?php if ($isLogin): ?>
                    <li><span class="pkp-menu-item" style="font-weight:600; color:#0E5C71;">👤 <?= htmlspecialchars($namaPegawai) ?></span></li>
                    <li><a href="/inventorisasi/logout.php" class="pkp-menu-item pkp-btn-login">LOGOUT</a></li>
                <?php else: ?>
                    <li><a href="/inventorisasi/login.php" class="pkp-menu-item pkp-btn-login">LOGIN</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
