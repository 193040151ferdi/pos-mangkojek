<?php
// Determine active menu item based on URL segments
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url_segments = explode('/', $url);
$controller_name = isset($url_segments[0]) ? strtolower($url_segments[0]) : '';
$method_name = isset($url_segments[1]) ? strtolower($url_segments[1]) : '';

$is_dashboard_active = ($controller_name === 'admin' && ($method_name === 'dashboard' || $method_name === ''));
$is_transaksi_active = ($controller_name === 'kasir' && $method_name === 'transaksi');
$is_produk_active = ($controller_name === 'admin' && strpos($method_name, 'produk') === 0);
$is_laporan_active = ($controller_name === 'admin' && $method_name === 'laporan');
$is_pengguna_active = ($controller_name === 'admin' && $method_name === 'pengguna');
?>

<aside class="sidebar-container" id="sidebar">
    <!-- Script sinkron untuk mencegah kedipan visual sidebar saat memuat halaman -->
    <script>
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            document.getElementById('sidebar').classList.add('collapsed');
        }
    </script>

    <!-- Header Merek/Cafe -->
    <div class="sidebar-brand-section">
        <div class="brand-logo-box">
            <i class="bi bi-cup-hot-fill text-white"></i>
        </div>
        <div class="brand-text-wrapper">
            <div class="brand-name">Mang Kojek</div>
            <div class="brand-sub">Kedai Kopi</div>
        </div>
    </div>
    
    <!-- Tombol Pemicu Perkecil Sidebar -->
    <button type="button" class="sidebar-collapse-btn" id="sidebarCollapseBtn" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
        <i class="bi bi-chevron-left" id="collapseIcon"></i>
    </button>

    <!-- Item Navigasi -->
    <div class="sidebar-menu-section">
        <!-- Dashboard -->
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <a href="<?= BASEURL; ?>/admin/dashboard" class="menu-item <?= $is_dashboard_active ? 'active' : '' ?>">
                <i class="bi bi-grid-fill menu-icon"></i>
                <span class="menu-text">Dashboard</span>
            </a>
        <?php endif; ?>

        <!-- Kasir POS (selalu terlihat untuk semua peran) -->
        <a href="<?= BASEURL; ?>/kasir/transaksi" class="menu-item <?= $is_transaksi_active ? 'active' : '' ?>">
            <i class="bi bi-cart-fill menu-icon"></i>
            <span class="menu-text">Kasir POS</span>
        </a>

        <!-- Item Menu Khusus Admin -->
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <!-- Produk & Stok -->
            <a href="<?= BASEURL; ?>/admin/produk" class="menu-item <?= $is_produk_active ? 'active' : '' ?>">
                <i class="bi bi-box-seam-fill menu-icon"></i>
                <span class="menu-text">Produk & Stok</span>
            </a>

            <!-- Laporan -->
            <a href="<?= BASEURL; ?>/admin/laporan" class="menu-item <?= $is_laporan_active ? 'active' : '' ?>">
                <i class="bi bi-bar-chart-line-fill menu-icon"></i>
                <span class="menu-text">Laporan</span>
            </a>

            <!-- Pengguna -->
            <a href="<?= BASEURL; ?>/admin/pengguna" class="menu-item <?= $is_pengguna_active ? 'active' : '' ?>">
                <i class="bi bi-people-fill menu-icon"></i>
                <span class="menu-text">Pengguna</span>
            </a>
        <?php endif; ?>
    </div>

    <!-- Bagian Profil Pengguna -->
    <div class="sidebar-user-section">
        <div class="user-info-wrapper">
            <div class="user-avatar" title="<?= htmlspecialchars($_SESSION['nama']); ?> (<?= ucfirst(htmlspecialchars($_SESSION['role'])); ?>)">
                <i class="bi bi-person-fill text-white"></i>
            </div>
            <div class="user-details">
                <div class="user-name"><?= htmlspecialchars($_SESSION['nama']); ?></div>
                <div class="user-role"><?= ucfirst(htmlspecialchars($_SESSION['role'])); ?></div>
            </div>
        </div>
        <a href="<?= BASEURL; ?>/auth/logout" class="logout-btn" title="Keluar">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>
</aside>

<!-- Script Pengendali Perkecil Sidebar -->
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const isCollapsed = sidebar.classList.toggle('collapsed');
    localStorage.setItem('sidebar-collapsed', isCollapsed);
}
</script>

<main class="content-container">
