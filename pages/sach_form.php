<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$id = $_GET['id'] ?? '';
$isEdit = $id !== '';
$row = ['MaSach' => '', 'TenSach' => '', 'MaTacGia' => '', 'MaTheLoai' => '', 'MaNXB' => '', 'DonGiaNhap' => '', 'DonGiaBan' => '', 'NamXuatBan' => '', 'SoLuongTon' => 0, 'MoTa' => '', 'KichThuoc' => '', 'SoTrang' => '', 'Anh' => ''];
if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM sach WHERE MaSach=?");
    $stmt->execute([$id]);
    $row = $stmt->fetch() ?: $row;
}
$tacGia = $pdo->query("SELECT MaTacGia, TenTacGia FROM tac_gia ORDER BY TenTacGia")->fetchAll();
$theLoai = $pdo->query("SELECT MaTheLoai, TenTheLoai FROM the_loai ORDER BY TenTheLoai")->fetchAll();
$nxb = $pdo->query("SELECT MaNXB, TenNXB FROM nha_xuat_ban ORDER BY TenNXB")->fetchAll();

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>
<style>
    .book-cover-md {
        width: 140px;
        height: 190px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid rgba(0, 0, 0, .08);
        background: #f8f9fa;
        display: block;
        box-shadow: 0 8px 20px rgba(15, 23, 42, .10);
    }
</style>

<h1 class="fw-bold mb-4"><?= $isEdit ? 'Cập nhật sách' : 'Thêm sách mới' ?></h1>
<?php show_flash(); ?>
<form class="card-soft p-4" method="post" action="../actions/sach_process.php">
    <input type="hidden" name="action" value="<?= $isEdit ? 'update' : 'create' ?>">
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Mã sách</label>
            <input class="form-control" name="MaSach" value="<?= e($row['MaSach']) ?>" <?= $isEdit ? 'readonly' : '' ?> placeholder="Tự sinh nếu bỏ trống">
        </div>
        <div class="col-md-9">
            <label class="form-label">Tên sách</label>
            <input class="form-control" name="TenSach" value="<?= e($row['TenSach']) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Tác giả</label>
            <select class="form-select" name="MaTacGia">
                <option value="">-- Chọn --</option>
                <?php foreach ($tacGia as $x): ?><option value="<?= e($x['MaTacGia']) ?>" <?= $row['MaTacGia'] === $x['MaTacGia'] ? 'selected' : '' ?>><?= e($x['TenTacGia']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Thể loại</label>
            <select class="form-select" name="MaTheLoai">
                <option value="">-- Chọn --</option>
                <?php foreach ($theLoai as $x): ?><option value="<?= e($x['MaTheLoai']) ?>" <?= $row['MaTheLoai'] === $x['MaTheLoai'] ? 'selected' : '' ?>><?= e($x['TenTheLoai']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Nhà xuất bản</label>
            <select class="form-select" name="MaNXB">
                <option value="">-- Chọn --</option>
                <?php foreach ($nxb as $x): ?><option value="<?= e($x['MaNXB']) ?>" <?= $row['MaNXB'] === $x['MaNXB'] ? 'selected' : '' ?>><?= e($x['TenNXB']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3"><label class="form-label">Giá nhập</label><input class="form-control" type="number" name="DonGiaNhap" value="<?= e($row['DonGiaNhap']) ?>"></div>
        <div class="col-md-3"><label class="form-label">Giá bán</label><input class="form-control" type="number" name="DonGiaBan" value="<?= e($row['DonGiaBan']) ?>" required></div>
        <div class="col-md-3"><label class="form-label">Năm XB</label><input class="form-control" type="number" name="NamXuatBan" value="<?= e($row['NamXuatBan']) ?>"></div>
        <div class="col-md-3"><label class="form-label">Số lượng tồn</label><input class="form-control" type="number" name="SoLuongTon" min="0" value="<?= e($row['SoLuongTon']) ?>"></div>
        <div class="col-md-4"><label class="form-label">Kích thước</label><input class="form-control" name="KichThuoc" value="<?= e($row['KichThuoc']) ?>"></div>
        <div class="col-md-4"><label class="form-label">Số trang</label><input class="form-control" type="number" name="SoTrang" value="<?= e($row['SoTrang']) ?>"></div>
        <div class="col-md-8"><label class="form-label">Ảnh URL / đường dẫn ảnh</label><input class="form-control" id="AnhInput" name="Anh" value="<?= e($row['Anh']) ?>" placeholder="VD: https://... hoặc assets/images/ten-anh.jpg">
            <div class="form-text">Ảnh được lưu bằng đường dẫn trong cột <code>Anh</code> của bảng <code>sach</code>, không upload file trực tiếp.</div>
        </div>
        <div class="col-md-4"><label class="form-label">Xem trước ảnh</label>
            <div><img id="AnhPreview" class="book-cover-md" src="<?= e(book_image_url($row['Anh'] ?? '')) ?>" onerror="this.onerror=null;this.src='<?= e(base_url('assets/images/book-placeholder.svg')) ?>';" alt="Xem trước ảnh sách"></div>
        </div>
        <div class="col-12"><label class="form-label">Mô tả</label><textarea class="form-control" name="MoTa" rows="4"><?= e($row['MoTa']) ?></textarea></div>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button class="btn btn-primary">Lưu</button>
        <a href="sach_list.php" class="btn btn-light">Quay lại</a>
    </div>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('AnhInput');
        const preview = document.getElementById('AnhPreview');
        const fallback = '<?= e(base_url('assets/images/book-placeholder.svg')) ?>';
        if (!input || !preview) return;
        input.addEventListener('input', function() {
            const value = input.value.trim();
            preview.onerror = function() {
                preview.onerror = null;
                preview.src = fallback;
            };
            if (!value) {
                preview.src = fallback;
                return;
            }
            if (/^https?:\/\//i.test(value)) {
                preview.src = value;
            } else {
                preview.src = '<?= e(base_url('')) ?>/' + value.replace(/^\/+/, '');
            }
        });
    });
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>