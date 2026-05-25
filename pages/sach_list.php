<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();
$q = trim($_GET['q'] ?? '');
$sql = "SELECT s.*, tg.TenTacGia, tl.TenTheLoai, nxb.TenNXB
        FROM sach s
        LEFT JOIN tac_gia tg ON tg.MaTacGia=s.MaTacGia
        LEFT JOIN the_loai tl ON tl.MaTheLoai=s.MaTheLoai
        LEFT JOIN nha_xuat_ban nxb ON nxb.MaNXB=s.MaNXB";
$params = [];
if ($q !== '') {
    $sql .= " WHERE s.MaSach LIKE ? OR s.TenSach LIKE ?";
    $params = ["%$q%", "%$q%"];
}
$sql .= " ORDER BY s.MaSach DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';
?>
<style>
    .book-cover-sm {
        width: 54px;
        height: 72px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid rgba(0, 0, 0, .08);
        background: #f8f9fa;
        display: block;
        box-shadow: 0 4px 12px rgba(15, 23, 42, .08);
    }

    .book-title-link {
        text-decoration: none;
        color: inherit;
    }

    .book-title-link:hover {
        color: var(--bs-primary);
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-width: 420px;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Danh mục Sách</h1>
        <p class="text-muted mb-0">Tìm kiếm, xem ảnh bìa, xem chi tiết, thêm, sửa, xóa thông tin sách.</p>
    </div>
    <a class="btn btn-primary" href="sach_form.php"><i class="bi bi-plus-lg me-1"></i>Thêm sách</a>
</div>
<?php show_flash(); ?>
<div class="card-soft p-4">
    <form class="row g-2 mb-3" method="get">
        <div class="col-md-10"><input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Nhập tên sách hoặc mã sách"></div>
        <div class="col-md-2"><button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Tìm</button></div>
    </form>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th style="width:82px">Ảnh</th>
                    <th>Mã</th>
                    <th>Tên sách</th>
                    <th>Tác giả</th>
                    <th>Thể loại</th>
                    <th>NXB</th>
                    <th>Giá sách</th>
                    <th>Tồn</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td>
                            <a href="sach_detail.php?id=<?= e($r['MaSach']) ?>">
                                <img class="book-cover-sm" src="<?= e(book_image_url($r['Anh'] ?? '')) ?>" alt="Ảnh <?= e($r['TenSach']) ?>" onerror="this.onerror=null;this.src='<?= e(base_url('assets/images/book-placeholder.svg')) ?>';">
                            </a>
                        </td>
                        <td><?= e($r['MaSach']) ?></td>
                        <td>
                            <a class="fw-semibold book-title-link" href="sach_detail.php?id=<?= e($r['MaSach']) ?>"><?= e($r['TenSach']) ?></a>
                            <?php if (!empty($r['MoTa'])): ?><div class="small text-muted line-clamp-2"><?= e($r['MoTa']) ?></div><?php endif; ?>
                        </td>
                        <td><?= e($r['TenTacGia']) ?></td>
                        <td><?= e($r['TenTheLoai']) ?></td>
                        <td><?= e($r['TenNXB']) ?></td>
                        <td><?= money_vnd($r['DonGiaBan']) ?></td>
                        <td><span class="badge <?= $r['SoLuongTon'] > 0 ? 'badge-soft' : 'bg-danger' ?>"><?= (int)$r['SoLuongTon'] ?></span></td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a class="btn btn-light" href="sach_detail.php?id=<?= e($r['MaSach']) ?>"><i class="bi bi-eye me-1"></i>Chi tiết</a>
                                <a class="btn btn-light" href="sach_form.php?id=<?= e($r['MaSach']) ?>">Sửa</a>
                                <a class="btn btn-outline-danger" onclick="return confirm('Xóa sách này?')" href="../actions/sach_process.php?action=delete&id=<?= e($r['MaSach']) ?>">Xóa</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$rows): ?><tr>
                        <td colspan="9" class="text-center text-muted py-4">Không tìm thấy sách.</td>
                    </tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>