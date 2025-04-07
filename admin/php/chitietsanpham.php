<?php
require_once('../php/sanpham.php');
ob_start();
include('../php/quanli.php');
$content = ob_get_clean();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="../../admin/css/details.css">
</head>

<body>
    <div class="details">
        <div class="tieude">Chi tiết sản phẩm</div>
        <?php if (isset($sanpham) && !is_string($sanpham)): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <td><?php echo $sanpham['product_id']; ?></td>
                </tr>
                <tr>
                    <th>Danh mục</th>
                    <td><?php echo $sanpham['category_name']; ?></td>
                </tr>
                <tr>
                    <th>Thương hiệu</th>
                    <td><?php echo $sanpham['brand_name']; ?></td>
                </tr>
                <tr>
                    <th>Tên sản phẩm</th>
                    <td><?php echo $sanpham['product_name']; ?></td>
                </tr>
                <tr>
                    <th>Giá cũ</th>
                    <td><?php echo number_format($sanpham['old_price'],0,',','.'); ?></td>
                </tr>
                <tr>
                    <th>Giá mới</th>
                    <td><?php echo number_format($sanpham['new_price'],0,',','.'); ?></td>
                </tr>
                <tr>
                    <th>Số lượng</th>
                    <td><?php echo $sanpham['quantity']; ?></td>
                </tr>
                <tr>
                    <th>Sản phẩm hot</th>
                    <td><?php
                    $hot =['0'=> 'Khong',
                '1'=>'Yêu Thích']; 
                    echo $hot[$sanpham['is_hot']]; ?></td>
                </tr>
                <tr>
                    <th>Xuất xứ</th>
                    <td><?php echo $sanpham['origin']; ?></td>
                </tr>
                <tr>
                    <th>Ngày bảo hành</th>
                    <td><?php echo $sanpham['warranty_period']; ?></td>
                </tr>
                <tr>
                    <th>Tuỳ chọn bảo hành</th>
                    <td><?php echo $sanpham['warranty_option']; ?></td>
                </tr>
                <tr>
                    <th>Tính năng</th>
                    <td><?php echo $sanpham['features']; ?></td>
                </tr>
                <tr>
                    <th>Chất liệu</th>
                    <td><?php echo $sanpham['material']; ?></td>
                </tr>
                <tr>
                    <th>Mô tả</th>
                    <td><?php echo $sanpham['descriptions']; ?></td>
                </tr>
                <tr>
                    <div class="cuoi">
                        <img class="anh" src="../../assets/img/<?php echo $sanpham['main_image']; ?>" alt="Product Main Image">
                        <img class="anh1" src="../../assets/img/<?php echo $sanpham['extra_image1']; ?>" alt="Product Extra Image 1">
                        <img class="anh2" src="../../assets/img/<?php echo $sanpham['extra_image2']; ?>" alt="Product Extra Image 2">
                    </div>
                </tr>
            </table>
        <?php else: ?>
            <p>Không tìm thấy sản phẩm.</p>
        <?php endif; ?>
    </div>
</body>

</html>