<?php
require_once __DIR__ . '/functions.php';
?>
<!doctype html>
<html lang="vi" data-bs-theme="<?= $_COOKIE['theme'] ?? 'light' ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý Thư viện</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body>
<div class="app-shell">
<header class="app-header">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-light d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <div class="text-muted small">Hệ thống</div>
            <h5 class="mb-0 fw-bold">Quản lý Thư viện</h5>
        </div>
    </div>

    <form class="header-search" action="<?= base_url('pages/sach_list.php') ?>" method="get">
        <i class="bi bi-search"></i>
        <input name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="Tìm sách theo mã hoặc tên...">
    </form>

    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-icon" id="themeToggle" title="Sáng / tối">
            <i class="bi bi-moon-stars"></i>
        </button>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-1"></i><?= e($_SESSION['admin']['HoTenNV'] ?? 'Admin') ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item text-danger" href="<?= base_url('actions/auth_process.php?action=logout') ?>">
                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                </a></li>
            </ul>
        </div>
    </div>
</header>
