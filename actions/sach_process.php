<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    if ($action === 'create') {
        $ma = trim($_POST['MaSach'] ?? '') ?: next_code($pdo, 'sach', 'MaSach', 'S', 3);
        $stmt = $pdo->prepare("INSERT INTO sach
            (MaSach,TenSach,MaTacGia,MaTheLoai,MaNXB,DonGiaNhap,DonGiaBan,NamXuatBan,SoLuongTon,MoTa,KichThuoc,SoTrang,Anh)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([
            $ma, $_POST['TenSach'], $_POST['MaTacGia'] ?: null, $_POST['MaTheLoai'] ?: null, $_POST['MaNXB'] ?: null,
            $_POST['DonGiaNhap'] ?: null, $_POST['DonGiaBan'], $_POST['NamXuatBan'] ?: null, $_POST['SoLuongTon'] ?: 0,
            $_POST['MoTa'] ?: null, $_POST['KichThuoc'] ?: null, $_POST['SoTrang'] ?: null, $_POST['Anh'] ?: null
        ]);
        flash('success', 'Đã thêm sách.');
    }

    if ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE sach SET TenSach=?, MaTacGia=?, MaTheLoai=?, MaNXB=?, DonGiaNhap=?, DonGiaBan=?, NamXuatBan=?, SoLuongTon=?, MoTa=?, KichThuoc=?, SoTrang=?, Anh=? WHERE MaSach=?");
        $stmt->execute([
            $_POST['TenSach'], $_POST['MaTacGia'] ?: null, $_POST['MaTheLoai'] ?: null, $_POST['MaNXB'] ?: null,
            $_POST['DonGiaNhap'] ?: null, $_POST['DonGiaBan'], $_POST['NamXuatBan'] ?: null, $_POST['SoLuongTon'] ?: 0,
            $_POST['MoTa'] ?: null, $_POST['KichThuoc'] ?: null, $_POST['SoTrang'] ?: null, $_POST['Anh'] ?: null, $_POST['MaSach']
        ]);
        flash('success', 'Đã cập nhật sách.');
    }

    if ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM sach WHERE MaSach=?");
        $stmt->execute([$_GET['id']]);
        flash('success', 'Đã xóa sách.');
    }
} catch (Throwable $e) {
    flash('danger', 'Lỗi xử lý sách: ' . $e->getMessage());
}
redirect('pages/sach_list.php');
