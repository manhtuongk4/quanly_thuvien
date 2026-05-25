<<<<<<< HEAD

# Group-exercise_Kiemthu

=======

# Quản lý Thư viện - PHP thuần + MySQL

## Cài đặt nhanh

1. Copy thư mục `quanly_thuvien` vào `htdocs`.
2. Import file SQL cũ `quanly_bansach.sql`.
3. Chạy thêm file `database/thu_vien_migration.sql` để tạo bảng `phieu_muon`.
4. Sửa cấu hình kết nối ở `config/database.php` nếu MySQL không dùng `root` / mật khẩu rỗng.
5. Truy cập: `http://localhost/quanly_thuvien`.

## Tài khoản mẫu

- Email: `admin@mote.vn`
- Mật khẩu: `0868219140Tg.`

## Ghi chú nghiệp vụ

- Độc giả tái sử dụng bảng `khach_hang`.
- Admin/thủ thư dùng bảng `nhan_vien`.
- Tạo phiếu mượn sẽ khóa dòng sách bằng `FOR UPDATE`, kiểm tra `SoLuongTon`, sau đó trừ tồn kho.
- Trả sách sẽ đổi trạng thái `DaTra`, ghi `NgayTra`, sau đó cộng lại tồn kho.

## Ảnh sách và xem chi tiết

- Cột ảnh dùng trường `Anh` trong bảng `sach`. Có thể lưu URL ngoài như `https://...` hoặc đường dẫn nội bộ như `assets/images/sach-001.jpg`.
- Trang danh sách `pages/sach_list.php` đã hiển thị ảnh bìa và nút `Chi tiết`.
- Trang mới `pages/sach_detail.php` hiển thị ảnh lớn, mô tả, giá, tồn kho, tác giả, thể loại, NXB và thông tin xuất bản.
- Nếu database cũ chưa có cột `Anh`, chạy thêm `database/thu_vien_migration.sql`.
  > > > > > > > 3f0b7aa (Thêm file README.md: Cập nhật thông tin và mô tả dự án)
