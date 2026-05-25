<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    if ($action === 'borrow') {
        $pdo->beginTransaction();

        $maSach = $_POST['MaSach'];
        $stmt = $pdo->prepare("SELECT SoLuongTon FROM sach WHERE MaSach=? FOR UPDATE");
        $stmt->execute([$maSach]);
        $ton = $stmt->fetchColumn();

        if ($ton === false) {
            throw new RuntimeException('Sách không tồn tại.');
        }
        if ((int)$ton <= 0) {
            throw new RuntimeException('Sách đã hết tồn, không thể cho mượn.');
        }
        if ($_POST['NgayHenTra'] < $_POST['NgayMuon']) {
            throw new RuntimeException('Ngày hẹn trả không được nhỏ hơn ngày mượn.');
        }

        $ma = next_code($pdo, 'phieu_muon', 'MaPhieuMuon', 'PM', 5);
        $stmt = $pdo->prepare("INSERT INTO phieu_muon (MaPhieuMuon,MaKH,MaSach,MaNhanVien,NgayMuon,NgayHenTra,TrangThai,GhiChu)
                               VALUES (?,?,?,?,?,?, 'DangMuon', ?)");
        $stmt->execute([
            $ma,
            $_POST['MaKH'],
            $maSach,
            $_SESSION['admin']['MaNhanVien'],
            $_POST['NgayMuon'],
            $_POST['NgayHenTra'],
            $_POST['GhiChu'] ?: null
        ]);

        $stmt = $pdo->prepare("UPDATE sach SET SoLuongTon = SoLuongTon - 1 WHERE MaSach=?");
        $stmt->execute([$maSach]);

        $pdo->commit();
        flash('success', 'Đã tạo phiếu mượn và trừ tồn kho.');
    }

    if ($action === 'return') {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT * FROM phieu_muon WHERE MaPhieuMuon=? FOR UPDATE");
        $stmt->execute([$_GET['id']]);
        $pm = $stmt->fetch();

        if (!$pm) {
            throw new RuntimeException('Phiếu mượn không tồn tại.');
        }
        if ($pm['TrangThai'] === 'DaTra') {
            throw new RuntimeException('Phiếu này đã trả trước đó.');
        }

        $stmt = $pdo->prepare("UPDATE phieu_muon SET TrangThai='DaTra', NgayTra=CURDATE() WHERE MaPhieuMuon=?");
        $stmt->execute([$pm['MaPhieuMuon']]);

        $stmt = $pdo->prepare("UPDATE sach SET SoLuongTon = SoLuongTon + 1 WHERE MaSach=?");
        $stmt->execute([$pm['MaSach']]);

        $pdo->commit();
        flash('success', 'Đã cập nhật trả sách và cộng lại tồn kho.');
    }
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    flash('danger', 'Lỗi nghiệp vụ mượn/trả: ' . $e->getMessage());
}

redirect('pages/phieumuon_list.php');
