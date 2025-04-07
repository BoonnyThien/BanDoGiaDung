<?php
require_once('../../admin/php/donhang.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lí đơn hàng</title>
    <link rel="stylesheet" href="../../admin/css/quanli.css">
    <style>
        .bto .bt form {
            margin: 0;
        }

        .update-btn,
        .delete-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 0;
            color: black;
            background-color: rgb(249, 172, 157);
            transition: 0.3s all ease-in;
        }
        .update-btn:hover {
            background-color: #45a049;
            color: white;
        }

        .delete-btn:hover {
            background-color: #da190b;
            color: white;

        }
    </style>
</head>

<body>
    <div id="notification" class="error" style="display: <?php echo isset($_SESSION['errors']) ? 'block' : 'none'; ?>">
        <?php echo isset($_SESSION['errors']) ? $_SESSION['errors'] : ''; ?>
    </div>
    <?php unset($_SESSION['errors']); ?>

    <div class="left">
        <a href="#" class="delete-link" onclick="submitDeleteForm()">Xóa đã chọn</a>
        <nav class="left">
            <form method="GET" action="quanlidonhang.php">
                <div class="leftsearch">
                    <p>Tìm kiếm theo </p>
                    <select name="search_column">
                        <option value="donhang_id" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'order_id' ? 'selected' : ''; ?>>ID</option>
                        <option value="magiaodich" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'transaction_code' ? 'selected' : ''; ?>>Mã giao dịch</option>
                        <option value="user_name" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'user_name' ? 'selected' : ''; ?>>Người dùng</option>
                        <option value="sanpham_name" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'product_name' ? 'selected' : ''; ?>>Sản phẩm</option>
                        <option value="soluong_mua" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'quantity' ? 'selected' : ''; ?>>Số lượng mua</option>
                        <option value="thanhtien" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'total_price' ? 'selected' : ''; ?>>Thành tiền</option>
                        <option value="trangthai" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'status' ? 'selected' : ''; ?>>Trạng thái</option>
                    </select>
                    <input type="text" name="search" placeholder="Tìm kiếm..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                </div>
                <div class="leftsort">
                    <p>Sắp xếp theo</p>
                    <select name="sort">
                        <option value="donhang_id" <?php echo $sort == 'order_id' ? 'selected' : ''; ?>>ID</option>
                        <option value="magiaodich" <?php echo $sort == 'transaction_code' ? 'selected' : ''; ?>>Mã giao dịch</option>
                        <option value="user_name" <?php echo $sort == 'user_name' ? 'selected' : ''; ?>>Người dùng</option>
                        <option value="sanpham_name" <?php echo $sort == 'product_id_name' ? 'selected' : ''; ?>>Sản phẩm</option>
                        <option value="soluong_mua" <?php echo $sort == 'quantity' ? 'selected' : ''; ?>>Số lượng mua</option>
                        <option value="thanhtien" <?php echo $sort == 'total_price' ? 'selected' : ''; ?>>Thành tiền</option>
                        <option value="trangthai" <?php echo $sort == 'status' ? 'selected' : ''; ?>>Trạng thái</option>
                    </select>
                    <select name="order">
                        <option value="ASC" <?php echo $order == 'ASC' ? 'selected' : ''; ?>>Tăng dần</option>
                        <option value="DESC" <?php echo $order == 'DESC' ? 'selected' : ''; ?>>Giảm dần</option>
                    </select>
                </div>
                <button type="submit">Lọc</button>
            </form>
        </nav>
    </div>

    <form method="POST" action="quanlidonhang.php">
        <table class="order">
            <thead>
                <tr>
                    <th></th>
                    <th><a href="quanlidonhang.php?sort=order_id&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">ID</a></th>
                    <th><a href="quanlidonhang.php?sort=transaction_code&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Mã giao dịch</a></th>
                    <th><a href="quanlidonhang.php?sort=quantity&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Số lượng mua</a></th>
                    <th><a href="quanlidonhang.php?sort=total_price&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Thành tiền</a></th>
                    <th><a href="quanlidonhang.php?sort=order_date&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Ngày mua</a></th>
                    <th><a href="quanlidonhang.php?sort=trangthai&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Trạng thái</a></th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($orders)): ?>
                    <tr>
                        <td><input type="checkbox" name="selected_ids[]" value="<?php echo $row['order_id']; ?>"></td>
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo $row['transaction_code']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo number_format($row['total_price'],0,',','.'); ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                        <td><?php 
                        $st = ['0'=> 'Chờ xác nhận',
                        '1'=>'Xác Nhận'];
                        echo $st[$row['status']]; ?></td>
                        <td class="bto">
                            <button class="bt chitiet">
                                <a href="#" onclick="showOverlay(<?php echo $row['order_id']; ?>)">Chi tiết</a>
                            </button>
                            <form method="POST" action="quanlidonhang.php" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <input type="hidden" name="delete_order" value="1">
                                <button type="submit" class="bt delete-btn" onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này?')">
                                    <strong>Xóa</strong>
                                </button>
                            </form>
                            <form method="POST" action="quanlidonhang.php" style="display: inline;">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <input type="hidden" name="update_status" value="1">
                                <button type="submit" class="bt update-btn" onclick="return confirm('Bạn có chắc muốn thay đổi trạng thái?')">
                                    <?php echo $row['status'] == 0 ? '<strong>Xác Nhận</strong>' : '<strong>Hủy</strong> '; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <input type="submit" id="delete_selected" name="delete_selected" value="Xóa đã chọn" style="display:none">
    </form>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>">Trang trước</a>
        <?php endif; ?>
        <div class="middle">
            <?php
            $range = 3; // phạm vi trang
            $start = max(1, $page - $range);
            $end = min($total_pages, $page + $range);
            for ($i = $start; $i <= $end; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>">Trang sau</a>
        <?php endif; ?>
        <div id="overlay" class="overlay">
            <div class="overlay-content">
                <iframe id="iframe-detail" src="" frameborder="0" width="100%" height="100%"></iframe>
                <button class="dong" onclick="hideOverlay()">Đóng</button>
            </div>
        </div>
</body>
<script src="../../admin/js/doanhthu.js"></script>

</html>