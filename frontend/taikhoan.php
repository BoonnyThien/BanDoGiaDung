<?php
include_once('../database/connect.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop đồ gia dụng</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"> -->
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link rel="stylesheet" href="./css/message.css">
    <link rel="stylesheet" href="./css/taikhoan.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="../assets/font/fontawesome-free-6.6.0-web/css/all.min.css">
</head>

<body>

    <?php
    include("components/header.php");
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    $sql_user = mysqli_query($con, "SELECT * FROM tbl_user WHERE user_id = $user_id");
    $row_user = mysqli_fetch_array($sql_user);
    $sql_order = mysqli_query($con, "SELECT * FROM tbl_orders WHERE user_id = $user_id ORDER BY order_id ASC");
    ?>

    <div style="padding-top: 130px; padding-bottom: 50px; min-height: 500px; background-color: #f5f5f5;">
        <div class="app__taikhoan">
            <div class="grid">
                <div class="grid__row">
                    <div class="grid__column-2">
                        <div class="category" style="background-color: #f5f5f5; padding-top: 20px;">

                            <h3 class="category__heading">
                                <i class="category__heading-icon fa-solid fa-user-pen"></i>
                                Admin
                            </h3>
                            <ul class="category-list">
                                <li class="category-item category-item--active">
                                    <div class="category-item__link">
                                        <i class="fa-regular fa-user" style="min-width: 20px;"></i>
                                        Tài khoản của tôi
                                    </div>
                                </li>
                                <li class="category-item ">
                                    <div class="category-item__link">
                                        <i class="fa-regular fa-calendar-days" style="min-width: 20px;"></i>
                                        Đơn mua
                                    </div>
                                </li>
                                <li class="category-item ">
                                    <div class="category-item__link">
                                        <i class="fa-regular fa-bell" style="min-width: 20px;"></i>
                                        Thông báo
                                    </div>
                                </li>
                                <li class="category-item ">
                                    <div class="category-item__link">
                                        <i class="fa-solid fa-ticket" style="min-width: 20px;"></i>
                                        Kho voucher
                                    </div>
                                </li>
                                <li class="category-item ">
                                    <div class="category-item__link">
                                        <i class="fa-brands fa-bitcoin" style="min-width: 20px;"></i>
                                        xu shopee
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="grid__column-10">
                        <div class="account_wrap">
                            <div class="account_title">
                                <p>Hồ sơ của tôi</p>
                                <span>Quản lý thông tin hồ sơ để bảo mật tài khoản</span>
                            </div>
                            <div class="account_content grid__row" style="width: 100%;">
                                <div class="grid__column-8">
                                    <div class="account_wrap-child">
                                        <p>Tên : </p>
                                        <input id="account_name" type="text" value="<?php echo $row_user['user_name']; ?>">
                                    </div>
                                    <div class="account_wrap-child">
                                        <p>Phone : </p>
                                        <input id="account_phone" type="tel" value="<?php echo $row_user['user_phone']; ?>">
                                    </div>
                                    <div class="account_wrap-child">
                                        <p>Email : </p>
                                        <input id="account_email" type="email" value="<?php echo $row_user['user_email']; ?>">
                                    </div>
                                    <div class="account_wrap-child">
                                        <p>Address : </p>
                                        <input id="account_address" type="text" value="<?php echo $row_user['user_address']; ?>">
                                    </div>
                                    <div class="account_wrap-child">
                                        <p>Tên đăng nhập: </p>
                                        <span><?php echo $row_user['user_account']; ?></span>
                                    </div>
                                    <div class="account_wrap-child">
                                        <p>Mật khẩu: </p>
                                        <input type="password" value="<?php echo $row_user['user_pass']; ?>"
                                            class="input_disable">
                                        <!-- <button class="btn_change-pass">Thay đổi</button> -->
                                    </div>
                                    <div class="account_wrap-child">
                                        <button class="btn btn--primary btn_save">Xác nhận thay đổi</button>
                                    </div>
                                </div>
                                <div class="grid__column-4">
                                    <div class="account_img">
                                        <div style="min-height:200px; padding-top: 40px; display: flex; justify-content: center;">
                                        <img src="../assets/img/user/<?php echo isset($row_user['user_picture']) ? $row_user['user_picture'] : 'user.png'; ?>"
                                        alt="Ảnh 1">
                                        </div>
                                        <button class="btn btn_change-img">Chọn ảnh</button>
                                        <input type="file" id="fileInput" name="user_picture" style="display: none;" accept=".jpg,.jpeg,.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="order_wrap">
                            <div class="order_title">
                                <ul class="order_title-list">
                                    <li class="order_title-child order_title-child--active"><span>Tất cả</span></li>
                                    <li class="order_title-child"><span>Chờ thanh toán</span></li>
                                    <li class="order_title-child"><span>Vận chuyển</span></li>
                                    <li class="order_title-child"><span>Chờ giao hàng</span></li>
                                    <li class="order_title-child"><span>Hoàn thành</span></li>
                                </ul>
                            </div>
                            <?php
                            $test_code = '';
                            while ($row_order = mysqli_fetch_array($sql_order)) {
                                $order_code = $row_order['transaction_code'];
                                if ($order_code != $test_code) {
                                    ?>
                                    <div class="order_content" data-order-code="<?php echo $order_code ?>">
                                        <div class="order_content-title">
                                            <h1>Mã đơn hàng: <?php echo $order_code; ?></h1>
                                            <p>Trạng thái : <span> Chờ xác nhận</span></p>
                                        </div>
                                        <div class="order_content-main">
                                            <?php
                                            $totalPrice = 0;
                                            $sql_product = mysqli_query($con, "SELECT dh.quantity AS order_quantity, dh.*,sp.* FROM tbl_orders AS dh JOIN tbl_product AS sp ON dh.product_id = sp.product_id WHERE dh.transaction_code = '$order_code' AND dh.user_id = $user_id");
                                            while ($row_product = mysqli_fetch_array($sql_product)) {
                                                $totalPrice += $row_product["total_price"];
                                                ?>
                                                <div class="cart_product-wrap">
                                                    <div class="cart_product-select">
                                                        <div class="img_wrap">
                                                            <img src="../assets/img/<?php echo $row_product['main_image']; ?>"
                                                                alt="san pham">
                                                        </div>
                                                    </div>
                                                    <div class="cart_product-infor-wrap">
                                                        <div class="cart-infor">
                                                            <span
                                                                class="cart-infor-name"><?php echo $row_product['product_name']; ?></span>
                                                            <div class="cartt-infor-price">
                                                                <span class="cart-infor-price-old"
                                                                    style="font-size: 1.8rem; color: #aaa; text-decoration: line-through;">
                                                                    <?php echo $row_product['old_price']; ?>
                                                                    <sup>đ</sup>
                                                                </span>
                                                                <span class="cart-infor-price-new"
                                                                    style="font-size: 2.2rem; color: var(--primary-color);">
                                                                    <?php echo $row_product['new_price']; ?>
                                                                    <sup>đ</sup>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="cart_product-infor-number">
                                                            <p style="display: block; width: 100%;; text-align: right;">Số lượng :
                                                                <?php echo $row_product['order_quantity']; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="cart_totalPrice">
                                                <div class="btn btn--primary deleteOder" style="margin-right: 20px; font-size: 1.8rem;">Hủy đơn hàng</div>
                                                Thành tiền : <p> <?php echo $totalPrice ?><sup>đ</sup></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                                $test_code = $order_code;
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include("components/footer.php");
    ?>

    <script src="./js/taikhoan.js"></script>

</body>

</html>