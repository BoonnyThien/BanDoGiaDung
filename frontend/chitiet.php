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
    <link rel="stylesheet" href="./css/chitiet.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="../assets/font/fontawesome-free-6.6.0-web/css/all.min.css">
</head>

<body>

    <?php
    include("components/header.php");
    $id_chitietSp = $_GET['product_id'];
    $detail_product = mysqli_query($con, " SELECT sp.*, th.* FROM tbl_product AS sp JOIN tbl_brand AS th ON sp.brand_id = th.brand_id Where sp.product_id = $id_chitietSp");
    $row_product = mysqli_fetch_array($detail_product);
    $category = $row_product["category_id"];
    $sql_related_products = mysqli_query($con, " SELECT sp.*, th.* FROM tbl_product AS sp JOIN tbl_brand AS th ON sp.brand_id = th.brand_id Where sp.category_id = '$category' AND sp.product_id != '$id_chitietSp'");
    $sql_category = mysqli_query($con, 'SELECT * FROM tbl_category');
    while ($row_category = mysqli_fetch_array($sql_category))
        if ($row_category['category_id'] == $row_product['category_id'])
            $current_category = $row_category['category_name'];
    ($row_product['old_price'] == 0) ? $none = 'display_none' : $none = '';
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    ?>

    <div class="product_detail" data-page="detail" data-user-id="<?php echo $user_id ?>">
        <div class="product_detail-wrap grid">
            <div class="navigation">
                <a href="index.php" class="navigation_child">Home</a>
                <div style="display: inline-block;">
                    <i class="fa-solid fa-chevron-right"></i>
                    <a href="danhmuc.php?id_danhmuc=<?php echo $row_product['category_id'] ?>"
                        class="navigation_child"><?php echo $current_category ?></a>
                </div>
                <div style="display: inline-block;">
                    <i class="fa-solid fa-chevron-right"></i>
                    <?php echo $row_product['product_name'] ?>
                </div>
            </div>
            <div class="grid__row product_detail-infor">
                <div class="grid__column-4">
                    <div class="detail_infor-img">
                        <img class="img_main" src="../assets/img/<?php echo $row_product['main_image'] ?>"
                            alt="Sản phẩm">
                        <div class="img_sub-wrap">
                            <div class="img_sub ">
                                <img class="border_active" src="../assets/img/<?php echo $row_product['main_image'] ?>"
                                    alt="Sub1">
                            </div>
                            <div class="img_sub">
                                <img src="../assets/img/<?php echo $row_product['extra_image1'] ?>" alt="">
                            </div>
                            <div class="img_sub">
                                <img src="../assets/img/<?php echo $row_product['extra_image2'] ?>" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid__column-8 product_detail-background">

                    <h1 class="product_detail-name"><?php echo $row_product['product_name'] ?></h1>
                    <div class="product_detail-quality">
                        <div class="product-item__rating" style="font-size: 1.6rem;">
                            <i class="product-item__star--gold fa-solid fa-star"></i>
                            <i class="product-item__star--gold fa-solid fa-star"></i>
                            <i class="product-item__star--gold fa-solid fa-star"></i>
                            <i class="product-item__star--gold fa-solid fa-star"></i>
                            <i class="product-item__star--gold fa-solid fa-star"></i>
                        </div>
                        <div class="product-item__evaluate">
                            <p>560</p> Đánh giá
                        </div>
                        <div class="product-item__sales">
                            <p><?php echo $row_product['quantity'] ?></p> Đã bán
                        </div>
                    </div>
                    <div class="product_detail-price">
                        <p class="product_detail-price-old <?php echo $none ?>">
                            <?php echo $row_product['old_price'] ?> <sup>đ</sup>
                        </p>
                        <p class="product_detail-price-new" data-price="<?php echo $row_product['new_price'] ?>">
                            <?php echo $row_product['new_price'] ?> <sup>đ</sup>
                        </p>
                    </div>

                    <div class="product_detail-discount">
                        <span>Flash Sale giảm giá </span>
                        <p> Giảm <?php $gg = 0;
                        if ($row_product['old_price'] > 0)
                            $gg = (($row_product['old_price'] - $row_product['new_price']) / $row_product['old_price']) * 100;
                        echo round($gg, 0); ?> %</p>
                    </div>

                    <div class="product_detail_transport">
                        <span>Vận chuyển </span>
                        <p><i class="fa-solid fa-truck"></i>Miễn phí vận chuyển toàn quốc</p>
                    </div>

                    <div class="product_detail-number-wrap">
                        <span>Số lượng </span>
                        <div class="product_detail-number">
                            <div class="product_detail-buy_number decrease"><i class="fa-solid fa-minus"></i></div>
                            <input type="number" class="product_detail-buy-updown" min="1" value="1"
                                data-product_id="<?php echo $row_product['product_id'] ?>">
                            <div class="product_detail-buy_number increase"><i class="fa-solid fa-plus"></i></div>
                        </div>
                        <p><?php echo $row_product['quantity'] ?> sản phẩm có sẵn</p>
                    </div>

                    <div class="product_detail-button-group">
                        <div class="btn product_detail-button product_detail-btncart"><i
                                class="fa-solid fa-cart-plus"></i>Thêm vào giỏ hàng</div>
                        <div class="btn product_detail-button product_detail-btnbuy"><i
                                class="fa-solid fa-money-bill-wave"></i>Mua ngay</div>
                    </div>

                    <div class="product_detail-return">
                        <span><i class="fa-solid fa-shield-halved"></i>Chính sách trả hàng</span>
                        <p>Miễn phí đổi trả trong vòng 15 ngày từ khi nhận hàng</p>
                    </div>
                </div>
            </div>

            <div class="grid__row product_specific">
                <div class="grid__column-6" style="border-right: 2px solid #ccc;">
                    <div class="product_specific-infor">
                        <h1>Thông số chi tiết</h1>
                        <div class="product_specific-infor-wrap">
                            <span>Số lượng khuyến mãi</span>
                            <p>99</p>
                        </div>
                        <div class="product_specific-infor-wrap">
                            <span>Số lượng hàng còn lại</span>
                            <p><?php echo $row_product['quantity'] ?></p>
                        </div>
                        <div class="product_specific-infor-wrap">
                            <span>Xuất xứ</span>
                            <p><?php echo $row_product['origin'] ?></p>
                        </div>
                        <div class="product_specific-infor-wrap">
                            <span>Hạn bảo hành</span>
                            <p><?php $baohanh_d = isset($row_product['warranty_period']) ? $row_product['warranty_period'] : 'Không';
                            echo $baohanh_d; ?>
                            </p>
                        </div>
                        <div class="product_specific-infor-wrap">
                            <span>Loại bảo hành</span>
                            <p><?php $baohanh_o = isset($row_product['warranty_option']) ? $row_product['warranty_option'] : 'Không';
                            echo $baohanh_o; ?>
                            </p>
                        </div>
                        <div class="product_specific-infor-wrap">
                            <span>Tính năng</span>
                            <p><?php $tinhnang = isset($row_product['features']) ? $row_product['features'] : 'Không';
                            echo $tinhnang; ?>
                            </p>
                        </div>
                        <div class="product_specific-infor-wrap">
                            <span>Chất liệu</span>
                            <p><?php $chatlieu = isset($row_product['material']) ? $row_product['material'] : 'Không';
                            echo $chatlieu; ?>
                            </p>
                        </div>
                        <div class="product_specific-infor-wrap">
                            <span>Điện áp đầu vào</span>
                            <p>Không</p>
                        </div>
                        <div class="product_specific-infor-wrap">
                            <span>Tiêu thụ điện năng</span>
                            <p>Không</p>
                        </div>
                    </div>
                </div>
                <div class="grid__column-6">
                    <div class="product_specific-describe">
                        <h1>Mô tả sản phẩm</h1>
                        <p>
                            <?php
                            $noidung = $row_product['descriptions'];
                            $noidung = nl2br(htmlspecialchars($noidung));
                            echo $noidung;
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="home-product" style="padding: 15px; background-color: rgb(245, 237, 230); border-radius: 20px;">
                <div class="next_product"><i class="fa-solid fa-angle-right"></i></div>
                <div class="before_product"><i class="fa-solid fa-angle-left"></i></div>
                <div
                    style="display: inline-block; padding: 10px 0px; margin: 10px 12px; font-size: 2.5rem; font-weight: 800; color: var(--primary-color); border-bottom: 2px solid var(--primary-color);">
                    Sản phẩm liên quan</div>
                <div style="overflow: hidden;">
                    <div class="grid_no_wrap">
                        <?php
                        while ($row_product_lq = mysqli_fetch_array($sql_related_products)) {
                            $hot = ($row_product_lq['is_hot'] == "1") ? 'favourite_active' : '';
                            ?>
                            <div class="grid__column-2 product" style="flex-shrink: 0;">
                                <a class="product-item"
                                    href="chitiet.php?product_id=<?php echo $row_product_lq['product_id'] ?>">
                                    <div class="product-item__img"
                                        style="background-image: url(../assets/img/<?php echo $row_product_lq['main_image'] ?>);">
                                    </div>
                                    <h4 class="product-item__name"><?php echo $row_product_lq['product_name'] ?></h4>
                                    <div class="product-item__price">
                                        <span class="product-item__price-old <?php if ($row_product_lq['old_price'] < $row_product_lq['new_price'])
                                            echo 'display_none' ?>"><?php echo $row_product_lq['old_price'] ?>
                                            <sup>đ</sup></span>
                                        <span class="product-item__price-current"><?php echo $row_product_lq['new_price'] ?>
                                            <sup>đ</sup></span>
                                    </div>
                                    <div class="product-item__ation">
                                        <span class="product-item__like ">
                                            <i class="product-item__like-icon-empty fa-regular fa-heart"
                                                style="color: #000;"></i>
                                            <i class="product-item__like-icon-fill fa-solid fa-heart"></i>
                                        </span>
                                        <div class="product-item__rating">
                                            <i class="product-item__star--gold fa-solid fa-star"></i>
                                            <i class="product-item__star--gold fa-solid fa-star"></i>
                                            <i class="product-item__star--gold fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                        <span class="product-item__sold"><?php echo $row_product_lq['quantity'] ?>
                                            đã bán</span>
                                    </div>
                                    <div class="product-item__origin">
                                        <span
                                            class="product-item__brand"><?php echo $row_product_lq['brand_name'] ?></span>
                                        <span
                                            class="product-item__origin-name"><?php echo $row_product_lq['origin'] ?></span>
                                    </div>
                                    <div class="product-item__favourite <?php echo $hot ?>">
                                        <i class="fa-solid fa-check"></i>
                                        <span>yêu thích</span>
                                    </div>
                                    <div class="product-item__sale-off <?php if ($row_product_lq['old_price'] < $row_product_lq['new_price'])
                                        echo ' display_none' ?>">
                                            <span class="product-item__sale-off-percent"><?php $gg = 0;
                                    if ($row_product_lq['old_price'] > 0)
                                        $gg = (($row_product_lq['old_price'] - $row_product_lq['new_price']) / $row_product_lq['old_price']) * 100;
                                    echo round($gg, 0); ?>
                                            %</span>
                                        <span class="product-item__sale-off-lable">Giam</span>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>




    </div>

    <?php
    include("components/footer.php");
    ?>
    
    <script src="./js/chitiet.js"></script>

</body>

</html>