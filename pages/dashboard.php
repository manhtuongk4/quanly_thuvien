<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/sidebar.php';

$books = (int)$pdo->query("SELECT COUNT(*) FROM sach")->fetchColumn();
$readers = (int)$pdo->query("SELECT COUNT(*) FROM khach_hang")->fetchColumn();
$borrowing = (int)$pdo->query("SELECT COUNT(*) FROM phieu_muon WHERE TrangThai='DangMuon'")->fetchColumn();
$outOfStock = (int)$pdo->query("SELECT COUNT(*) FROM sach WHERE SoLuongTon <= 0")->fetchColumn();

$latest = $pdo->query("
    SELECT pm.*, kh.HoTenKH, s.TenSach
    FROM phieu_muon pm
    JOIN khach_hang kh ON kh.MaKH = pm.MaKH
    JOIN sach s ON s.MaSach = pm.MaSach
    ORDER BY pm.NgayTao DESC
    LIMIT 8
")->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Dashboard thư viện</h1>
        <p class="text-muted mb-0">Theo dõi sách, độc giả và nghiệp vụ mượn/trả.</p>
    </div>
    <a href="phieumuon_form.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tạo phiếu mượn</a>
</div>

<div class="row g-4 mb-4">
    <?php foreach ([
        ['Số đầu sách', $books, 'bi-journal-bookmark'],
        ['Độc giả', $readers, 'bi-people'],
        ['Đang mượn', $borrowing, 'bi-arrow-left-right'],
        ['Hết tồn', $outOfStock, 'bi-exclamation-triangle'],
    ] as $item): ?>
    <div class="col-md-3">
        <div class="card-soft p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div><div class="text-muted"><?= $item[0] ?></div><h2 class="fw-bold mb-0"><?= $item[1] ?></h2></div>
                <i class="bi <?= $item[2] ?> fs-1 text-primary"></i>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="card-soft p-4">
    <h5 class="fw-bold mb-3">Phiếu mượn gần đây</h5>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Mã phiếu</th><th>Độc giả</th><th>Sách</th><th>Ngày mượn</th><th>Hẹn trả</th><th>Trạng thái</th></tr></thead>
            <tbody>
            <?php foreach ($latest as $row): ?>
                <tr>
                    <td><?= e($row['MaPhieuMuon']) ?></td>
                    <td><?= e($row['HoTenKH']) ?></td>
                    <td><?= e($row['TenSach']) ?></td>
                    <td><?= e($row['NgayMuon']) ?></td>
                    <td><?= e($row['NgayHenTra']) ?></td>
                    <td><span class="badge <?= $row['TrangThai']==='DaTra'?'bg-success':'bg-warning text-dark' ?>"><?= e($row['TrangThai']) ?></span></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$latest): ?><tr><td colspan="6" class="text-center text-muted">Chưa có phiếu mượn.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
