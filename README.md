# Website Bán Đồ Gia Dụng

Đây là một website bán đồ gia dụng được xây dựng bằng PHP thuần và MySQL, với phiên bản web tĩnh để demo trên GitHub Pages.

## Công nghệ sử dụng

### Backend
- **PHP**: Xử lý logic nghiệp vụ và tương tác với cơ sở dữ liệu
- **MySQL**: Cơ sở dữ liệu lưu trữ thông tin sản phẩm, người dùng, đơn hàng
- **PDO/MySQLi**: Kết nối và truy vấn cơ sở dữ liệu

### Frontend
- **HTML5/CSS3**: Xây dựng giao diện người dùng
- **JavaScript**: Xử lý tương tác người dùng và tương tác với backend
- **AJAX/Fetch API**: Giao tiếp bất đồng bộ với server
- **LocalStorage**: Lưu trữ dữ liệu tạm thời ở phía client (phiên bản web tĩnh)

### Công cụ phát triển
- **Git**: Quản lý mã nguồn
- **GitHub Pages**: Triển khai phiên bản web tĩnh

## Tính năng chính

### Người dùng
- **Đăng ký/Đăng nhập**: Quản lý tài khoản người dùng
- **Xem sản phẩm**: Duyệt và tìm kiếm sản phẩm theo danh mục
- **Chi tiết sản phẩm**: Xem thông tin chi tiết, hình ảnh, đánh giá
- **Giỏ hàng**: Thêm, cập nhật, xóa sản phẩm trong giỏ hàng
- **Đặt hàng**: Tạo đơn hàng từ giỏ hàng
- **So sánh sản phẩm**: So sánh thông tin giữa các sản phẩm
- **Yêu thích**: Lưu sản phẩm yêu thích

### Quản trị viên
- **Quản lý sản phẩm**: Thêm, sửa, xóa sản phẩm
- **Quản lý danh mục**: Quản lý các danh mục sản phẩm
- **Quản lý người dùng**: Xem và quản lý tài khoản người dùng
- **Quản lý đơn hàng**: Xem và cập nhật trạng thái đơn hàng
- **Thống kê doanh thu**: Xem báo cáo doanh thu theo thời gian
- **Quản lý thương hiệu**: Thêm, sửa, xóa thương hiệu sản phẩm

## Cấu trúc dự án

```mermaid
graph TD
    A[BanDoGiaDung] --> B[admin]
    A --> C[frontend]
    A --> D[assets]
    A --> E[database]
    A --> F[static-version]
    
    B --> B1[js]
    B --> B2[php]
    B --> B3[css]
    
    C --> C1[js]
    C --> C2[php]
    C --> C3[css]
    C --> C4[components]
    
    D --> D1[img]
    D --> D2[font]
    
    F --> F1[js]
    F --> F2[css]
    F --> F3[data]
    
    B1 --> B1a[quanli.js]
    B1 --> B1b[quanliuser.js]
    B1 --> B1c[doanhthu.js]
    B1 --> B1d[danhmuc.js]
    B1 --> B1e[thuonghieu.js]
    
    C1 --> C1a[index.js]
    C1 --> C1b[giohang.js]
    C1 --> C1c[chitiet.js]
    C1 --> C1d[danhmuc.js]
    C1 --> C1e[taikhoan.js]
    C1 --> C1f[header.js]
    
    F1 --> F1a[app.js]
    F1 --> F1b[cart.js]
    F1 --> F1c[account.js]
    F1 --> F1d[category.js]
    F1 --> F1e[product-detail.js]
    F1 --> F1f[compare.js]
    F1 --> F1g[contact.js]
```

## Cài đặt phiên bản Localhost

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

### Cách sử dụng phiên bản web tĩnh

1. Truy cập trực tiếp vào thư mục `static-version`
2. Mở file `index.html` để xem demo
3. Hoặc deploy lên GitHub Pages để có URL trực tuyến

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