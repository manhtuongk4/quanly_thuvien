<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();
$readers = $pdo->query("SELECT MaKH, HoTenKH, SoDienThoai FROM khach_hang ORDER BY HoTenKH")->fetchAll();
$books = $pdo->query("SELECT MaSach, TenSach, SoLuongTon FROM sach WHERE SoLuongTon > 0 ORDER BY TenSach")->fetchAll();
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>
<h1 class="fw-bold mb-4">Tạo phiếu mượn</h1>
<?php show_flash(); ?>
<form class="card-soft p-4" method="post" action="../actions/muontra_process.php">
    <input type="hidden" name="action" value="borrow">
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Độc giả</label>
            <select class="form-select" name="MaKH" required>
                <option value="">-- Chọn độc giả --</option>
                <?php foreach ($readers as $r): ?>
                    <option value="<?= e($r['MaKH']) ?>"><?= e($r['HoTenKH']) ?> - <?= e($r['SoDienThoai']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Sách còn tồn</label>
            <select class="form-select" name="MaSach" required>
                <option value="">-- Chọn sách --</option>
                <?php foreach ($books as $b): ?>
                    <option value="<?= e($b['MaSach']) ?>"><?= e($b['TenSach']) ?> - tồn <?= (int)$b['SoLuongTon'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6"><label class="form-label">Ngày mượn</label><input class="form-control" type="date" name="NgayMuon" value="<?= date('Y-m-d') ?>" required></div>
        <div class="col-md-6"><label class="form-label">Ngày hẹn trả</label><input class="form-control" type="date" name="NgayHenTra" value="<?= date('Y-m-d', strtotime('+14 days')) ?>" required></div>
        <div class="col-12"><label class="form-label">Ghi chú</label><textarea class="form-control" name="GhiChu" rows="3"></textarea></div>
    </div>
    <div class="mt-4 d-flex gap-2"><button class="btn btn-primary">Tạo phiếu</button><a href="phieumuon_list.php" class="btn btn-light">Quay lại</a></div>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
