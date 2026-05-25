<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$id = $_GET['id'] ?? '';
$isEdit = $id !== '';
$row = ['MaKH'=>'','HoTenKH'=>'','Email'=>'','SoDienThoai'=>'','DiaChi'=>'','PasswordHash'=>'','Avatar'=>''];
if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM khach_hang WHERE MaKH=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch() ?: $row;
}
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>
<h1 class="fw-bold mb-4"><?= $isEdit ? 'Cập nhật độc giả' : 'Thêm độc giả' ?></h1>
<?php show_flash(); ?>
<form class="card-soft p-4" method="post" action="../actions/docgia_process.php">
    <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'create' ?>">
    <div class="row g-3">
        <div class="col-md-3"><label class="form-label">Mã độc giả</label><input class="form-control" name="MaKH" value="<?= e($row['MaKH']) ?>" <?= $isEdit?'readonly':'' ?> placeholder="Tự sinh nếu bỏ trống"></div>
        <div class="col-md-9"><label class="form-label">Họ tên</label><input class="form-control" name="HoTenKH" value="<?= e($row['HoTenKH']) ?>" required></div>
        <div class="col-md-4"><label class="form-label">Email</label><input class="form-control" type="email" name="Email" value="<?= e($row['Email']) ?>"></div>
        <div class="col-md-4"><label class="form-label">SĐT</label><input class="form-control" name="SoDienThoai" value="<?= e($row['SoDienThoai']) ?>"></div>
        <div class="col-md-4"><label class="form-label">Mật khẩu <?= $isEdit?'mới':'' ?></label><input class="form-control" type="password" name="PasswordHash" <?= $isEdit?'':'required' ?>></div>
        <div class="col-12"><label class="form-label">Địa chỉ</label><input class="form-control" name="DiaChi" value="<?= e($row['DiaChi']) ?>"></div>
        <div class="col-12"><label class="form-label">Avatar URL</label><input class="form-control" name="Avatar" value="<?= e($row['Avatar']) ?>"></div>
    </div>
    <div class="mt-4 d-flex gap-2"><button class="btn btn-primary">Lưu</button><a href="docgia_list.php" class="btn btn-light">Quay lại</a></div>
</form>
<?php include __DIR__ . '/../includes/footer.php'; ?>
