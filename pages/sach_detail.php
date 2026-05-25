<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$id = trim($_GET['id'] ?? '');
if ($id === '') {
    flash('danger', 'Thiếu mã sách cần xem chi tiết.');
    redirect('pages/sach_list.php');
}

$stmt = $pdo->prepare("SELECT s.*, tg.TenTacGia, tl.TenTheLoai, nxb.TenNXB
    FROM sach s
    LEFT JOIN tac_gia tg ON tg.MaTacGia=s.MaTacGia
    LEFT JOIN the_loai tl ON tl.MaTheLoai=s.MaTheLoai
    LEFT JOIN nha_xuat_ban nxb ON nxb.MaNXB=s.MaNXB
    WHERE s.MaSach=?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    flash('danger', 'Không tìm thấy sách.');
    redirect('pages/sach_list.php');
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>
<style>
    .book-cover-lg {
        width: 100%;
        max-width: 260px;
        height: 360px;
        object-fit: cover;
        border-radius: 18px;
        border: 1px solid rgba(0, 0, 0, .08);
        background: #f8f9fa;
        display: block;
        margin: 0 auto;
        box-shadow: 0 12px 28px rgba(15, 23, 42, .12);
    }

    .detail-metric {
        border: 1px solid rgba(0, 0, 0, .08);
        border-radius: 14px;
        padding: 14px;
        background: rgba(248, 249, 250, .72);
        height: 100%;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="text-muted small mb-1"><a href="sach_list.php" class="text-decoration-none">Danh mục sách</a> / <?= e($book['MaSach']) ?></div>
        <h1 class="fw-bold mb-0">Chi tiết sách</h1>
    </div>
    <div class="d-flex gap-2">
        <a class="btn btn-light" href="sach_list.php"><i class="bi bi-arrow-left me-1"></i>Quay lại</a>
        <a class="btn btn-primary" href="sach_form.php?id=<?= e($book['MaSach']) ?>"><i class="bi bi-pencil-square me-1"></i>Sửa sách</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-soft p-3">
            <img class="book-cover-lg" src="<?= e(book_image_url($book['Anh'] ?? '')) ?>" alt="Ảnh <?= e($book['TenSach']) ?>" onerror="this.onerror=null;this.src='<?= e(base_url('assets/images/book-placeholder.svg')) ?>';">
            <div class="small text-muted mt-3 text-break">Nguồn ảnh: <?= e($book['Anh'] ?: 'Chưa có đường dẫn ảnh') ?></div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card-soft p-4 h-100">
            <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                <div>
                    <span class="badge badge-soft mb-2"><?= e($book['MaSach']) ?></span>
                    <h2 class="fw-bold mb-2"><?= e($book['TenSach']) ?></h2>
                    <div class="text-muted">
                        <i class="bi bi-person me-1"></i><?= e($book['TenTacGia'] ?: 'Chưa cập nhật tác giả') ?>
                        <span class="mx-2">•</span>
                        <i class="bi bi-bookmark me-1"></i><?= e($book['TenTheLoai'] ?: 'Chưa cập nhật thể loại') ?>
                    </div>
                </div>
                <div class="text-lg-end">
                    <div class="fs-3 fw-bold text-primary"><?= money_vnd($book['DonGiaBan']) ?></div>
                    <div class="small text-muted">Giá nhập: <?= $book['DonGiaNhap'] !== null ? money_vnd($book['DonGiaNhap']) : 'Chưa cập nhật' ?></div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="detail-metric">
                        <div class="text-muted small">Tồn kho</div>
                        <div class="fs-4 fw-bold"><?= (int)$book['SoLuongTon'] ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="detail-metric">
                        <div class="text-muted small">Năm XB</div>
                        <div class="fs-5 fw-bold"><?= e($book['NamXuatBan'] ?: '—') ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="detail-metric">
                        <div class="text-muted small">Số trang</div>
                        <div class="fs-5 fw-bold"><?= e($book['SoTrang'] ?: '—') ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="detail-metric">
                        <div class="text-muted small">Kích thước</div>
                        <div class="fs-5 fw-bold"><?= e($book['KichThuoc'] ?: '—') ?></div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <h5 class="fw-bold">Thông tin xuất bản</h5>
                <div class="row g-2 text-muted">
                    <div class="col-md-6"><i class="bi bi-building me-1"></i>Nhà xuất bản: <?= e($book['TenNXB'] ?: 'Chưa cập nhật') ?></div>
                    <div class="col-md-6"><i class="bi bi-tag me-1"></i>Thể loại: <?= e($book['TenTheLoai'] ?: 'Chưa cập nhật') ?></div>
                </div>
            </div>

            <div>
                <h5 class="fw-bold">Mô tả</h5>
                <p class="mb-0 lh-lg"><?= nl2br(e($book['MoTa'] ?: 'Chưa có mô tả cho sách này.')) ?></p>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>