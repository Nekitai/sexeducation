<?php
@session_start();
$currentPage = basename($_SERVER['PHP_SELF']);  

// Redirect ke halaman admin jika login sebagai admin
if (isset($_SESSION['login']) && $_SESSION['login'] === true && $_SESSION['username'] === 'admin' && $currentPage !== 'admin/index.php') {
    header("Location: admin/index.php");
    exit();
}
include 'partial/modal/modal.php';
?>
<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center me-auto me-xl-0">
            <h1 class="sitename">Sex Education</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="index.php" class="<?= $currentPage == 'index.php' ? 'active' : '' ?>">Home</a></li>
                <li><a href="artikel.php" class="<?= $currentPage == 'artikel.php' ? 'active' : '' ?>">Artikel</a></li>
                <li><a href="vidio.php" class="<?= $currentPage == 'vidio.php' ? 'active' : '' ?>">Education</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
            <!-- Tampilkan dropdown untuk pengguna yang login -->
            <div class="dropdown">
                <button class="btn-getstarted dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <?= htmlspecialchars($_SESSION['username']); ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    <?php if ($_SESSION['username'] !== 'admin'): ?>
                        <li><a class="dropdown-item" href="template/edit-profile.php">Ganti Kata Sandi/Username</a></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item" href="action/logout.php">Logout</a></li>
                </ul>
            </div>
        <?php else: ?>
            <!-- Tampilkan tombol login/daftar jika belum login -->
            <a class="btn-getstarted" data-bs-toggle="modal" data-bs-target="#loginModal">Login/Daftar</a>
        <?php endif; ?>
    </div>
</header>
<script src="assets/js/main.js"></script>
