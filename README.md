# Website Bán Đồ Gia Dụng

Đây là một website bán đồ gia dụng được xây dựng bằng PHP thuần và MySQL.

## Tính năng chính

- 🛍️ **Quản lý sản phẩm**
  - Hiển thị danh sách sản phẩm
  - Phân loại sản phẩm theo danh mục
  - Tìm kiếm sản phẩm
  - Chi tiết sản phẩm
  - So sánh sản phẩm

- 👤 **Quản lý người dùng**
  - Đăng ký tài khoản
  - Đăng nhập/Đăng xuất
  - Quản lý thông tin cá nhân

- 🛒 **Giỏ hàng**
  - Thêm sản phẩm vào giỏ hàng
  - Cập nhật số lượng
  - Xóa sản phẩm khỏi giỏ hàng

- 👨‍💼 **Trang quản trị**
  - Quản lý sản phẩm
  - Quản lý danh mục
  - Quản lý người dùng
  - Quản lý đơn hàng

## Cài đặt

1. Clone repository về máy local
2. Import file database vào MySQL
3. Cấu hình kết nối database trong file `database/connect.php`
4. Chạy website thông qua web server (Apache/Nginx)

## Yêu cầu hệ thống

- PHP >= 7.0
- MySQL >= 5.7
- Web server (Apache/Nginx)

## Cấu trúc thư mục

```
BanDoGiaDung/
├── admin/           # Trang quản trị
├── assets/          # Tài nguyên (images, fonts)
├── database/        # File kết nối và xử lý database
├── frontend/        # Giao diện người dùng
│   ├── components/  # Các component tái sử dụng
│   ├── css/        # File CSS
│   └── js/         # File JavaScript
```

## Triển khai

Website này có thể được triển khai trên các nền tảng sau:

1. **Shared Hosting**: Phù hợp với các gói hosting hỗ trợ PHP và MySQL
2. **VPS/Cloud Server**: Có thể triển khai trên các dịch vụ như DigitalOcean, Linode, Vultr
3. **Local Server**: Có thể chạy trên XAMPP, WAMP, MAMP

## Đóng góp

Mọi đóng góp đều được hoan nghênh. Vui lòng tạo issue hoặc pull request để đóng góp.

## Giấy phép

MIT License 