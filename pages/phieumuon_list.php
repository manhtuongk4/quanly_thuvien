<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$status = $_GET['status'] ?? '';
$sql = "SELECT pm.*, kh.HoTenKH, s.TenSach
        FROM phieu_muon pm
        JOIN khach_hang kh ON kh.MaKH=pm.MaKH
        JOIN sach s ON s.MaSach=pm.MaSach";
$params = [];
if ($status !== '') {
    $sql .= " WHERE pm.TrangThai=?";
    $params[] = $status;
}
$sql .= " ORDER BY pm.NgayTao DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h1 class="fw-bold mb-1">Phiếu mượn / trả</h1><p class="text-muted mb-0">Trả sách sẽ tự động cộng lại tồn kho.</p></div>
    <a class="btn btn-primary" href="phieumuon_form.php"><i class="bi bi-plus-lg me-1"></i>Tạo phiếu mượn</a>
</div>
<?php show_flash(); ?>
<div class="card-soft p-4">
    <form class="mb-3">
        <select class="form-select" name="status" onchange="this.form.submit()" style="max-width:240px">
            <option value="">Tất cả trạng thái</option>
            <option value="DangMuon" <?= $status==='DangMuon'?'selected':'' ?>>Đang mượn</option>
            <option value="DaTra" <?= $status==='DaTra'?'selected':'' ?>>Đã trả</option>
        </select>
    </form>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Mã phiếu</th><th>Độc giả</th><th>Sách</th><th>Ngày mượn</th><th>Hẹn trả</th><th>Ngày trả</th><th>Trạng thái</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= e($r['MaPhieuMuon']) ?></td>
                <td><?= e($r['HoTenKH']) ?></td>
                <td><?= e($r['TenSach']) ?></td>
                <td><?= e($r['NgayMuon']) ?></td>
                <td><?= e($r['NgayHenTra']) ?></td>
                <td><?= e($r['NgayTra'] ?? '') ?></td>
                <td><span class="badge <?= $r['TrangThai']==='DaTra'?'bg-success':'bg-warning text-dark' ?>"><?= e($r['TrangThai']) ?></span></td>
                <td class="text-end">
                    <?php if ($r['TrangThai'] === 'DangMuon'): ?>
                        <a class="btn btn-sm btn-success" onclick="return confirm('Xác nhận trả sách?')" href="../actions/muontra_process.php?action=return&id=<?= e($r['MaPhieuMuon']) ?>">Trả sách</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?><tr><td colspan="8" class="text-center text-muted">Chưa có phiếu mượn.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
