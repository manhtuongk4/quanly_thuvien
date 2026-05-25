<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'logout') {
    session_destroy();
    redirect('index.php');
}

if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM nhan_vien WHERE Email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    $ok = false;
    if ($user) {
        $hash = $user['PasswordHash'];
        $ok = password_verify($password, $hash) || hash_equals($hash, $password);
    }

    if ($ok) {
        $_SESSION['admin'] = [
            'MaNhanVien' => $user['MaNhanVien'],
            'HoTenNV' => $user['HoTenNV'],
            'Email' => $user['Email'],
        ];
        redirect('pages/dashboard.php');
    }

    flash('danger', 'Email hoặc mật khẩu không đúng.');
    redirect('index.php');
}

redirect('index.php');
