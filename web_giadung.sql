-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 15, 2024 lúc 12:11 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `web_giadung`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_brand`
--

CREATE TABLE `tbl_brand` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_brand`
--

INSERT INTO `tbl_brand` (`brand_id`, `brand_name`) VALUES
(1, 'SUNHOUSEs'),
(2, 'FUJISHI'),
(3, 'XIAOMI'),
(4, 'MAXSUN'),
(5, 'REDHOME'),
(6, 'IKURA'),
(7, 'SEKA'),
(9, 'SUSANNO');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_cart`
--

CREATE TABLE `tbl_cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_cart`
--

INSERT INTO `tbl_cart` (`cart_id`, `user_id`, `product_id`, `quantity`) VALUES
(172, 2, 2, 1),
(175, 2, 3, 1),
(188, 16, 23, 1),
(190, 16, 18, 1),
(199, 1, 16, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_category`
--

CREATE TABLE `tbl_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_category`
--

INSERT INTO `tbl_category` (`category_id`, `category_name`) VALUES
(1, 'Bếp điện'),
(2, 'Tủ lạnh'),
(3, 'Máy nóng lạnh'),
(4, 'Máy hút bụi'),
(5, 'Đồ dùng nhà bếp'),
(6, 'Lò sưởi'),
(7, 'Máy lọc nước');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `order_id` int(11) NOT NULL,
  `transaction_code` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `status` int(11) DEFAULT 0,
  `order_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_orders`
--

INSERT INTO `tbl_orders` (`order_id`, `transaction_code`, `user_id`, `product_id`, `quantity`, `total_price`, `status`, `order_date`) VALUES
(60, 'DH889612', 1, 25, 1, 1400000, 0, '0000-00-00 00:00:00'),
(61, 'DH672789', 1, 16, 1, 2000000, 0, '0000-00-00 00:00:00'),
(62, 'DH672789', 1, 26, 2, 600000, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_problem`
--

CREATE TABLE `tbl_problem` (
  `problem_id` int(11) NOT NULL,
  `problem_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_problem`
--

INSERT INTO `tbl_problem` (`problem_id`, `problem_name`) VALUES
(1, 'Giao diện người dùng'),
(2, 'Thanh toán'),
(3, 'Đăng nhập'),
(4, 'Đăng ký tài khoản'),
(5, 'Tìm kiếm sản phẩm'),
(6, 'Khác');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_product`
--

CREATE TABLE `tbl_product` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT 1,
  `product_name` varchar(100) NOT NULL,
  `old_price` int(11) NOT NULL DEFAULT 0,
  `new_price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `is_hot` int(11) NOT NULL,
  `main_image` varchar(50) NOT NULL,
  `extra_image1` varchar(50) NOT NULL,
  `extra_image2` varchar(50) NOT NULL,
  `origin` varchar(100) DEFAULT 'Vietnam',
  `warranty_period` int(11) DEFAULT 12,
  `warranty_option` varchar(100) DEFAULT 'Default',
  `features` varchar(200) DEFAULT 'None',
  `material` varchar(200) DEFAULT NULL,
  `descriptions` varchar(5000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_product`
--

INSERT INTO `tbl_product` (`product_id`, `category_id`, `brand_id`, `product_name`, `old_price`, `new_price`, `quantity`, `is_hot`, `main_image`, `extra_image1`, `extra_image2`, `origin`, `warranty_period`, `warranty_option`, `features`, `material`, `descriptions`) VALUES
(1, 1, 7, 'Bếp đơn hồng ngoại Sunhouse SHD6011 - Công suất 2000W - Phím bấm điện tử - 5 chế độ nấu - Bảo hàn', 894000, 695000, 345, 1, 'bep1_1.png', 'bep1_2.png', 'bep1_3.png', 'VIệt Nam', 12, 'chính hãng', '5 chế độ nấu', 'Inox 304', 'Nấu ăn nhanh, gọn nhẹ, dễ dàng vệ sinh, an toàn cho người sử dụng, hiệu suất sử dụng cao… chính là những ưu điểm khiến bếp từ ngày càng được ưa chuộng. Nắm bắt được những nhu cầu của xã hội hiện đại, SUNHOUSE đem đến cho bạn chiếc Bếp từ đơn SUNHOUSE SHD6803 với nhiều tính năng ưu việt cùng giá thành hợp lý, chắc chắn sẽ trở thành người bạn đáng tin cậy của các bà nội trợ Việt.\r\n\r\nSản phẩm được trang bị những tính năng an toàn – tiện lợi hàng đầu: mặt kính ceramic cường lực, khóa trẻ em, tự động ngắt khi quá nhiệt và hẹn giờ tắt giúp tiết kiệm thời gian hiệu quả'),
(2, 1, 1, 'Bếp từ đơn Sunhouse SHD6803 - Công suất 2000W - Bảo hành 12 tháng - 8 chế độ nấu - Nút bấm điện tử', 1187000, 989000, 134, 0, 'bep3_3.png', 'bep3_1.png', 'bep3_3.png', 'VIệt Nam', 12, 'chính hãng', 'Nấu ăn nhanh, gọn nhẹ', 'Inox 304', 'Nấu ăn nhanh, gọn nhẹ, dễ dàng vệ sinh, an toàn cho người sử dụng, hiệu suất sử dụng cao… chính là những ưu điểm khiến bếp từ ngày càng được ưa chuộng. Nắm bắt được những nhu cầu của xã hội hiện đại, SUNHOUSE đem đến cho bạn chiếc Bếp từ đơn SUNHOUSE SHD6803 với nhiều tính năng ưu việt cùng giá thành hợp lý, chắc chắn sẽ trở thành người bạn đáng tin cậy của các bà nội trợ Việt.\r\n\r\nSản phẩm được trang bị những tính năng an toàn – tiện lợi hàng đầu: mặt kính ceramic cường lực, khóa trẻ em, tự động ngắt khi quá nhiệt và hẹn giờ tắt giúp tiết kiệm thời gian hiệu quả'),
(3, 1, 2, 'Bếp điện từ Lock & Lock Induction Cooker, 220-240V, 50/60Hz, 2000W - Màu đen EJI411', 2100000, 1300000, 99, 0, 'bep3_1.png', 'bep3_2.png', 'bep3_3.png', 'China', 12, 'chính hãng', 'Nấu ăn nhanh, gọn nhẹ', 'Inox 304', 'Bếp điện từ Lock & Lock Induction Cooker, 220-240V, 50/60Hz, 2000W - Màu đen EJI411\r\nChất liệu:  Thủy tinh, PP, ABS \r\nEJI411 - Bếp điện từ				\r\nCẤU TẠO THIẾT BỊ				\r\nƯU ĐIỂM				\r\n- Mặt bếp làm bằng thủy tinh, dễ dàng làm sạch.				\r\n- Công suất lớn 2000W, nấu ăn nhanh hơn.				\r\n- Bếp có chức năng hẹn giờ, giúp bạn nấu ăn thuận tiện và kiểm soát thời gian nấu dễ dàng.				\r\n- Giao diện điều khiển cảm ứng, dễ thao tác.				\r\n- Quạt turbo giúp làm mát nhanh.				\r\n- Chức năng khóa bảng điều khiển của bếp giúp bạn yên tâm sử dụng dù nhà có con nhỏ.				'),
(4, 1, 3, 'Bếp từ đơn Xiaomi Youth Lite DCL002CM điều chỉnh 9 mức nhiệt - Bếp điện từ núm xoay điều chỉnh, màn ', 1100000, 665000, 898, 1, 'bep4_1.png', 'bep4_2.png', 'bep4_3.png', 'China', 12, 'chính hãng', 'Nấu ăn nhanh, gọn nhẹ', 'Inox 304 kết hợp mặt kính cường lực', 'Bếp từ đơn Xiaomi Youth Lite DCL002CM có thiết kế màu trắng tinh tế và sang trọng, được trang bị 9 mức điều chỉnh nhiệt linh hoạt nâng cấp tản nhiệt, núm xoay điều chỉnh kết hợp màn hình led thông minh, cho người dùng tùy chỉnh để nấu thức ăn.\r\n\r\nĐiểm nổi bật bếp điện từ đơn Xiaomi Youth Lite DCL002CM\r\n✔️ Là bếp điện cảm ứng từ của hãng Xiaomi\r\n✔️ Dễ dàng sử dụng với núm điều chỉnh vặn xoay.\r\n✔️ Linh hoạt điều chỉnh nhiệt 9 mức độ khác nhau\r\n✔️ Công suất định mức lên đến 2100W'),
(5, 1, 3, 'Bếp điện từ đôi Lock&Lock 2800W EJI326WHT', 5000000, 4200000, 56, 0, 'bep5_1.png', 'bep5_2.png', 'bep5_3.png', 'China', 12, 'chính hãng', '5 chế độ nấu', 'Inox 304 kết hợp mặt kính cường lực', 'Bếp điện từ đôi LocknLock 2800W\r\n\r\n- Kích thước : 46cm * 23cm * 6cm (dài x rộng x độ dày)\r\n- Mã sản phẩm: EJI326WHT\r\n- Làm nóng nhanh với công suất bếp trái 1500W, bếp phải 1300W\r\n- Tính năng an toàn, tiện lợi: Hẹn giờ, cài đặt thời gian nấu, khóa trẻ\r\n- Thiết kế mặt bếp pha lê bền chống mài mòn, sáng bóng, cứng, dễ dàng vệ sinh; 2 vùng nấu nâng cao hiệu quả, nấu cùng lúc nhiều\r\n- Phong cách thiết kế hiện đại, nhỏ: thiết kế góc bo tròn làm tăng vẻ đẹp cho căn bếp của bạn'),
(6, 1, 4, 'BẾP HỒNG NGOẠI PERFECT PF-BH82 - Bảo Hành 18 Tháng', 1000000, 550000, 765, 1, 'bep6_1.png', 'bep6_2.png', 'bep6_3.png', 'China', 18, 'chính hãng', 'Nấu ăn nhanh, gọn nhẹ', 'Inox 304 kết hợp mặt kính cường lực', 'BẾP HỒNG NGOẠI PERFECT PF-BH82:\r\n\r\n-4 tính năng: nướng, súp, lẩu, chiên xào\r\n-Chức năng hẹn giờ tiết kiệm thời không cần canh chỉnh\r\n-Sử dụng được tất cả các loại nồi, chảo: gang, inox, thủy tinh, đất, nhôm\r\n-Mặt kính cường lực chịu nhiệt chống trầy xước, dễ vệ sinh\r\n-Màn hình LCD hiển thị thời gian và nhiệt độ giúp quan sát dễ dàng, thao tác chính xác\r\n-Bảng điều khiển cảm ứng bằng tiếng Việt  \r\n-Núm vặn điều chỉnh tăng giảm nhiệt độ\r\n-Quạt tản nhiệt làm mát linh kiện bên trong tăng tuổi thọ\r\n-Nhỏ gọn tiết kiệm diện tích\r\n-Công suất cao nấu ăn nhanh'),
(7, 1, 4, '[NEW] [Tặng kèm nồi inox] Bếp từ đơn Hafele HS-I2711B 536.61.890', 1500000, 1150000, 545, 1, 'bep7_1.png', 'bep7_2.png', 'bep7_3.png', 'VIệt Nam', 12, 'chính hãng', '5 chế độ nấu', 'Inox 304 kết hợp mặt kính cường lực', 'Đặc tính sản phẩm\r\n\r\n• Công suất 2000W\r\n• Hẹn giờ: 3 tiếng\r\n• Điều khiển cảm ứng với 10 mức công suất/nhiệt độ\r\n• Màn hình hiển thị với LED màu đỏ\r\n• Mặt kính pha lê\r\n• Lựa chọn hiển thị nhiệt độ hoặc công suất\r\nChức năng an toàn\r\n• Khóa trẻ em\r\n• Bảo vệ quá nhiệt\r\n• Bảo vệ quá áp\r\nThông tin kĩ thuật\r\n• Điện áp: 220 - 240V\r\n• Tần số: 50/60 Hz\r\n• Kích thước sản phẩm: 270*310*65mm'),
(8, 1, 6, 'Bếp điện từ đôi Elmich ICE-3495OL/3496', 8000000, 5500000, 66, 0, 'bep8_1.png', 'bep8_2.png', 'bep8_3.png', 'China', 24, 'chính hãng', '5 chế độ nấu', 'Inox 304 kết hợp mặt kính cường lực', 'Thông số kỹ thuật\r\n\r\nĐiện áp: 220V~50/60Hz\r\nCông suất bếp bên trái: 2200W\r\nCông suất bếp bên phải: 2200W\r\nCông suất tối đa: 4400W\r\nKích thước mặt kính: 730 x 430 x 80mm\r\nĐường kính bếp trái: ɸ170\r\nĐường kính bếp phải: ɸ170\r\nKích thước âm tủ: 680x395mm\r\nTrong lượng : 8kg\r\nChức năng nồi bật : \r\n- Để dương hay lắp âm đều được.\r\n- Hẹn giờ \r\n- Khóa phím trẻ em\r\n- Nhận diện vùng nấu\r\n- Booster (Tăng công suất tới mức tối đa)'),
(9, 1, 7, 'Bếp từ KAIMEIDI đa năng truyền nhiệt nhanh tiết kiệm điện năng an toàn khi sử dụng và dễ dàng vệ sin', 350000, 300000, 780, 1, 'bep9_1.png', 'bep9_2.png', 'bep9_3.png', 'VIệt Nam', 12, 'chính hãng', '5 chế độ nấu', 'Inox 304', 'MÔ TẢ SẢN PHẨM\r\n\r\nThời gian giao hàng dự kiến cho sản phẩm này là từ 7-9 ngày\r\n\r\nTHÔNG TIN SẢN PHẨM: \r\nChất liệu bảng điều khiển: Bảng pha lê đen \r\nLò cảm ứng: lò đơn \r\nKhả năng chống nước: Có \r\nCông suất: 2200 w \r\nMức tiêu thụ công suất: Mức 3\r\nKích thước sản phẩm: 280 mm * 350 mm \r\nCách hoạt động: màn hình cảm ứng tinh khiết \r\nChiều dài dây: 1 m \r\nChức năng sản phẩm: súp / nước sôi /Chiên / lửa chậm / cháo / cháo, lẩu / hấp, hầm / đặt trước'),
(10, 1, 7, 'Bếp Từ Sunhouse, Bếp Điện Cảm Ứng Thế Hệ Mới Tặng Kèm Nồi Lẩu Chống Khê, Mặt Kính Chịu Lực- Bảo Hành', 300000, 280000, 456, 1, 'bep10_1.png', 'bep10_2.png', 'bep10_3.png', 'VIệt Nam', 12, 'chính hãng', 'Nấu ăn nhanh, gọn nhẹ', 'Inox 304 kết hợp mặt kính cường lực', 'Bếp Từ Sunhouse, Bếp Điện Cảm Ứng Thế Hệ Mới Tặng Kèm Nồi Lẩu Chống Khê, Mặt Kính Chịu Lực- Bảo Hành 12 Tháng (Mẫu 2024)\r\n\r\n1.THÔNG TIN SẢN PHẨM\r\n Mình cũng đang sử dụng dòng bếp này được hơn 4 năm rồi và không có bất kì lỗi gì cả. Model mới nhất được cải tiến con chip nên có chế độ quạt gió lâu hơn bếp của mình, giúp cho bếp tản nhiệt tốt hơn, bền hơn. Dưới đây là những ƯU ĐIỂM mà mình thấy ở bếp này:\r\n- Làm nóng nhanh, tiết kiệm điện\r\n- Gần như không tỏa nhiệt ra ngoài nên mùa hè cũng không sợ nóng\r\n- Tự động tạm dừng khi không có nồi chảo và tự động hoạt động trở lại khi đặt nồi chảo lên bếp\r\n- Tự động ngắt khi quá nhiệt. Ví dụ: bạn dùng chảo để xào rán nhưng lại đặt chế độ nấu lẩu thì khi nhiệt độ cao, bếp sẽ tắt. Bạn phải chọn chế độ xào rán và chỉnh nhiệt độ phù hợp để nấu nhé. Vậy nên rất an toàn.\r\n- Đa chức năng như nấu lẩu, xào, nướng, hấp, hâm sữa,... đã được cài sẵn nhiệt độ hoặc công suất phù hợp, bạn có thể tùy chỉnh cho phù hợp với nhu cầu sử dụng thực tế'),
(11, 1, 7, 'Bếp hồng ngoại cảm ứng cao cấp - Cảm ứng có đèn LED, mặt kính cường lực 3500W - Bảo Hành 24 Tháng', 865000, 555000, 355, 1, 'bep11_1.png', 'bep11_2.png', 'bep11_3.png', 'VIệt Nam', 24, 'chính hãng', '5 chế độ nấu', 'Inox 304 kết hợp mặt kính cường lực', 'ƯU ĐIỂM CỦA Bếp hồng ngoại :\r\n\r\n- Hệ thống điều khiển thông minh với nhiều chế độ nấu: Công suất 3500W nấu nhanh và được thiết kế nhiều chế độ nấu bao gồm: nấu trà, nấu súp, chiên/xào, nấu lẩu, hấp, đun sữa, nấu cháo, nướng, giúp bạn đa dạng món ăn cho gia đình. - - Bên cạnh đó, chức năng hẹn giờ thông minh, cho phép cài đặt thời gian nấu khi bạn bận việc khác. Đặc biệt, bạn có thể sử dụng bếp điện để nướng trực tiếp trên bề mặt bếp.\r\n- Mặt bếp hồng ngoại làm từ kính chịu nhiệt, chịu được nhiệt độ cao, thoải mái nấu nướng mà không lo bếp bị nứt, vỡ.\r\nNhiều chế độ nấu như súp, cháo, nướng, hâm sữa, chưng, nấu trà, chiên, xào, ...\r\n- Không kén nồi kể cả nồi gang, nồi thủy tinh, nồi đất và có thể nướng trực tiếp các món ăn như thịt, cá, hải sản trực tiếp trên mặt bếp.\r\n- Bảng điều khiển của bếp hồng ngoại cảm ứng hiện đại\r\n- Hệ thống tản nhiệt tăng tuổi thọ cho bếp\r\n- Bàn phím nhấn siêu nhạy cả khi tay ướt\r\n- Bảo vệ môi trường : không tạo ra khí CO2 gây đen nồi khi nấu và an toàn cho sức khỏe người dùng.\r\n- An toàn : Cài đặt nhiều chế độ bảo hộ, bảo đảm an toàn.'),
(12, 2, 2, '[Livestream] Tủ lạnh Samsung Inverter 236 lít RT22M4032BY/SV - Miễn phí lắp đặt', 10999000, 8888000, 78, 0, 'tl1_1.png', 'tl1_2.png', 'tl1_3.png', 'VIệt Nam', 12, 'chính hãng', 'Công nghệ Digital Inverter', 'Nhựa cường lực và Kính cường lực', '- Ngăn đông mềm Optimal Fresh Zone\r\nThức ăn của bạn sẽ được bảo quản ở chế độ đông mềm nên không lo bị đông cứng, không đợi rã đông và đảm bảo hương vị tươi ngon trọn vẹn. Ngăn đông mềm được thiết kế để chứa các thực phẩm như thịt cá tươi sống ở nhiệt độ lý tưởng nhất (lên đến -1°C) giữ cho độ tươi ngon lâu hơn gấp 2 lần.\r\n\r\n- Công nghệ Digital Inverter\r\nCông nghệ Digital Inverter tự động điều chỉnh độ lạnh dựa trên nhiệt độ, độ ẩm và thói quen sử dụng với 7 cấp độ linh hoạt. Nhờ đó, giúp tiết kiệm năng lượng tiêu thụ, giảm độ ồn, giảm hao mòn và tăng tuổi thọ máy. Động cơ bảo hành 20 năm đồng hành cùng bạn qua năm tháng\r\n\r\n- Bộ lọc khử mùi tự nhiên được tích hợp giữ không khí trong tủ lạnh luôn tươi mát và thực phẩm trọn vị tươi ngon. Đặc biệt mùi khó chịu sẽ bị loại bỏ hoàn toàn khi đi qua bộ lọc than hoạt tính.\r\n\r\n- Khay kéo linh hoạt\r\nViệc lấy thực phẩm trong tủ lạnh đôi khi gặp trở ngại, nhưng với Khay Linh Hoạt được đặt trên thanh trượt và kéo ra dễ dàng, bạn có thể lưu trữ, sắp xếp gọn gàng và lấy thức ăn nhanh chóng; đồng thời tiếp cận được những thực phẩm ở sâu bên trong.'),
(13, 2, 3, '[NHẬP SSDA11BU GIẢM THÊM 15%]Tủ lạnh Samsung Inverter 236 lít RT22M4032BY/SV - Miễn phí lắp đặt', 6500000, 5400000, 145, 1, 'tl2_1.png', 'tl2_2.png', 'tl2_3.png', 'China', 12, 'chính hãng', 'Làm lạnh nhanh chóng', 'Nhựa cường lực và Kính cường lực', '- Ngăn đông mềm Optimal Fresh Zone\r\nThức ăn của bạn sẽ được bảo quản ở chế độ đông mềm nên không lo bị đông cứng, không đợi rã đông và đảm bảo hương vị tươi ngon trọn vẹn. Ngăn đông mềm được thiết kế để chứa các thực phẩm như thịt cá tươi sống ở nhiệt độ lý tưởng nhất (lên đến -1°C) giữ cho độ tươi ngon lâu hơn gấp 2 lần.\r\n\r\n- Công nghệ Digital Inverter\r\nCông nghệ Digital Inverter tự động điều chỉnh độ lạnh dựa trên nhiệt độ, độ ẩm và thói quen sử dụng với 7 cấp độ linh hoạt. Nhờ đó, giúp tiết kiệm năng lượng tiêu thụ, giảm độ ồn, giảm hao mòn và tăng tuổi thọ máy. Động cơ bảo hành 20 năm đồng hành cùng bạn qua năm tháng\r\n\r\n- Bộ lọc khử mùi tự nhiên được tích hợp giữ không khí trong tủ lạnh luôn tươi mát và thực phẩm trọn vị tươi ngon. Đặc biệt mùi khó chịu sẽ bị loại bỏ hoàn toàn khi đi qua bộ lọc than hoạt tính.'),
(14, 2, 5, 'AQR-D59FA(BS) - Tủ Lạnh Aqua 50 Lít AQR-D59FA (BS) - Bảo Hành Chính Hãng- GIAO TOÀN QUỐC', 1300000, 999000, 775, 1, 'tl3_1.png', 'tl3_2.png', 'tl3_3.png', 'VIệt Nam', 24, 'chính hãng', 'Làm lạnh nhanh chóng', 'Nhựa cường lực và Kính cường lực', 'ĐẶC ĐIỂM NỔI BẬT\r\n-Khay làm bằng kính chịu lực, chứa được nhiều thực phẩm nặng\r\n-Chất liệu cửa tủ lạnh mặt thép bền bỉ, ít bám bẩn và dễ lau chùi\r\n-Đạt chuẩn RoHS đảm bảo thân thiện và an toàn với môi trường\r\n-Làm lạnh trực tiếp giúp thực phẩm tươi ngon, giữ trọn hương vị\r\n-Dung tích 50 lít phù hợp cho sinh viên, khách sạn, n'),
(15, 3, 2, 'Máy tạo nước nóng trực tiệp tại vòi, có vòi sen , điều chỉnh được nóng lạnh, Chống giật mẫu mới GINE', 1100000, 730000, 973, 1, 'nl1_1.png', 'nl1_2.png', 'nl1_3.png', 'China', 12, 'chính hãng', 'Làm nóng tức thì, có thể dùng nước nóng tùy thích, không cần bể chứa', 'Nhựa cường lực và Kính cường lực', '????TÍNH NĂNG, ĐẶC ĐIỂM: \r\n- 3 giây nóng, thực sự ổn định nhiệt mà không cần chờ đợi\r\n-  Nước nóng liên tục trong 24h\r\n- Tự động ngắt điện khi rò rỉ \r\n- Làm nóng tức thì, có thể dùng nước nóng tùy thích, không cần bể chứa.\r\n- Thân máy nhỏ, tiết kiệm năng lượng và hiệu quả cao \r\n- Màn hình hiển thị nhiệt độ kỹ thuật số LCD. \r\n- Cấu trúc không gỉ, bền, thanh lịch và thời trang. \r\n- Máy nước nóng điện này dễ dàng lắp đặt dưới phòng tắm hoặc bồn rửa nhà bếp để rửa bát đĩa, thực phẩm, quần áo, khuôn mặt và các chức năng khác. \r\n- Bình nóng lạnh an toàn, tiết kiệm điện, tiết kiệm nước, không gây ô nhiễm, không khí thải. \r\n- Công tắc cảm ứng áp lực nước phản ứng ứng nhạy, tỷ lệ hỏng hóc thấp.'),
(16, 3, 3, 'Máy Đun Nước Nóng Lạnh Tức Thì Để Bàn Xiiaomi T2, 4 Chế Độ Nhiệt, Dung Tích 3L, Nhỏ Gọn, Tiện Lợi Ph', 2000000, 1700000, 565, 1, 'nl2_1.png', 'nl2_2.png', 'nl2_3.png', 'VIệt Nam', 12, 'chính hãng', 'Làm lạnh nhanh chóng', 'Nhựa cường lực và Kính cường lực', 'TÍNH NĂNG CÚA MÁY ĐUN NƯỚC JMEY\r\n\r\n_ Bình được thiết kế thông minh, cùng với vẻ sang trọng quen thuộc, Xiaomi jmey T2 được cải tiến và trang bị cho mình những tính năng giúp phục vụ người tiêu dùng một cách tối ưu\r\n_  Với dung tích tối ưu, 2.8 lít được cho là vừa tối ưu với mỗi gia đình Việt trung bình\r\n_  Thiết kế bình nước trong suốt, giúp quan sát dễ dàng lượng nước bên trong để không bị thiếu nước nóng khi cần.\r\n_ Có 4 mức nhiệt độ lý tưởng cho máy khi đun nước, đáp ứng các nhu cầu khác nhau vào mỗi thời điểm như pha trà, pha sữa ấm, pha cà phê, hay nước uống thông thường…\r\n_ Có 2 mức nước 200ml và 350ml phù hợp với các mức sử dụng nước khác nhau và tránh lãng phí nước\r\n_ Bình chứa được thiết kế nắp riêng biệt, giúp ngăn ngừa côn trùng bay vào bình chứa.\r\n_ Màn hình led hiển thị nhiệt độ nước giúp người sử dụng dễ dàng nhận biết. Kèm đèn sáng hiển thị chế độ nước đang sử dụng từ nước uống trực tiếp là 25 độ C, pha sữa là 45 độ C, pha trà hoa là 75 độ C và nước sôi là 99 độ C.\r\n_ Đường ống dẫn luôn được tráng qua bằng nước trong bình, tạo độ an toàn vệ sinh khi sử dụng máy.\r\n_ Tự ngắt ngừng hoạt động khi thiếu nước, và báo hiếu hết nước giúp người sử dụng nhận biết dễ dàng.\r\n_ Kích thước gọn nhẹ, bạn có thể đặt máy ở bất cứ đâu bạn muốn, dù là phòng khách, phòng ngủ, phòng bếp, phòng làm việc…'),
(17, 4, 2, 'Máy hút bụi cầm tay Deerma DX118C lực hút 15000pa, công suất 600W với bộ lọc bụi mịn HEPA - Bảo hành', 540000, 430000, 764, 1, 'hb1_1.png', 'hb1_2.png', 'hb1_3.png', 'China', 12, 'chính hãng', 'Vệ sinh nhanh chóng', 'Nhựa cường lực', 'Thông tin chi tiết:\r\n\r\nThương hiệu: Deerma\r\n\r\nModel: DEM DX118C - phiên bản mới nhất\r\n\r\nCông suất: 600W\r\n\r\nLực hút: 15.000pa\r\n\r\nTrọng lượng: 1.82kg	\r\n\r\nChiều dài dây điện: 5m\r\n\r\nKích thước: 59x150x13cm\r\n\r\nDụng lượng bụi : 1.2L'),
(18, 4, 3, 'Robot hút bụi mini JIASHI thông minh đa năng cho gia đình', 350000, 150000, 454, 1, 'hb2_1.png', 'hb2_2.png', 'hb2_3.png', 'China', 12, 'chính hãng', 'Làm lạnh nhanh chóng', 'Nhựa cường lực', 'Chế độ làm việc: cơ khí\r\n\r\nNguồn cung cấp: USB\r\nChức năng hẹn giờ: không hẹn giờ\r\nĐiện áp: 7V\r\nCông suất: 5W\r\nTần số tối đa: 5W\r\nLoại công tắc: Loại bàn phím\r\nChức năng quét: hút và quét\r\nDiện tích sử dụng: 90-120 mét vuông\r\nĐiều khiển từ xa: Không có\r\nLCD: Không\r\nCó chức năng lên lịch cuộc hẹn không: Không\r\nCó hoặc không có tường ảo: có'),
(19, 5, 2, 'Dụng Cụ Đập Dập Hành Tỏi Đồ Dùng Nhà Bếp Bằng Tay - Dập Tỏi Ớt Cán Dài', 90000, 60000, 98, 0, 'dcb1_1.png', 'dcb1_2.png', 'dcb1_3.png', 'China', 12, 'chính hãng', 'xử lý hành tỏi, rau củ', 'Nhựa cường lực', '???? Đặc điểm\r\n- Thiết kế nhỏ gọn, cầm tay tiện lợi\r\n- Bên trong tích hợp từ các lưỡi cắt sắc bén, có thể sử dụng để đập dập hành tỏi, thái lát hoặc băm nhuyễn các loại rau củ.\r\n- Chỉ với một vài thao tác nhấn nhẹ là khách hàng đã có thể hoàn thiện xong các nguyên liệu để chế biến món ăn cực kỳ đơn giản, dễ dàng.\r\n- Chất liệu nhựa và thép không gỉ cao cấp, bền bỉ theo thời gian.\r\n- Các bộ phận của máy giã tỏi ớt có thể tháo rời, thuận tiện mỗi khi lắp đặt và vệ sinh.\r\n- Tiết kiệm thời gian gấp 2 lần so với giã tay thông thường.\r\n- Sử dụng như một công cụ đập dập hành tỏi, rau củ và thực phẩm đa năng.\r\n- Kiểu dáng sang trọng, tinh tế.'),
(20, 5, 4, 'kệ để đồ nhà bếp 2 tầng, kệ để bàn bếp mẫu mới đựng gia vị cao cấp', 0, 55000, 342, 0, 'dcb2_1.png', 'dcb2_2.png', 'dcb2_3.png', 'China', 1, 'chính hãng', 'Không', 'Nhựa cường lực', 'Thông tin sản phẩm: \r\n\r\n1. Tên sản phẩm: Kệ đựng gia vị nhà bếp mẫu mới 2 tầng decor cao cấp, giá để lọ gia vị tiện ích\r\n\r\n2. kích thước: (tham khảo trên ảnh sản phẩm)\r\n\r\n3. Ưu điểm\r\n\r\n- Mẫu kệ đựng gia vị kiểu mới, giúp để chai lọ tiện ích\r\n\r\n- sắp xếp không gian nhà bếp được gọn gàng hơn\r\n\r\n- giá cả tốt hơn các sản phẩm khác trên thị trường\r\n\r\n- thuộc mẫu sản phẩm decor nhà bếp vừa đẹp , giúp trang trí không gian bếp và vừa tiện ích'),
(21, 7, 7, 'Máy Lọc Nước Để Gầm Hòa Phát HPU488, Thiết kế nhỏ gọn 11 Lõi Cao Cấp, BH Tại Nhà 3 Năm', 4999000, 3199000, 45, 0, 'mln1_1.png', 'mln1_2.png', 'mln1_3.png', 'VIệt Nam', 12, 'chính hãng', ' Hệ thống 11 lõi lọc công suất mạnh mẽ 15L/ giờ', 'Nhựa cường lực', 'TẶNG KÈM ĐẦY ĐỦ VẬT TƯ VÀ VIDEO HƯỚNG DẪN LẮP CHI TIẾT RẤT ĐƠN GIẢN DỄ LẮP\r\n\r\n<<<Khách có nhu cầu lắp đặt nhắn cho shop có ktv hãng qua lắp đặt có tính phí>>>\r\n\r\nTHÔNG SỐ KỸ THUẬT MÁY LỌC NƯỚC Hòa Phát U488 - 11 LÕI MẪU MỚI 2024\r\n\r\nTính năng sản phẩm:- Hệ thống 11 lõi lọc công suất mạnh mẽ 15L/ giờ\r\n\r\n- 4 lõi lọc thô đúc liền thay nhanh tiện lợi giúp người dùng thay lõi dễ dàng\r\n\r\n- Nước sau lọc đạt chuẩn quốc gia nước uống trực tiếp tại vòi theo QCVN 6-1:2010/BYT của Bộ y tế\r\n\r\n- Màng lọc RO 75 GPD nhập khẩu Hàn Quốc (công nghệ tấm màng từ Mỹ) đạt tiêu chuẩn quốc tế NSF/ ANSI 58 giúp loại bỏ 99.99% vi khuẩn, vi rút, kim loại nặng, hóa chất độc hại trong nước\r\n\r\n- Thiết kế tiện dụng, có thể linh hoạt ứng dụng \"để gầm\" hoặc \"để bàn\"- Bổ sung khoáng chất với 6 lõi chức năng- Bảo hành vượt trội từng linh kiện lên tới 36 tháng'),
(22, 7, 5, '[MIỄN PHÍ LẮP ĐẶT] Máy lọc nước RO 6 lõi Kangaroo KGRP68EC - không vỏ tủ', 4990000, 3450000, 34, 0, 'mln2_1.png', 'mln2_2.png', 'mln2_3.png', 'China', 12, 'chính hãng', ' Hệ thống 11 lõi lọc công suất mạnh mẽ 15L/ giờ', 'Nhựa cường lực', 'Máy lọc nước chân quỳ Kangaroo KGRP68EC là lựa chọn tối ưu cho hộ gia đình ưa tối giản, gọn nhẹ và muốn cải thiện nguồn nước sinh hoạt. Máy có kiểu dáng tinh gọn, chiều sâu chỉ 20.2cm hơn một gang tay. Hoạt động mạnh mẽ với cụm 6 lõi lọc chức năng, mang đến nguồn nước sạch khỏe  giúp ngăn chặn lão hóa và cân bằng độ PH trong cơ thể.  \r\n\r\nTối ưu diện tích cho căn nhà\r\nKích thước: 373x202x454 mm\r\nMáy lọc nước chân quỳ Kangaroo KGRP68EC có chiều sâu là 20.2cm chỉ bằng một gang tay, chiều cao 45.4cm và chiều dài 37.3cm, dễ dàng lắp đặt tại mọi nơi dưới gầm tủ của gia đình, nhằm tối ưu diện tích cho căn nhà.'),
(23, 1, 4, 'Bếp Từ Đơn Tròn FIKA NEOFLAM Hàn Quốc Cao Cấp', 0, 999000, 47, 0, 'bep12_1.png', 'bep12_2.png', 'bep12_3.png', 'China', 12, 'chính hãng', '5 chế độ nấu', 'Nhựa cường lực và Kính cường lực', '* HƯỚNG DẪN SỬ DỤNG\r\n- Khi cắm dây nguồn, nút điều chỉnh hiện lên chữ C, chạm tay vào nút nguồn để bật bếp\r\n- Chạm tay vào nút chức năng để chọn công suất, vặn nút điều chỉnh sang trái hoặc phải để chọn công suất từ P1 đến P9 (Tương đương từ 200W tới 2000W)\r\n\r\n- Chạm vào nút chức năng để chọn nhiệt độ và vặnn nút điều chỉnh để chọn nhiệt độ từ C1 đến C9 (tương đương từ 60°C tới 220°C).\r\n- Nút khoá để khoá nhiệt độ\r\n- Nút đồng hồ để hẹn giờ\r\n- Chỉ sử dụng được với nồi chuyên dụng cho bếp từ'),
(24, 4, 2, 'PerySmith V20Pro Máy Hút Bụi Giường Nệm Cầm Tay Không Dây Gọn Nhẹ Diệt Khuẩn Hút Bụi Mịn Có Đèn UV', 2100000, 1300000, 676, 1, 'hb3_1.png', 'hb3_2.png', 'hb3_3.png', 'VIệt Nam', 12, 'chính hãng', 'Làm sạch bụi bẩn nhanh chóng', 'Nhựa cường lực', 'PERYSMITH DUSTMITE SERIES - V20PRO - 2 CHẾ ĐỘ HÚt\r\n\r\nGiúp việc dọn dẹp của bạn trở nên đơn giản hơn với sản phẩm máy hút bụi giường nệm không dây V20Pro của PerySmith, với đèn UV-C 12W, công nghệ sóng siêu âm Miteless, lực hút 15.000PA, 60.000 nhịp mỗi phút, sấy khí nóng 65°C để vô hiệu hóa hoàn toàn mạt bụi và đảm bảo môi trường trong sạch và lành mạnh.\r\n\r\nCÔNG NGHỆ ĐÈN UV-C DIỆT KHUẨN 12W MẠNH MẼ\r\nSản phẩm được trang bị hệ thống đèn UV-C 253,7mm hiệu suất cao, đảm bảo tỷ lệ tiêu diệt rệp, ve bọ, vi sinh vật và vi khuẩn lên đến 99,7%\r\n\r\nĐỘNG CƠ TĂNG CƯỜNG 500W VỚI CÔNG SUẤT HÚT 15000PA\r\nĐảm bảo hút sạch bụi mịn và trứng bọ rệp trên bề mặt cần vệ sinh'),
(25, 2, 6, 'Tủ lạnh mini 2 cửa nội địa Trung, bảo hành 12th, tiết kiệm điện, phù hợp phòng trọ Forher.since1998', 0, 1400000, 344, 1, 'tl4_1.png', 'tl4_2.png', 'tl4_3.png', 'China', 12, 'chính hãng', 'Làm mát thực phẩm', 'Nhựa cường lực và Kính cường lực', 'ƯU ĐIỂM SẢN PHẨM\r\nTiết kiệm không gian: Kích thước nhỏ gọn, phù hợp cho căn hộ nhỏ, phòng trọ, văn phòng.\r\nTiết kiệm điện năng: Công suất tiêu thụ thấp, thường sử dụng công nghệ inverter.\r\nThiết kế thẩm mỹ: Kiểu dáng hiện đại, màu sắc đa dạng, dễ dàng vệ sinh.\r\nGiá cả phải chăng: Phù hợp với ngân sách của nhiều đối tượng.\r\nDễ sử dụng: Hệ thống điều khiển đơn giản, bố trí ngăn kệ hợp lý.\r\nThích hợp cho không gian cá nhân: Hoạt động êm ái, lý tưởng cho phòng ngủ hoặc văn phòng.\r\nHƯỚNG DẪN BẢO QUẢN\r\nVị trí đặt tủ: Đặt nơi thoáng mát, tránh nguồn nhiệt và ánh nắng.\r\nSắp xếp thực phẩm: Không quá tải, phân loại đúng ngăn.\r\nĐiều chỉnh nhiệt độ: Ngăn mát 3-5°C, ngăn đông -18°C.\r\nVệ sinh định kỳ: Lau sạch bên trong và gioăng cửa.\r\nKiểm tra và xả đá: Kiểm tra lớp đá tuyết, xả đá khi cần.\r\nKiểm tra bảo dưỡng: Đảm bảo hệ thống làm lạnh hoạt động tốt.\r\nSử dụng hợp lý: Mở tủ ít và nhanh, để nguội thực phẩm trước khi cất.'),
(26, 1, 7, 'Bếp từ KAIMEIDI đa năng truyền nhiệt nhanh tiết kiệm điện năng', 300000, 200000, 700, 0, 'bep9_1.png', 'bep9_2.png', 'bep9_3.png', 'VIệt Nam', 12, 'chính hãng', '5 chế độ nấu', 'Inox 304', 'MÔ TẢ SẢN PHẨM\r\n\r\nThời gian giao hàng dự kiến cho sản phẩm này là từ 7-9 ngày\r\n\r\nTHÔNG TIN SẢN PHẨM: \r\nChất liệu bảng điều khiển: Bảng pha lê đen \r\nLò cảm ứng: lò đơn \r\nKhả năng chống nước: Có \r\nCông suất: 2200 w \r\nMức tiêu thụ công suất: Mức 3\r\nKích thước sản phẩm: 280 mm * 350 mm \r\nCách hoạt động: màn hình cảm ứng tinh khiết \r\nChiều dài dây: 1 m \r\nChức năng sản phẩm: súp / nước sôi /Chiên / lửa chậm / cháo / cháo, lẩu / hấp, hầm / đặt trước'),
(27, 2, 3, 'Tủ lạnh Samsung Inverter 236 lít RT22M4032BY/SV - Miễn phí lắp đặt', 10000000, 8000000, 78, 0, 'tl1_1.png', 'tl1_2.png', 'tl1_3.png', 'VIệt Nam', 12, 'chính hãng', 'Công nghệ Digital Inverter', 'Nhựa cường lực và Kính cường lực', '- Ngăn đông mềm Optimal Fresh Zone\r\nThức ăn của bạn sẽ được bảo quản ở chế độ đông mềm nên không lo bị đông cứng, không đợi rã đông và đảm bảo hương vị tươi ngon trọn vẹn. Ngăn đông mềm được thiết kế để chứa các thực phẩm như thịt cá tươi sống ở nhiệt độ lý tưởng nhất (lên đến -1°C) giữ cho độ tươi ngon lâu hơn gấp 2 lần.\r\n\r\n- Công nghệ Digital Inverter\r\nCông nghệ Digital Inverter tự động điều chỉnh độ lạnh dựa trên nhiệt độ, độ ẩm và thói quen sử dụng với 7 cấp độ linh hoạt. Nhờ đó, giúp tiết kiệm năng lượng tiêu thụ, giảm độ ồn, giảm hao mòn và tăng tuổi thọ máy. Động cơ bảo hành 20 năm đồng hành cùng bạn qua năm tháng\r\n\r\n- Bộ lọc khử mùi tự nhiên được tích hợp giữ không khí trong tủ lạnh luôn tươi mát và thực phẩm trọn vị tươi ngon. Đặc biệt mùi khó chịu sẽ bị loại bỏ hoàn toàn khi đi qua bộ lọc than hoạt tính.\r\n\r\n- Khay kéo linh hoạt\r\nViệc lấy thực phẩm trong tủ lạnh đôi khi gặp trở ngại, nhưng với Khay Linh Hoạt được đặt trên thanh trượt và kéo ra dễ dàng, bạn có thể lưu trữ, sắp xếp gọn gàng và lấy thức ăn nhanh chóng; đồng thời tiếp cận được những thực phẩm ở sâu bên trong.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_report`
--

CREATE TABLE `tbl_report` (
  `report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `problem_id` int(11) NOT NULL,
  `report_content` text NOT NULL,
  `report_image` varchar(255) DEFAULT NULL,
  `report_date` datetime NOT NULL,
  `report_status` enum('pending','processing','resolved') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_report`
--

INSERT INTO `tbl_report` (`report_id`, `user_id`, `problem_id`, `report_content`, `report_image`, `report_date`, `report_status`) VALUES
(1, 1, 1, 'Lỗi giao diện', 'dien.png', '2024-11-15 17:28:52', 'pending'),
(11, 17, 6, '<body>\r\n      <p><strong>Người gửi:</strong> Nguyễn Đức duong </p>\r\n      <p><strong>Số điện thoại:</strong> 0353991328</p>\r\n      <p><strong>Email:</strong> z0329563594@gmail.com</p>\r\n      <p><strong>Nội dung:</strong>1233333333333333333333333</p>\r\n    </body>', NULL, '2024-11-15 17:53:00', 'pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_searchhistory`
--

CREATE TABLE `tbl_searchhistory` (
  `searchHistory_id` int(11) NOT NULL,
  `searchHistory_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_searchhistory`
--

INSERT INTO `tbl_searchhistory` (`searchHistory_id`, `searchHistory_name`) VALUES
(7, 'Tủ lạnh'),
(8, 'Bếp'),
(9, 'máy hút bụi');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) DEFAULT NULL,
  `user_phone` varchar(20) NOT NULL,
  `user_email` varchar(50) NOT NULL,
  `user_address` varchar(100) DEFAULT NULL,
  `user_account` varchar(50) NOT NULL,
  `user_pass` varchar(50) NOT NULL,
  `user_picture` varchar(50) DEFAULT NULL,
  `rule` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Đang đổ dữ liệu cho bảng `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `user_name`, `user_phone`, `user_email`, `user_address`, `user_account`, `user_pass`, `user_picture`, `rule`) VALUES
(1, 'Admin123', '0353991328', 'admin@gmail.com', 'Hà Nội', 'admin', 'admin', 'Screenshot 2024-03-05 224313.png', 1),
(2, 'Bùi Xuân Phương Thiện ', '0353991327', 'buithien@gmail.com', 'Sơn la', 'user1', 'user1', 'FB_IMG_1709553128156 (1).jpeg', 0),
(16, 'ádasd', '0389792670', 'a@gmail.com', '222', '123123', '1231', 'duong.png', 0),
(17, 'Khách', '', '', NULL, 'Khách', 'Khách', NULL, 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tbl_brand`
--
ALTER TABLE `tbl_brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Chỉ mục cho bảng `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Chỉ mục cho bảng `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `tbl_problem`
--
ALTER TABLE `tbl_problem`
  ADD PRIMARY KEY (`problem_id`);

--
-- Chỉ mục cho bảng `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Chỉ mục cho bảng `tbl_report`
--
ALTER TABLE `tbl_report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `problem_id` (`problem_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `tbl_searchhistory`
--
ALTER TABLE `tbl_searchhistory`
  ADD PRIMARY KEY (`searchHistory_id`);

--
-- Chỉ mục cho bảng `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tbl_brand`
--
ALTER TABLE `tbl_brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- AUTO_INCREMENT cho bảng `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT cho bảng `tbl_problem`
--
ALTER TABLE `tbl_problem`
  MODIFY `problem_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `tbl_report`
--
ALTER TABLE `tbl_report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `tbl_searchhistory`
--
ALTER TABLE `tbl_searchhistory`
  MODIFY `searchHistory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD CONSTRAINT `tbl_cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`product_id`),
  ADD CONSTRAINT `tbl_cart_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`);

--
-- Các ràng buộc cho bảng `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD CONSTRAINT `tbl_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`),
  ADD CONSTRAINT `tbl_orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tbl_product` (`product_id`);

--
-- Các ràng buộc cho bảng `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD CONSTRAINT `tbl_product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `tbl_category` (`category_id`),
  ADD CONSTRAINT `tbl_product_ibfk_2` FOREIGN KEY (`brand_id`) REFERENCES `tbl_brand` (`brand_id`);

--
-- Các ràng buộc cho bảng `tbl_report`
--
ALTER TABLE `tbl_report`
  ADD CONSTRAINT `tbl_report_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `tbl_problem` (`problem_id`),
  ADD CONSTRAINT `tbl_report_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;