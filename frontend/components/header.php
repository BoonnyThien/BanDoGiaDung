<?php
session_start();
include_once('../database/connect.php');

$is_logged_in = isset($_SESSION['user_id']);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$sql_history = mysqli_query($con, "SELECT * FROM tbl_searchhistory  ORDER BY searchHistory_id DESC LIMIT 3");
$sql_cart = mysqli_query($con, "SELECT gh.quantity AS cart_quantity,sp.*,dm.* FROM tbl_cart AS gh JOIN tbl_product AS sp ON gh.product_id = sp.product_id JOIN tbl_category AS dm ON dm.category_id = sp.category_id WHERE gh.user_id = $user_id");
$number_cart = mysqli_num_rows($sql_cart);
$sql_user = mysqli_query($con, "SELECT * FROM tbl_user WHERE user_id = $user_id");
$row_user = mysqli_fetch_array($sql_user);
?>

<header class="header">
    <div class="header__navbar grid">
        <ul class="header__navbar-list">
            <li class="header__navbar-item header__navbar-item--has-qr header__navbar-item--separate">
                Cửa hàng đồ gia dụng nhóm 4
                <div class="header__qr">
                    <img src="../assets/img/qr_code.png" alt="QR code" class="header__qr-img">
                    <div class="header__qr-apps">
                        <a href="#" class="header__qr-link header__qr-boder-right">
                            <img src="../assets/img/ch_play.png" alt="CH Play" class="header__qr-download">
                        </a>
                        <a href="#" class="header__qr-link">
                            <img src="../assets/img/app_store.png" alt="App Store" class="header__qr-download">
                        </a>
                    </div>
                </div>
            </li>
            <li class="header__navbar-item">
                <span class="header__navbar-title--disable">Kết nối</span>
                <a href="#" class="header__navbar-icon-link">
                    <i class="header__navbar-icon fa-brands fa-facebook"></i>
                </a>
                <a href="#" class="header__navbar-icon-link">
                    <i class="header__navbar-icon fa-brands fa-instagram"></i>
                </a>
            </li>
        </ul>

        <ul class="header__navbar-list">
            <li class="header__navbar-item header__navbar-item--has-noti">
                <a href="sosanh.php" class="header__navbar-itemlink">
                    <i class="header__navbar-icon fa-solid fa-square-plus"></i>
                    So Sánh
                </a>
            </li>
            <li class="header__navbar-item help_active">
                <a href="lienhe.php" class="header__navbar-itemlink">
                    <i class="header__navbar-icon fa-regular fa-circle-question"></i>
                    Trợ giúp
                </a>
            </li>
            <?php if (!$is_logged_in): ?>
                <li class="header__navbar-item header__navbar-item--strong header__navbar-item--separate">Đăng ký</li>
                <li class="header__navbar-item header__navbar-item--strong">Đăng nhập</li>
            <?php else: ?>
                <li class="header__navbar-item header__navbar-user" style="min-width: 150px; justify-content: center;">
                    <i class="header__navbar-user-img fa-solid fa-user-astronaut"></i>
                    <span class="header__navbar-user-name"><?php echo $_SESSION['user_name'] ?></span>
                    <ul class="header__navbar-user-menu">
                        <li class="header__navbar-user-item">
                            <a href="taikhoan.php">Tài khoản của tôi</a>
                        </li>

                        <?php if (isset($_SESSION['user_rule']) && $_SESSION['user_rule'] == 1) { ?>
                            <li class="header__navbar-user-item">
                                <a href="./../admin/php/login.php">Quản lý</a>
                            </li>
                        <?php } ?>
                        <li class="header__navbar-user-item">
                            <form method="POST" action="../database/main.php"
                                style="text-align: center; margin: 10px 0 5px 0;">
                                <input type="submit" name="logout" value="Đăng xuất" class="btn btn--primary">
                            </form>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="header-with-search grid">
        <div class="header__logo">
            <a href="index.php" class="header__logo-link">
                <img class="header__logo-img" src="../assets/img/logo_shopee123.png" alt="Shopee">
            </a>
        </div>

        <div class="header__search">
            <div class="header__search-input-wrap">
                <input type="text" class="header__search-input" placeholder="Nhập để tìm kiếm sản phẩm"
                    value="<?php echo (isset($inputSearch)) ? $inputSearch : ''; ?>">

                <div class="header__search-history">
                    <h3 class="header__search-history-heading">Lịch sử tìm kiếm</h3>
                    <ul class="header__search-history-list">
                        <?php while ($row_history = mysqli_fetch_array($sql_history)) { ?>
                            <li class="header__search-history-item">
                                <a
                                    href="index.php?search=<?php echo $row_history['searchHistory_name'] ?>"><?php echo $row_history['searchHistory_name'] ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <button class="header__search-btn">
                <i class="header__search-btn-icon fa-solid fa-magnifying-glass"></i>
            </button>
        </div>

        <div class="header__cart">
            <div class="header__cart-wrap">
                <i class="header__cart-icon fa-solid fa-cart-shopping"></i>
                <span class="header__cart-notice"><?php echo $number_cart; ?></span>


                <!-- khong cart thif the div duoi them class  'header__cart-list--no-cart' -->
                <div class="header__cart-list <?php if ($number_cart <= 0)
                    echo 'header__cart-list--no-cart'; ?>">
                    <img src="../assets/img/no_cart.png" alt="" class="header__cart-no-cart-img">
                    <span class="header__cart-no-cart-title">Không có sản phẩm</span>

                    <h4 class="header__cart-heading">Sản phẩm đã thêm</h4>
                    <ul class="header__cart-list-item">
                        <?php
                        while ($row_cart = mysqli_fetch_array($sql_cart)) {
                            ?>
                            <li class="header__cart-item" data-product-id="<?php echo $row_cart['product_id'] ?>">
                                <img src="../assets/img/<?php echo $row_cart['main_image']; ?>" alt=""
                                    class="header__cart-item-img">
                                <div class="header__cart-item-info">
                                    <div class="header__cart-item-head">
                                        <h5 class="header__cart-item-name"><?php echo $row_cart['product_name']; ?></h5>
                                        <div>
                                            <span class="header__cart-item-price"><?php echo $row_cart['new_price']; ?>
                                                <sup>đ</sup></span>
                                            <span class="header__cart-item-multiply">x</span>
                                            <span
                                                class="header__cart-item-qlt"><?php echo $row_cart['cart_quantity']; ?></span>
                                        </div>
                                    </div>
                                    <div class="header__cart-item-body">
                                        <span class="header__cart-item-description">
                                            Phân loại : <?php echo $row_cart['category_name']; ?>
                                        </span>
                                        <span class="header__cart-item-remove"
                                            data-product-id="<?php echo $row_cart['product_id'] ?>">
                                            Xóa
                                        </span>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>

                    <a href="giohang.php" class="header__cart-view-cart btn btn--primary">Xem giỏ hàng</a>

                </div>
            </div>
        </div>
    </div>

    <!-- Thông báo -->
    <div id="toast"></div>

</header>

<!-- Modal layout (Đăng nhập, đăng kí, xác nhận)  -->

<div class="modal">
    <div class="modal__overlay"></div>

    <div class="modal__body">

        <!-- Register Form -->
        <form method="POST" action="../database/main.php" class="auth-form form-register">
            <div class="auth-form__container">

                <div class="auth-form__header">
                    <h3 class="auth-form__heading">Đăng ký</h3>
                    <span class="auth-form__switch-btn">Đăng nhập</span>
                </div>

                <div class="auth-form__form">
                    <div class="auth-form__group">
                        <input type="text" name="register_name" class="auth-form__input" placeholder="Nhập họ tên"
                            required>
                    </div>
                    <div class="auth-form__group">
                        <input type="tel" name="register_phone" class="auth-form__input" pattern="[0-9]{10}"
                            placeholder="Nhập số điện thoại" required>
                    </div>
                    <div class="auth-form__group">
                        <input type="email" name="register_email" class="auth-form__input" placeholder="Nhập Email"
                            required>
                    </div>
                    <div class="auth-form__group">
                        <input type="text" name="register_address" class="auth-form__input" placeholder="Nhập địa chỉ"
                            required>
                    </div>
                    <div class="auth-form__group">
                        <input type="text" name="register_account" class="auth-form__input"
                            placeholder="Nhập tên đăng nhập" required>
                    </div>
                    <div class="auth-form__group">
                        <input type="password" name="register_password" class="auth-form__input"
                            placeholder="Nhập mật khẩu" required>
                    </div>
                    <div class="auth-form__group">
                        <input type="password" name="register_confirm_password" class="auth-form__input"
                            placeholder="Nhập lại mật khẩu" required>
                    </div>
                </div>

                <div class="auth-form__aside">
                    <p class="auth-form__policy-text">
                        Bằng việc đăng ký bạn đã đồng ý với chúng tôi về
                        <a href="#" class="auth-form__text-link">Điều khoản dịch vụ</a> &
                        <a href="#" class="auth-form__text-link">Chính sách bảo mật</a>
                    </p>
                </div>

                <div class="auth-form__controls">
                    <button type="button" class="btn auth-form__controls-back btn--normal">TRỞ LẠI</button>
                    <button type="submit" name="dangky" class="btn btn--primary">ĐĂNG KÝ</button>
                </div>

            </div>

            <div class="auth-form__socials">
                <a href="#" class="auth-form__socials--facebook btn btn__with-icon btn-size-s">
                    <i class="auth-form__socials-icon fa-brands fa-square-facebook"></i>
                    <span class="auth-form__socials-title">Kết nối với FaceBook</span>
                </a>
                <a href="#" class="auth-form__socials--google btn btn__with-icon btn-size-s">
                    <i class="auth-form__socials-icon fa-brands fa-google"></i>
                    <span class="auth-form__socials-title">Kết nối với GooGle</span>
                </a>
            </div>

        </form>

        <!-- Login Form -->
        <form method="POST" action="../database/main.php" class="auth-form form-login">
            <!-- Thêm method và action -->
            <div class="auth-form__container">

                <div class="auth-form__header">
                    <h3 class="auth-form__heading">Đăng nhập</h3>
                    <span class="auth-form__switch-btn">Đăng ký</span>
                </div>

                <div class="auth-form__form">
                    <div class="auth-form__group">
                        <input type="text" name="login_account" class="auth-form__input" placeholder="Nhập tài khoản"
                            required>
                    </div>
                    <div class="auth-form__group">
                        <input type="password" name="login_password" class="auth-form__input"
                            placeholder="Nhập mật khẩu" required>
                    </div>
                </div>

                <?php if (isset($_SESSION['error_login'])): ?>
                    <div class="error-message"
                        style="color: red; margin-top: 20px; margin-bottom: 20px; font-size: 1.4rem;">
                        <?php echo $_SESSION['error_login']; ?>
                    </div>
                <?php endif; ?>

                <div class="auth-form__aside">
                    <div class="auth-form__help">
                        <a href="#" class="auth-form__help-link auth-form__help-forgot">Quên mật khẩu</a>
                        <a href="#" class="auth-form__help-link auth-form__help-needhelp">Cần trợ giúp ?</a>
                    </div>
                </div>
                <div class="auth-form__controls">
                    <button type="button" class="btn auth-form__controls-back btn--normal">TRỞ LẠI</button>
                    <button type="submit" name="dangnhap" class="btn btn--primary">ĐĂNG NHẬP</button>
                    <!-- Thay đổi thành type="submit" -->
                </div>

            </div>

            <div class="auth-form__socials">
                <a href="#" class="auth-form__socials--facebook btn btn__with-icon btn-size-s">
                    <i class="auth-form__socials-icon fa-brands fa-square-facebook"></i>
                    <span class="auth-form__socials-title">Đăng nhập với FaceBook</span>
                </a>
                <a href="#" class="auth-form__socials--google btn btn__with-icon btn-size-s">
                    <i class="auth-form__socials-icon fa-brands fa-google"></i>
                    <span class="auth-form__socials-title">Đăng nhập với GooGle</span>
                </a>
            </div>
        </form>

        <!-- Form xác nhận thông tin nhận hàng -->
        <form method="POST" action="../database/main.php" class="auth-form form-confirm" name="help">
            <div class="auth-form__container">

                <div class="auth-form__header">
                    <h3 class="auth-form__heading" style="width: 100%; text-align: center;">Xác nhận thông tin nhận hàng
                    </h3>
                </div>

                <div class="auth-form__form">
                    <div class="auth-form__group">
                        <input type="text" id="name_confirm" class="auth-form__input" placeholder="Nhập tên của bạn"
                            required value="<?php echo isset($row_user['user_name']) ? $row_user['user_name'] : '' ?>">
                    </div>
                    <div class="auth-form__group">
                        <input type="text" id="phone_confirm" class="auth-form__input" placeholder="Nhập số điện thoại"
                            required
                            value="<?php echo isset($row_user['user_phone']) ? $row_user['user_phone'] : '' ?>">
                    </div>
                    <div class="auth-form__group">
                        <input type="text" id="address_confirm" class="auth-form__input" placeholder="Nhập địa chỉ"
                            required
                            value="<?php echo isset($row_user['user_address']) ? $row_user['user_address'] : '' ?>">
                    </div>
                </div>

                <div class="auth-form__aside">
                    <p class="auth-form__policy-text">
                        Nếu bạn gặp vấn đề xin vui lòng liên hệ với chúng tôi qua sdt:
                        <a href="#" class="auth-form__text-link">0353991328</a> &
                        <a href="#" class="auth-form__text-link">nguyenduong@gmail.com.vn</a>
                    </p>
                </div>

                <div class="auth-form__controls">
                    <button type="button" class="btn auth-form__controls-back btn--normal">TRỞ LẠI</button>
                    <button type="button" name="confirm" class="btn btn--primary btn_confirm-address">XÁC NHẬN</button>
                </div>

            </div>

            <div class="auth-form__socials">
            </div>

        </form>
    </div>
</div>

</script>
<script src="./js/header.js"></script>
<script src="./js/message.js"></script>