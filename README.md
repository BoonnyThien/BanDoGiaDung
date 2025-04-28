# Website Bán Đồ Gia Dụng

Đây là một website bán đồ gia dụng được xây dựng bằng PHP thuần và MySQL, với phiên bản web tĩnh để demo trên GitHub Pages.

## Công nghệ sử dụng

### Backend
- **PHP**: Xử lý logic nghiệp vụ và tương tác với cơ sở dữ liệu
- **MySQL**: Cơ sở dữ liệu lưu trữ thông tin sản phẩm, người dùng, đơn hàng
- **PDO/MySQLi**: Kết nối và truy vấn cơ sở dữ liệu
- **GraphQL Mock**: Giả lập API động cho phiên bản web tĩnh

### Frontend
- **HTML5/CSS3**: Xây dựng giao diện người dùng
- **JavaScript**: Xử lý tương tác người dùng và tương tác với backend
- **AJAX/Fetch API**: Giao tiếp bất đồng bộ với server
- **LocalStorage**: Lưu trữ dữ liệu tạm thời ở phía client (phiên bản web tĩnh)
- **Service Worker**: Hỗ trợ offline và caching
- **Web Workers**: Xử lý tác vụ nặng (tìm kiếm, xử lý dữ liệu) trong background
- **Lazy Loading**: Tối ưu tải trang và tài nguyên
- **Code Splitting**: Chia nhỏ code để tải theo nhu cầu

### Công cụ phát triển
- **Git**: Quản lý mã nguồn
- **GitHub Pages**: Triển khai phiên bản web tĩnh
- **MSW (Mock Service Worker)**: Giả lập API cho môi trường phát triển

## Kiến trúc dự án

```mermaid
graph TD
    subgraph "Frontend"
        A1[HTML5/CSS3] --> A2[JavaScript]
        A2 --> A3[Fetch API]
        A2 --> A4[LocalStorage]
        A2 --> A5[Service Worker]
        A2 --> A6[Web Workers]
        A2 --> A7[Lazy Loading]
        A2 --> A8[Code Splitting]
        A3 --> A9[Giao diện người dùng]
        A4 --> A9
        A5 --> A9
        A6 --> A9
        A7 --> A9
        A8 --> A9
    end
    
    subgraph "Backend"
        B1[PHP] --> B2[PDO/MySQLi]
        B2 --> B3[MySQL Database]
        B1 --> B4[Xử lý logic nghiệp vụ]
        B4 --> B5[API Endpoints]
        B5 --> B6[GraphQL Mock]
    end
    
    subgraph "Tính năng người dùng"
        C1[Đăng ký/Đăng nhập]
        C2[Xem sản phẩm]
        C3[Giỏ hàng]
        C4[Đặt hàng]
        C5[So sánh sản phẩm]
        C6[Yêu thích]
        C7[Tìm kiếm nâng cao]
    end
    
    subgraph "Tính năng quản trị"
        D1[Quản lý sản phẩm]
        D2[Quản lý danh mục]
        D3[Quản lý người dùng]
        D4[Quản lý đơn hàng]
        D5[Thống kê doanh thu]
        D6[Quản lý thương hiệu]
    end
    
    A9 --> B5
    B5 --> A3
    B6 --> A3
    
    B5 --> C1
    B5 --> C2
    B5 --> C3
    B5 --> C4
    B5 --> C5
    B5 --> C6
    B5 --> C7
    
    B5 --> D1
    B5 --> D2
    B5 --> D3
    B5 --> D4
    B5 --> D5
    B5 --> D6
    
    B3 --> B2
```

## Tính năng chính

### Người dùng
- **Đăng ký/Đăng nhập**: Quản lý tài khoản người dùng
- **Xem sản phẩm**: Duyệt và tìm kiếm sản phẩm theo danh mục
- **Chi tiết sản phẩm**: Xem thông tin chi tiết, hình ảnh, đánh giá
- **Giỏ hàng**: Thêm, cập nhật, xóa sản phẩm trong giỏ hàng
- **Đặt hàng**: Tạo đơn hàng từ giỏ hàng
- **So sánh sản phẩm**: So sánh thông tin giữa các sản phẩm
- **Yêu thích**: Lưu sản phẩm yêu thích
- **Tìm kiếm nâng cao**: Tìm kiếm sản phẩm với nhiều tiêu chí
- **Offline Mode**: Xem sản phẩm và giỏ hàng khi không có internet

### Quản trị viên
- **Quản lý sản phẩm**: Thêm, sửa, xóa sản phẩm
- **Quản lý danh mục**: Quản lý các danh mục sản phẩm
- **Quản lý người dùng**: Xem và quản lý tài khoản người dùng
- **Quản lý đơn hàng**: Xem và cập nhật trạng thái đơn hàng
- **Thống kê doanh thu**: Xem báo cáo doanh thu theo thời gian
- **Quản lý thương hiệu**: Thêm, sửa, xóa thương hiệu sản phẩm

## Tối ưu hiệu suất

### Lazy Loading
- **Hình ảnh**: Tải hình ảnh khi chúng xuất hiện trong viewport
- **Component**: Tải các component không cần thiết ngay lập tức
- **Tài nguyên**: Tải các tài nguyên (CSS, JS) theo nhu cầu

### Code Splitting
- **Route-based**: Chia code theo route
- **Component-based**: Chia code theo component
- **Dynamic Import**: Tải module động khi cần

### Web Workers
- **Xử lý tìm kiếm**: Chạy tìm kiếm trong background thread
- **Xử lý dữ liệu**: Xử lý dữ liệu lớn không block UI
- **Cache Management**: Quản lý cache trong background

### Service Worker
- **Offline Support**: Hỗ trợ xem sản phẩm offline
- **Caching Strategy**: Cache-first cho tài nguyên tĩnh
- **Background Sync**: Đồng bộ dữ liệu khi có kết nối

### GraphQL Mock
- **API Simulation**: Giả lập API cho môi trường phát triển
- **Schema Definition**: Định nghĩa schema cho dữ liệu
- **Mock Data**: Tạo dữ liệu giả cho testing

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