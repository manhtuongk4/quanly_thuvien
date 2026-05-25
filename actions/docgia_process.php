<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$action = $_POST['action'] ?? '';
try {
    if ($action === 'create') {
        $ma = trim($_POST['MaKH'] ?? '') ?: next_code($pdo, 'khach_hang', 'MaKH', 'KH', 3);
        $pass = password_hash($_POST['PasswordHash'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO khach_hang (MaKH,HoTenKH,DiaChi,SoDienThoai,Email,PasswordHash,Avatar) VALUES (?,?,?,?,?,?,?)");
        $stmt->execute([$ma, $_POST['HoTenKH'], $_POST['DiaChi'] ?: null, $_POST['SoDienThoai'] ?: null, $_POST['Email'] ?: null, $pass, $_POST['Avatar'] ?: null]);
        flash('success', 'Đã thêm độc giả.');
    }

    if ($action === 'update') {
        if (!empty($_POST['PasswordHash'])) {
            $stmt = $pdo->prepare("UPDATE khach_hang SET HoTenKH=?, DiaChi=?, SoDienThoai=?, Email=?, PasswordHash=?, Avatar=? WHERE MaKH=?");
            $stmt->execute([$_POST['HoTenKH'], $_POST['DiaChi'] ?: null, $_POST['SoDienThoai'] ?: null, $_POST['Email'] ?: null, password_hash($_POST['PasswordHash'], PASSWORD_DEFAULT), $_POST['Avatar'] ?: null, $_POST['MaKH']]);
        } else {
            $stmt = $pdo->prepare("UPDATE khach_hang SET HoTenKH=?, DiaChi=?, SoDienThoai=?, Email=?, Avatar=? WHERE MaKH=?");
            $stmt->execute([$_POST['HoTenKH'], $_POST['DiaChi'] ?: null, $_POST['SoDienThoai'] ?: null, $_POST['Email'] ?: null, $_POST['Avatar'] ?: null, $_POST['MaKH']]);
        }
        flash('success', 'Đã cập nhật độc giả.');
    }
} catch (Throwable $e) {
    flash('danger', 'Lỗi xử lý độc giả: ' . $e->getMessage());
}
redirect('pages/docgia_list.php');
