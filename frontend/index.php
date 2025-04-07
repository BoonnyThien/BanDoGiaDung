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
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="../assets/font/fontawesome-free-6.6.0-web/css/all.min.css">
</head>

<body>

    <?php
    // Truy vấn danh mục
    $sql_category = mysqli_query($con, 'SELECT * FROM tbl_category ORDER BY category_id DESC');

    // Tạo phần phân trang
    $itemPage = 25;
    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($currentPage - 1) * $itemPage;
    $numRow_product = mysqli_query($con, 'SELECT * FROM tbl_product');
    $totalPage = ceil(mysqli_num_rows($numRow_product) / $itemPage);

    // Truy vấn sản phẩm
    $issetSearch = '';
    $inputSearch = '';
    if (isset($_GET['search'])) {
        $inputSearch = $_GET['search'];
        $issetSearch = 'display_none';
        $sql_product = mysqli_query($con, "SELECT sp.*, th.* FROM tbl_product AS sp JOIN tbl_brand AS th ON sp.brand_id = th.brand_id WHERE sp.product_name LIKE '%$inputSearch%' ORDER BY sp.product_id DESC LIMIT $itemPage OFFSET $offset");
    } else {
        $sql_product = mysqli_query($con, "SELECT sp.*, th.* FROM tbl_product AS sp JOIN tbl_brand AS th ON sp.brand_id = th.brand_id ORDER BY sp.product_id DESC LIMIT $itemPage OFFSET $offset");
    }

    // Truy vấn sản phẩm yêu thích
    $sql_like_product = mysqli_query($con, "SELECT sp.*, th.* FROM tbl_product AS sp JOIN tbl_brand AS th ON sp.brand_id = th.brand_id Where sp.is_hot = '1' ORDER BY sp.product_id DESC");
    include("components/header.php");
    ?>

    <div class="slider_header">
        <div class="slider_header-wrap grid">
            <div class="slide_header-169">
                <a href="#"><img class="slider_header-img" src="../assets/img/slider_img/slide1.png" alt="Slide 1"></a>
                <a href="#"><img class="slider_header-img" src="../assets/img/slider_img/slide2.png" alt="Slide 1"></a>
                <a href="#"><img class="slider_header-img" src="../assets/img/slider_img/slide3.png" alt="Slide 1"></a>
                <a href="#"><img class="slider_header-img" src="../assets/img/slider_img/slide4.png" alt="Slide 1"></a>
                <a href="#"><img class="slider_header-img" src="../assets/img/slider_img/slide5.png" alt="Slide 1"></a>
            </div>
            <div class="slider_header-dot-wrap">
                <div class="slider_header-dot dot_active"></div>
                <div class="slider_header-dot"></div>
                <div class="slider_header-dot"></div>
                <div class="slider_header-dot"></div>
                <div class="slider_header-dot"></div>
            </div>
        </div>
    </div>


    <div class="app__container">
        <div class="grid">
            <div class="grid__row app__content">
                <div class="grid__column-2 div_cha" style="position: relative; background-color: #fff;">
                    <div class="category">
                        <h3 class="category__heading">
                            <i class="category__heading-icon fa-solid fa-bars"></i>
                            Danh mục
                        </h3>

                        <ul class="category-list">

                            <!-- Them class  category-item--active de tao hieu ung da nhan vao danh muc -->
                            <?php
                            while ($row_category = mysqli_fetch_array($sql_category)) {
                                ?>
                                <li class="category-item">
                                    <a href="danhmuc.php?id_danhmuc=<?php echo $row_category['category_id'] ?>"
                                        class="category-item__link"><?php echo $row_category['category_name'] ?></a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <!-- __________________________________________________SẢN PHẨM HOT__________________________________________________________ -->
                <div class="grid__column-10">
                    <div class="home-product <?php echo $issetSearch; ?>"
                        style="padding: 15px; background-color: rgb(246, 232, 224); border-radius: 20px; margin-bottom: 40px;">
                        <div class="next_product"><i class="fa-solid fa-angle-right"></i></div>
                        <div class="before_product"><i class="fa-solid fa-angle-left"></i></div>
                        <div
                            style="display: inline-block; padding: 10px 0px; margin: 10px 12px; font-size: 2.5rem; font-weight: 800; color: var(--primary-color); border-bottom: 2px solid var(--primary-color);">
                            Sản phẩm HOT <i class="fa-solid fa-fire"></i></div>
                        <div style="overflow: hidden;">
                            <div class="grid_no_wrap">
                                <?php
                                while ($row_like_product = mysqli_fetch_array($sql_like_product)) {
                                    $hot = ($row_like_product['is_hot'] == "1") ? 'favourite_active' : '';
                                    ?>
                                    <div class="grid__column-2-4 product" style="flex-shrink: 0;">
                                        <a class="product-item"
                                            href="chitiet.php?product_id=<?php echo $row_like_product['product_id'] ?>">
                                            <div class="product-item__img"
                                                style="background-image: url(../assets/img/<?php echo $row_like_product['main_image'] ?>);">
                                            </div>
                                            <h4 class="product-item__name"><?php echo $row_like_product['product_name'] ?>
                                            </h4>
                                            <div class="product-item__price">
                                                <span
                                                    class="product-item__price-old <?php if ($row_like_product['old_price'] < $row_like_product['new_price'])
                                                        echo 'display_none' ?>"><?php echo $row_like_product['old_price'] ?>
                                                    <sup>đ</sup></span>
                                                <span
                                                    class="product-item__price-current"><?php echo $row_like_product['new_price'] ?>
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
                                                    <i class="product-item__star--gold fa-solid fa-star"></i>
                                                    <i class="product-item__star--gold fa-solid fa-star"></i>
                                                </div>
                                                <span class="product-item__sold"><?php echo $row_like_product['quantity'] ?>
                                                    đã bán</span>
                                            </div>
                                            <div class="product-item__origin">
                                                <span
                                                    class="product-item__brand"><?php echo $row_like_product['brand_name'] ?></span>
                                                <span
                                                    class="product-item__origin-name"><?php echo $row_like_product['origin'] ?></span>
                                            </div>
                                            <div class="product-item__favourite <?php echo $hot ?>">
                                                <i class="fa-solid fa-check"></i>
                                                <span>yêu thích</span>
                                            </div>
                                            <div class="product-item__sale-off <?php if ($row_like_product['old_price'] < $row_like_product['new_price'])
                                                echo ' display_none' ?>">
                                                    <span class="product-item__sale-off-percent"><?php $gg = 0;
                                            if ($row_like_product['old_price'] > 0)
                                                $gg = (($row_like_product['old_price'] - $row_like_product['new_price']) / $row_like_product['old_price']) * 100;
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
                    
                    <!-- __________________________________________________SẢN PHẨM GỢI Ý__________________________________________________________ -->
                    <div class="home-product">
                        <h1
                            style="width: 100%; text-align: center;color: var(--primary-color); font-size: 2.5rem; margin-top: 0; padding: 30px 0 20px; background-color: var(--white-color);border-bottom: 5px solid var(--primary-color);">
                            <?php echo ($inputSearch) ? 'Tìm kiếm: ' . $inputSearch : 'Gợi ý hôm nay'; ?>
                        </h1>
                        <div class="grid__row">
                            <?php
                            if (mysqli_num_rows($sql_product) == 0) {
                                echo "<div style='width: 100%; background-color: white; padding: 20px; margin: 15px; text-align: center;'>";
                                echo "<h1 style='width: 100%;'>Không có sản phẩm</h1>";
                                echo "<img src='../assets/img/no_cart.png' alt='Không có sản phẩm' style='display: block; margin: 0 auto;'>";
                                echo "</div>";
                            } else {
                                while ($row_product = mysqli_fetch_array($sql_product)) {
                                    $hot = ($row_product['is_hot'] == "1") ? 'favourite_active' : '';
                                    ?>
                                    <div class="grid__column-2-4">
                                        <a class="product-item"
                                            href="chitiet.php?product_id=<?php echo $row_product['product_id'] ?>">
                                            <div class="product-item__img"
                                                style="background-image: url(../assets/img/<?php echo $row_product['main_image'] ?>);">
                                            </div>
                                            <h4 class="product-item__name"><?php echo $row_product['product_name'] ?></h4>
                                            <div class="product-item__price">
                                                <span class="product-item__price-old <?php if ($row_product['old_price'] < $row_product['new_price'])
                                                    echo 'display_none' ?>"><?php echo $row_product['old_price'] ?>
                                                    <sup>đ</sup></span>
                                                <span
                                                    class="product-item__price-current"><?php echo $row_product['new_price'] ?>
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
                                                    <i class="product-item__star--gold fa-solid fa-star"></i>
                                                    <i class="product-item__star--gold fa-solid fa-star"></i>
                                                </div>
                                                <span class="product-item__sold"><?php echo $row_product['quantity'] ?>
                                                    đã bán</span>
                                            </div>
                                            <div class="product-item__origin">
                                                <span
                                                    class="product-item__brand"><?php echo $row_product['brand_name'] ?></span>
                                                <span
                                                    class="product-item__origin-name"><?php echo $row_product['origin'] ?></span>
                                            </div>
                                            <div class="product-item__favourite <?php echo $hot ?>">
                                                <i class="fa-solid fa-check"></i>
                                                <span>yêu thích</span>
                                            </div>
                                            <div class="product-item__sale-off <?php if ($row_product['old_price'] < $row_product['new_price'])
                                                echo ' display_none' ?>">
                                                    <span class="product-item__sale-off-percent"><?php $gg = 0;
                                            if ($row_product['old_price'] > 0)
                                                $gg = (($row_product['old_price'] - $row_product['new_price']) / $row_product['old_price']) * 100;
                                            echo round($gg, 0); ?>
                                                    %</span>
                                                <span class="product-item__sale-off-lable">Giam</span>
                                            </div>
                                        </a>
                                    </div>
                                <?php }
                            } ?>
                        </div>
                    </div>

                    <!-- ____________________________________________________Phần chia trang_______________________________________________________________- -->
                    <ul class="pagination home-product__pagination">
                        <li class="pagination-item">
                            <a href="?page=<?php echo $currentPage - 1 ?>"
                                class="pagination-item__link <?php echo ($currentPage > 1) ? '' : 'a--disable'; ?>">
                                <i class="pagination-item__icon fa-solid fa-angle-left"></i>
                            </a>
                        </li>

                        <?php
                        for ($num = 1; $num <= $totalPage; $num++) { ?>
                            <li class="pagination-item <?php echo ($currentPage == $num) ? 'pagination-item--active' : ''; ?>">
                                <a href="?page=<?php echo $num ?>" class="pagination-item__link"><?php echo $num ?></a>
                            </li>
                        <?php } ?>

                        <li>
                            <a href="?page=<?php echo $currentPage + 1; ?>"
                                class="pagination-item__link <?php echo ($currentPage < $totalPage) ? '' : 'a--disable'; ?>">
                                <i class="pagination-item__icon fa-solid fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <?php
    include("components/footer.php");
    ?>

    <script src="./js/index.js"></script>

</body>

</html>