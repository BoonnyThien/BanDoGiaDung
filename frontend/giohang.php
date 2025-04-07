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
    <link rel="stylesheet" href="./css/giohang.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="../assets/font/fontawesome-free-6.6.0-web/css/all.min.css">
</head>

<body>

    <?php
    include("components/header.php");
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    $sql_cart = mysqli_query($con, "SELECT gh.quantity AS cart_quantity, gh.*,sp.* FROM tbl_cart AS gh JOIN tbl_product AS sp ON gh.product_id = sp.product_id WHERE gh.user_id = $user_id");
    ?>

    <div class="cart_container">
        <div class="grid">
            <div class="navigation">
                <a href="index.php" class="navigation_child">Home</a>
                <div style="display: inline-block;">
                    <i class="fa-solid fa-chevron-right"></i>
                    Giỏ hàng
                </div>
            </div>
            <div class="grid__row">
                <div class="grid__column-8">

                    <div class="cart_all">
                        <div class="cart_select-all">
                            <div class="input_wrap">
                                <input type="checkbox">
                            </div>
                            <p>Chọn tất cả (<span>0</span>)</p>
                        </div>
                        <div class="cart_remove-wrap">
                            <i class="fa-regular fa-thumbs-up"></i>
                        </div>
                    </div>

                    <div class="all_product-result">
                        <?php
                        if (mysqli_num_rows($sql_cart) != 0) {
                            while ($row_cart = mysqli_fetch_array($sql_cart)) {
                                $display = '';
                                if ($row_cart['old_price'] <= 0)
                                    $display = 'display_none';
                                ?>
                                <div class="cart_product-wrap" data-product-id="<?php echo $row_cart['product_id']; ?>"
                                    data-product-old-price="<?php echo ($row_cart['old_price'] == 0) ? $row_cart['new_price'] : $row_cart['old_price']; ?>"
                                    data-product-new-price="<?php echo $row_cart['new_price']; ?>">
                                    <div class="cart_product-select">
                                        <div class="input_wrap">
                                            <input type="checkbox">
                                        </div>
                                        <div class="img_wrap">
                                            <img src="../assets/img/<?php echo $row_cart['main_image']; ?>" alt="san pham">
                                        </div>
                                    </div>
                                    <div class="cart_product-infor-wrap">
                                        <div class="cart-infor">
                                            <span class="cart-infor-name"><?php echo $row_cart['product_name']; ?></span>
                                            <div class="cartt-infor-price">
                                                <span class="cart-infor-price-old"
                                                    style="font-size: 1.8rem; color: #aaa; text-decoration: line-through;"
                                                    class="<?php echo $display ?>"><?php echo $row_cart['old_price']; ?>
                                                    <sup>đ</sup>
                                                </span>
                                                <span class="cart-infor-price-new"
                                                    style="font-size: 2.2rem; color: var(--primary-color);"><?php echo $row_cart['new_price']; ?>
                                                    <sup>đ</sup>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="cart_product-infor-number">
                                            <div class="product_detail-number" style="margin-right: 20px;">
                                                <div class="product_detail-buy_number decrease"><i
                                                        class="fa-solid fa-minus"></i>
                                                </div>
                                                <input type="number" class="product_detail-buy-updown" min="1"
                                                    value="<?php echo $row_cart['cart_quantity']; ?>">
                                                <div class="product_detail-buy_number increase"><i class="fa-solid fa-plus"></i>
                                                </div>
                                            </div>
                                            <div class="cart_remove-wrap">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        } else {
                            echo "<div style='width: 100%; height: 100%; background-color: white; padding: 10px; border-radius: 20px;text-align: center;'>";
                            echo "<h1 style='width: 100%;'>Không có sản phẩm</h1>";
                            echo "<img src='../assets/img/no_cart.png' alt='Không có sản phẩm' style='display: block; margin: 0 auto;'>";
                            echo "</div>";
                        } ?>
                    </div>

                </div>
                <div class="grid__column-4">
                    <div class="cart_confirm">
                        <div class="cart_confirm-wrap">
                            <div><i class="fa-solid fa-money-check-dollar"></i>
                                <p>Chọn hoặc nhập ưu đãi</p>
                            </div>
                            <i class="fa-solid fa-chevron-right"></i>
                            <!-- <i class="fa-brands fa-bitcoin" style="color:yellow;"></i> -->
                        </div>

                        <!-- THem class  active_toggle-coin de bat toggle___________ -->
                        <div class="cart_confirm-wrap">
                            <div><i class="fa-brands fa-bitcoin" style="color:yellow;"></i>
                                <p>Đổi 0 điểm (~0đ)</p>
                            </div>
                            <i class="fa-solid fa-toggle-off toggle_off"></i>
                            <i class="fa-solid fa-toggle-on toggle_on"></i>
                        </div>

                        <div class="cart_confirm-infor">
                            <div class="cart_confirm-infor-wrap">
                                <h1>Thông tin đơn hàng</h1>
                            </div>
                            <div class="cart_confirm-infor-wrap">
                                <p>Tổng tiền</p>
                                <span class="totalPrice">0 <sup>đ</sup></span>
                            </div>
                            <div class="cart_confirm-infor-wrap">
                                <p>Tổng khuyến mãi</p>
                                <span class="discoundPrice">0 <sup>đ</sup></span>
                            </div>
                            <div class="cart_confirm-infor-wrap">
                                <p>Phí vận chuyển</p>
                                <span>Miễn phí</span>
                            </div>
                            <div class="cart_confirm-infor-wrap">
                                <p>Cần thanh toán</p>
                                <span class="pay_element" style="color: var(--primary-color);">0 <sup>đ</sup></span>
                            </div>
                            <div class="cart_confirm-infor-wrap">
                                <p>Điểm thưởng</p>
                                <span> <i class="fa-brands fa-bitcoin" style="color:yellow;"></i>+0 </span>
                            </div>
                            <div class="cart_confirm-btn" data-user-id="<?php echo $user_id ?>">Đặt hàng</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include("components/footer.php");
    ?>

    <script src="./js/giohang.js"></script>

</body>

</html>