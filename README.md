# Website Bán Đồ Gia Dụng

Đây là một website bán đồ gia dụng được xây dựng bằng PHP thuần và MySQL, với phiên bản web tĩnh để demo trên GitHub Pages.

## Công nghệ sử dụng

### Frontend
- **HTML5, CSS3**: Xây dựng giao diện người dùng
- **JavaScript thuần**: Xử lý tương tác người dùng, không sử dụng framework
- **Responsive Design**: Tương thích với nhiều thiết bị khác nhau
- **LocalStorage**: Lưu trữ dữ liệu giỏ hàng và thông tin người dùng ở phiên bản tĩnh

### Backend
- **PHP thuần**: Xử lý logic nghiệp vụ và tương tác với cơ sở dữ liệu
- **MySQL**: Lưu trữ dữ liệu sản phẩm, người dùng, đơn hàng
- **Session Management**: Quản lý phiên đăng nhập và giỏ hàng
- **AJAX/Fetch API**: Giao tiếp bất đồng bộ giữa frontend và backend

### Admin Panel
- **PHP**: Xử lý quản lý sản phẩm, người dùng, đơn hàng
- **JavaScript**: Tương tác người dùng trong trang quản trị
- **Chart.js**: Hiển thị biểu đồ thống kê doanh thu
- **Flatpickr**: Chọn khoảng thời gian cho thống kê

## Tính năng chính

### Giao diện người dùng
- 🏠 **Trang chủ**
  - Hiển thị danh sách sản phẩm nổi bật
  - Danh mục sản phẩm cố định khi cuộn trang
  - Slider quảng cáo tự động chuyển động
  - Hiển thị sản phẩm liên quan với nút điều hướng

- 🛍️ **Quản lý sản phẩm**
  - Hiển thị danh sách sản phẩm theo danh mục
  - Tìm kiếm sản phẩm theo tên, thương hiệu, giá
  - Chi tiết sản phẩm với nhiều hình ảnh
  - So sánh sản phẩm (tối đa 4 sản phẩm cùng lúc)
  - Hiển thị giá gốc, giá khuyến mãi và phần trăm giảm giá

- 👤 **Quản lý người dùng**
  - Đăng ký tài khoản mới
  - Đăng nhập/Đăng xuất
  - Quản lý thông tin cá nhân (tên, email, số điện thoại, địa chỉ)
  - Cập nhật ảnh đại diện
  - Xem lịch sử đơn hàng

- 🛒 **Giỏ hàng**
  - Thêm sản phẩm vào giỏ hàng
  - Cập nhật số lượng sản phẩm
  - Xóa sản phẩm khỏi giỏ hàng
  - Chọn/bỏ chọn tất cả sản phẩm
  - Hiển thị tổng tiền, tiền giảm giá
  - Đặt hàng với thông tin giao hàng

- 📦 **Đơn hàng**
  - Tạo mã đơn hàng tự động
  - Lưu thông tin địa chỉ giao hàng
  - Xem trạng thái đơn hàng
  - Hủy đơn hàng

### Trang quản trị
- 👨‍💼 **Quản lý sản phẩm**
  - Thêm, sửa, xóa sản phẩm
  - Upload nhiều hình ảnh sản phẩm
  - Quản lý thông tin chi tiết (giá, số lượng, xuất xứ, bảo hành)
  - Xem chi tiết sản phẩm trong overlay

- 📊 **Thống kê doanh thu**
  - Biểu đồ doanh thu theo ngày
  - Thống kê tổng đơn hàng, số lượng bán, doanh thu
  - Lọc theo khoảng thời gian tùy chọn

- 👥 **Quản lý người dùng**
  - Thêm, sửa, xóa người dùng
  - Phân quyền người dùng
  - Xem chi tiết thông tin người dùng

- 📦 **Quản lý đơn hàng**
  - Xem danh sách đơn hàng
  - Cập nhật trạng thái đơn hàng
  - Xem chi tiết đơn hàng

## Cấu trúc thư mục dự án

```
BanDoGiaDung/
├── admin/           # Trang quản trị
│   ├── css/         # CSS cho trang quản trị
│   ├── js/          # JavaScript cho trang quản trị
│   └── php/         # PHP xử lý cho trang quản trị
├── assets/          # Tài nguyên (images, fonts)
├── database/        # File kết nối và xử lý database
├── frontend/        # Giao diện người dùng
│   ├── components/  # Các component tái sử dụng
│   ├── css/         # File CSS
│   └── js/          # File JavaScript
└── static-version/  # Phiên bản web tĩnh để demo
```

## Cài đặt và sử dụng

### Phiên bản Localhost
1. Clone repository về máy local
2. Import file database vào MySQL
3. Cấu hình kết nối database trong file `database/connect.php`
4. Chạy website thông qua web server (Apache/Nginx)

### Yêu cầu hệ thống
- PHP >= 7.0
- MySQL >= 5.7
- Web server (Apache/Nginx)

### Phiên bản Web Tĩnh
- Truy cập trực tiếp vào thư mục `static-version`
- Mở file `index.html` để xem demo
- Hoặc deploy lên GitHub Pages để có URL trực tuyến

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