<?php
require_once __DIR__ . '/includes/functions.php';
if (is_logged_in()) redirect('pages/dashboard.php');
?>
<!doctype html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <title>Đăng nhập - Quản lý Thư viện</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
<div class="card-soft p-4" style="width:420px;max-width:94vw">
    <div class="text-center mb-4">
        <div class="brand-icon mx-auto mb-3"><i class="bi bi-book-half"></i></div>
        <h3 class="fw-bold">Đăng nhập quản trị</h3>
        <p class="text-muted">Admin / Thủ thư</p>
    </div>
    <?php show_flash(); ?>
    <form action="<?= base_url('actions/auth_process.php') ?>" method="post">
        <input type="hidden" name="action" value="login">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control form-control-lg" required value="admin@mote.vn">
        </div>
        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input name="password" type="password" class="form-control form-control-lg" required>
            <div class="form-text">Database mẫu đang lưu mật khẩu dạng text: 0868219140Tg.</div>
        </div>
        <button class="btn btn-primary btn-lg w-100">Đăng nhập</button>
    </form>
</div>
</body>
</html>
