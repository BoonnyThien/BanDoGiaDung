# Website Bán Đồ Gia Dụng

Đây là một website bán đồ gia dụng được xây dựng bằng PHP thuần và MySQL, với phiên bản web tĩnh để demo trên GitHub Pages.

## Phiên bản Localhost (Chính)

### Tính năng chính

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

### Cài đặt phiên bản Localhost

1. Clone repository về máy local
2. Import file database vào MySQL
3. Cấu hình kết nối database trong file `database/connect.php`
4. Chạy website thông qua web server (Apache/Nginx)

### Yêu cầu hệ thống

- PHP >= 7.0
- MySQL >= 5.7
- Web server (Apache/Nginx)

## Phiên bản Web Tĩnh (Demo)

Phiên bản web tĩnh được tạo để demo trên GitHub Pages, sử dụng HTML, CSS và JavaScript thuần. Dữ liệu sản phẩm được lưu dưới dạng JSON và các chức năng như giỏ hàng, đăng ký/đăng nhập được xử lý ở phía client bằng localStorage.

### Cấu trúc thư mục phiên bản web tĩnh

```
static-version/
├── assets/          # Chứa hình ảnh và font chữ
├── css/             # Các file CSS
├── js/              # Các file JavaScript
├── data/            # Dữ liệu JSON
├── index.html       # Trang chủ
├── cart.html        # Trang giỏ hàng
└── account.html     # Trang tài khoản
```

### Cách sử dụng phiên bản web tĩnh

1. Truy cập trực tiếp vào thư mục `static-version`
2. Mở file `index.html` để xem demo
3. Hoặc deploy lên GitHub Pages để có URL trực tuyến

## Cấu trúc thư mục dự án

```
BanDoGiaDung/
├── admin/           # Trang quản trị
├── assets/          # Tài nguyên (images, fonts)
├── database/        # File kết nối và xử lý database
├── frontend/        # Giao diện người dùng
│   ├── components/  # Các component tái sử dụng
│   ├── css/        # File CSS
│   └── js/         # File JavaScript
└── static-version/  # Phiên bản web tĩnh để demo
```

## Triển khai

### Phiên bản Localhost
1. **Shared Hosting**: Phù hợp với các gói hosting hỗ trợ PHP và MySQL
2. **VPS/Cloud Server**: Có thể triển khai trên các dịch vụ như DigitalOcean, Linode, Vultr
3. **Local Server**: Có thể chạy trên XAMPP, WAMP, MAMP

### Phiên bản Web Tĩnh
- Deploy lên GitHub Pages để có URL demo trực tuyến

## Đóng góp

Mọi đóng góp đều được hoan nghênh. Vui lòng tạo issue hoặc pull request để đóng góp.

## Giấy phép

MIT License 