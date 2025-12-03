<?php
// Deteksi base path
$current_file = $_SERVER['PHP_SELF'];
if (strpos($current_file, '/pages/') !== false) {
    $base_url = '../ukk_kasir_fauzi';
    $dashboard_url = $base_url . '../dashboard.php';
    $produk_url = $base_url . '../pages/produk/index.php';
    $pelanggan_url = $base_url . '../pages/pelanggan/index.php';
    $transaksi_url = $base_url . '../pages/transaksi/index.php';
    $laporan_url = $base_url . '../pages/laporan/index.php';
    $logout_url = $base_url . '../logout.php';
} else {
    $base_url = '../ukk_kasir_fauzi';
    $dashboard_url = $base_url . '../dashboard.php';
    $produk_url = $base_url . '../pages/produk/index.php';
    $pelanggan_url = $base_url . '../pages/pelanggan/index.php';
    $transaksi_url = $base_url . '../pages/transaksi/index.php';
    $laporan_url = $base_url . '../pages/laporan/index.php';
    $logout_url = $base_url . '../logout.php';
}

// Deteksi halaman aktif
$current_page = basename($_SERVER['PHP_SELF']);
$current_folder = basename(dirname($_SERVER['PHP_SELF']));
?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= $dashboard_url ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-cash-register"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Kasir UKK</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard -->
    <li class="nav-item <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= $dashboard_url ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Menu Utama</div>

    <!-- Produk -->
    <li class="nav-item <?= ($current_folder == '../produk/index.php') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= $produk_url ?>">
            <i class="fas fa-fw fa-box"></i>
            <span>Data Produk</span>
        </a>
    </li>

    <!-- Pelanggan -->
    <li class="nav-item <?= ($current_folder == '../pelanggan/index.php') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= $pelanggan_url ?>">
            <i class="fas fa-fw fa-users"></i>
            <span>Data Pelanggan</span>
        </a>
    </li>

    <!-- Transaksi -->
    <li class="nav-item <?= ($current_folder == '../transaksi/index.php') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= $transaksi_url ?>">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Transaksi</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Laporan</div>

    <!-- Laporan -->
    <li class="nav-item <?= ($current_folder == '../laporan/index.php') ? 'active' : '' ?>">
        <a class="nav-link" href="<?= $laporan_url ?>">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>Laporan Penjualan</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $user_name ?></span>
                        <i class="fas fa-user-circle fa-2x text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>

            </ul>

        </nav>
        <!-- End of Topbar -->