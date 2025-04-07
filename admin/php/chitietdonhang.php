<?php
require_once('../php/donhang.php');
ob_start();
include('../php/quanlidonhang.php');
$content = ob_get_clean();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <link rel="stylesheet" href="../../admin/css/details.css">
</head>
<body>
    <div class="details">
        <div class="tieude">Chi tiết đơn hàng</div>
        <?php if (isset($donhang) && !is_string($donhang)): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <td><?php echo $donhang['order_id']; ?></td>
                </tr>
                <tr>
                    <th>Mã giao dịch</th>
                    <td><?php echo $donhang['transaction_code']; ?></td>
                </tr>
                <tr>
                    <th>Người Mua</th>
                    <td><?php echo $donhang['user_name']; ?></td>
                </tr>
                <tr>
                    <th>Đia chỉ</th>
                    <td><?php echo $donhang['user_address']; ?></td>
                </tr>
                <tr>
                    <th>Sản phẩm</th>
                    <td><?php echo $donhang['product_name']; ?></td>
                </tr>
                <tr>
                    <th>Giá</th>
                    <td><?php echo number_format($donhang['new_price'],0,',','.'); ?>đ</td>
                </tr>
                <tr>
                    <th>Số lượng mua</th>
                    <td><?php echo $donhang['quantity']; ?></td>
                </tr>
                <tr>
                    <th>Thành tiền</th>
                    <td><?php echo number_format($donhang['total_price'],0,',','.') ?>đ</td>
                </tr>
                <tr>
                    <th>Ngày Mua</th>
                    <td><?php echo $donhang['order_date']; ?></td>
                </tr>
                <tr>
                    <th>Trạng thái</th>
                    <td><?php 
                    $st = ['0'=> 'Chờ xác nhận',
                    '1'=>'Xác Nhận'];
                    echo $st[$donhang['status']]; ?></td>
                </tr>
            </table>
        <?php else: ?>
            <p>Không tìm thấy đơn hàng.</p>
        <?php endif; ?>
    </div>
</body>
</html>
