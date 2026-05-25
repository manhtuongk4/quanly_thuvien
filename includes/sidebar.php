<?php
$current = basename($_SERVER['PHP_SELF']);
function active_menu(array $files): string
{
    global $current;
    return in_array($current, $files, true) ? 'show' : '';
}
function active_link(string $file): string
{
    global $current;
    return $current === $file ? 'active' : '';
}
?>
<aside class="app-sidebar d-none d-lg-flex">
    <div class="brand">
        <div class="brand-icon"><i class="bi bi-book-half"></i></div>
        <div>
            <strong>Library University</strong>
            <span>Metronic style</span>
        </div>
    </div>

    <nav class="menu">
        <a class="menu-link <?= active_link('dashboard.php') ?>" href="<?= base_url('pages/dashboard.php') ?>">
            <i class="bi bi-grid"></i><span>Trang chủ</span>
        </a>

        <button class="menu-parent" data-bs-toggle="collapse" data-bs-target="#bookMenu">
            <i class="bi bi-journal-bookmark"></i><span>Danh mục Sách</span><i class="bi bi-chevron-down ms-auto"></i>
        </button>
        <div id="bookMenu" class="collapse <?= active_menu(['sach_list.php', 'sach_form.php']) ?>">
            <a class="menu-child <?= active_link('sach_list.php') ?>" href="<?= base_url('pages/sach_list.php') ?>">Danh sách sách</a>
            <a class="menu-child <?= active_link('sach_form.php') ?>" href="<?= base_url('pages/sach_form.php') ?>">Thêm sách</a>
        </div>

        <button class="menu-parent" data-bs-toggle="collapse" data-bs-target="#readerMenu">
            <i class="bi bi-people"></i><span>Độc giả</span><i class="bi bi-chevron-down ms-auto"></i>
        </button>
        <div id="readerMenu" class="collapse <?= active_menu(['docgia_list.php', 'docgia_form.php']) ?>">
            <a class="menu-child <?= active_link('docgia_list.php') ?>" href="<?= base_url('pages/docgia_list.php') ?>">Danh sách độc giả</a>
            <a class="menu-child <?= active_link('docgia_form.php') ?>" href="<?= base_url('pages/docgia_form.php') ?>">Thêm độc giả</a>
        </div>

        <button class="menu-parent" data-bs-toggle="collapse" data-bs-target="#borrowMenu">
            <i class="bi bi-arrow-left-right"></i><span>Mượn / Trả</span><i class="bi bi-chevron-down ms-auto"></i>
        </button>
        <div id="borrowMenu" class="collapse <?= active_menu(['phieumuon_list.php', 'phieumuon_form.php']) ?>">
            <a class="menu-child <?= active_link('phieumuon_list.php') ?>" href="<?= base_url('pages/phieumuon_list.php') ?>">Phiếu mượn</a>
            <a class="menu-child <?= active_link('phieumuon_form.php') ?>" href="<?= base_url('pages/phieumuon_form.php') ?>">Tạo phiếu mượn</a>
        </div>
    </nav>
</aside>

<div class="offcanvas offcanvas-start" id="mobileSidebar">
    <div class="offcanvas-body p-0">
        <aside class="app-sidebar position-static w-100 d-flex" style="height:100vh">
            <div class="brand">
                <div class="brand-icon"><i class="bi bi-book-half"></i></div>
                <div><strong>Library Admin</strong><span>Mobile</span></div>
            </div>
            <nav class="menu">
                <a class="menu-link" href="<?= base_url('pages/dashboard.php') ?>"><i class="bi bi-grid"></i><span>Trang chủ</span></a>
                <a class="menu-link" href="<?= base_url('pages/sach_list.php') ?>"><i class="bi bi-journal-bookmark"></i><span>Sách</span></a>
                <a class="menu-link" href="<?= base_url('pages/docgia_list.php') ?>"><i class="bi bi-people"></i><span>Độc giả</span></a>
                <a class="menu-link" href="<?= base_url('pages/phieumuon_list.php') ?>"><i class="bi bi-arrow-left-right"></i><span>Mượn / Trả</span></a>
            </nav>
        </aside>
    </div>
</div>

<main class="app-main">