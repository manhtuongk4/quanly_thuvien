<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();
$q = trim($_GET['q'] ?? '');
$sql = "SELECT * FROM khach_hang";
$params = [];
if ($q !== '') {
    $sql .= " WHERE MaKH LIKE ? OR HoTenKH LIKE ? OR SoDienThoai LIKE ? OR Email LIKE ?";
    $params = ["%$q%", "%$q%", "%$q%", "%$q%"];
}
$sql .= " ORDER BY MaKH DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h1 class="fw-bold mb-1">Quản lý Độc giả</h1><p class="text-muted mb-0">Tái sử dụng dữ liệu từ bảng khach_hang.</p></div>
    <a class="btn btn-primary" href="docgia_form.php"><i class="bi bi-plus-lg me-1"></i>Thêm độc giả</a>
</div>
<?php show_flash(); ?>
<div class="card-soft p-4">
    <form class="row g-2 mb-3">
        <div class="col-md-10"><input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Tên, mã, SĐT hoặc email"></div>
        <div class="col-md-2"><button class="btn btn-primary w-100">Tìm</button></div>
    </form>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Mã</th><th>Họ tên</th><th>Email</th><th>SĐT</th><th>Địa chỉ</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= e($r['MaKH']) ?></td><td class="fw-semibold"><?= e($r['HoTenKH']) ?></td>
                <td><?= e($r['Email']) ?></td><td><?= e($r['SoDienThoai']) ?></td><td><?= e($r['DiaChi']) ?></td>
                <td class="text-end"><a class="btn btn-sm btn-light" href="docgia_form.php?id=<?= e($r['MaKH']) ?>">Sửa</a></td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$rows): ?><tr><td colspan="6" class="text-center text-muted">Không có độc giả.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
