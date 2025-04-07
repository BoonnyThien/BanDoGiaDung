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
    <link rel="stylesheet" href="./css/danhmuc.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="stylesheet" href="../assets/font/fontawesome-free-6.6.0-web/css/all.min.css">
</head>

<body>

    <?php
    include("components/header.php");
    $id_danhmuc = (int) $_GET['id_danhmuc'];
    $id_thuonghieu = isset($_GET['thuonghieu']) ? (int) $_GET['thuonghieu'] : 0;
    $startPrice = isset($_GET['start']) ? (int) $_GET['start'] : 0;
    $endPrice = isset($_GET['end']) ? (int) $_GET['end'] : 0;
    
    $itemPage = 25;
    $currentPage = isset($_GET['page']) ? intval($_GET['page']) :1;
    $offset = ($currentPage - 1 ) * $itemPage;
    $order = '';
    if($startPrice || $endPrice){
        if($startPrice < $endPrice){
            $order = "AND sp.new_price >= $startPrice AND sp.new_price <= $endPrice ";
        }else{
            $order = "AND sp.new_price >= $startPrice ";
        }
    }
    $sql_category = mysqli_query($con, 'SELECT * FROM tbl_category ORDER BY category_id DESC');
    $sql_brand = mysqli_query($con, 'SELECT * FROM tbl_brand ORDER BY brand_id DESC');
    $sql_product = $id_thuonghieu ? mysqli_query($con, " SELECT sp.*, th.* FROM tbl_product AS sp JOIN tbl_brand AS th ON sp.brand_id = th.brand_id Where sp.category_id = $id_danhmuc AND th.brand_id = $id_thuonghieu $order  ORDER BY sp.product_id DESC LIMIT $itemPage OFFSET $offset")
        : mysqli_query($con, " SELECT sp.*, th.* FROM tbl_product AS sp JOIN tbl_brand AS th ON sp.brand_id = th.brand_id Where sp.category_id = $id_danhmuc $order ORDER BY sp.product_id DESC LIMIT $itemPage OFFSET $offset");
    
    $totalPage = ceil(mysqli_num_rows($sql_product) / $itemPage);


    
    ?>

    <div class="slider_header">
        <div class="slider_header-wrap grid">
            <div class="slide_header-169 slide_header-103">
                <a href="#"><img class="slider_header-img" src="../assets/img/slider_img/slide6.png" alt="Slide 6"></a>
                <a href="#"><img class="slider_header-img" src="../assets/img/slider_img/slide7.png" alt="Slide 7"></a>
                <a href="#"><img class="slider_header-img" src="../assets/img/slider_img/slide8.png" alt="Slide 8"></a>
                <a href="#"><img class="slider_header-img" src="../assets/img/slider_img/slide9.png" alt="Slide 9"></a>
                <a href="#"><img class="slider_header-img" src="../assets/img/slider_img/slide10.png" alt="Slide 10"></a>
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
                <div class="grid__column-2">
                    <div class="category">

                        <h3 class="category__heading">
                            <i class="category__heading-icon fa-solid fa-bars"></i>
                            Danh mục
                        </h3>
                        <ul class="category-list">
                            <?php
                            $name = '';
                            while ($row_category = mysqli_fetch_array($sql_category)) {
                                $isActive = ($id_danhmuc == $row_category['category_id']) ? 'category-item--active' : '';
                                ?>
                                <li class="category-item <?php echo $isActive; ?>"
                                    data-id_danhmuc="<?php echo $row_category['category_id']; ?>">
                                    <a href="?id_danhmuc=<?php echo $row_category['category_id']; ?>"
                                        class="category-item__link">
                                        <?php echo $row_category['category_name']; ?>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>

                        <h3 style="font-size: 1.4rem; font-weight: normal;" class="category__heading">
                            Thương hiệu
                        </h3>

                        <ul class="category-list">
                            <?php
                            while ($row_brand = mysqli_fetch_array($sql_brand)) {
                                $isActive = ($id_thuonghieu == $row_brand['brand_id']) ? 'check_active' : '';
                                ?>
                                <li class="category-item-band <?php echo $isActive ?>"
                                    data-id_thuonghieu="<?php echo $row_brand['brand_id'] ?>">
                                    <div class="band_check"><i class="fa-solid fa-check"></i></div>
                                    <span><?php echo $row_brand['brand_name'] ?></span>
                                </li>
                            <?php } ?>

                        </ul>
                        <h3 style="font-size: 1.4rem;  font-weight: normal;" class="category__heading">
                            Khoảng giá
                        </h3>
                        <div class="category_check-price">
                            <input class="category-price price_start" type="number" min="0" value="<?php echo isset($startPrice) ? $startPrice : 0; ?>">
                            <span>-</span>
                            <input class="category-price price_end" type="number" min="0" value="<?php echo isset($endPrice) ? $endPrice : 0; ?>">
                        </div>
                        <button class="btn check-price-btn">Áp dụng</button>

                    </div>
                </div>

                <div class="grid__column-10">
                    <h1 class="home_title"><?php echo $name ?></h1>
                    <div class="home-filter">
                        <span class="home-filter__label">Sắp xếp theo</span>
                        <button class="btn home-filter__btn">Phổ biến</button>
                        <button class="btn home-filter__btn">Mới nhất</button>

                        <div class="select-input">
                            <span class="select-input__label">Giá : </span>
                            <i class="select-input-icon fa-solid fa-angle-down"></i>
                            <ul class="select-input__list">
                                <li class="select-input__item">
                                    <a href="" class="select-input__link">Giá : Thấp đến cao</a>
                                </li>
                                <li class="select-input__item">
                                    <a href="" class="select-input__link">Giá : Cao đến thấp</a>
                                </li>
                            </ul>
                        </div>

                        <div class="home-filter__page">
                            <span class="home-filter__page-num">
                                <span class="home-filter__page-curent"><?php echo $currentPage?></span>
                                /<?php echo $totalPage ?>
                            </span>

                            <div class="home-filter__page-control">
                                <a href="#" class="home-filter__page-btn home-filter__page-btn--disabled">
                                    <i class="home-filter__page-icon fa-solid fa-angle-left"></i>
                                </a>
                                <a href="#" class="home-filter__page-btn home-filter__page-btn--disabled">
                                    <i class="home-filter__page-icon fa-solid fa-angle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="home-product">
                        <div class="grid__row parent_product">
                            <?php
                            if (mysqli_num_rows($sql_product) == 0) {
                                echo "<div style='width: 100%; height: 400px; background-color: white; padding: 20px; margin: 15px; text-align: center;'>";
                                echo "<h1 style='width: 100%;'>Không có sản phẩm</h1>";
                                echo "<img src='../assets/img/no_cart.png' alt='Không có sản phẩm' style='display: block; margin: 0 auto;'>";
                                echo "</div>";
                            } else {
                                while ($row_product = mysqli_fetch_array($sql_product)) {
                                    $hot = ($row_product['is_hot'] == 1) ? 'favourite_active' : '';
                                    ?>
                                    <div class="grid__column-2-4">
                                        <a class="product-item"
                                            href="chitiet.php?product_id=<?php echo $row_product['product_id'] ?>"
                                            data-id="<?php echo $row_product['product_id'] ?>"
                                            data-price="<?php echo $row_product['new_price'] ?>"
                                            data-soluong="<?php echo $row_product['quantity'] ?>">
                                            <div class="product-item__img"
                                                style="background-image: url(../assets/img/<?php echo $row_product['main_image'] ?>);">
                                            </div>
                                            <h4 class="product-item__name"><?php echo $row_product['product_name'] ?></h4>
                                            <div class="product-item__price">
                                                <span class="product-item__price-old <?php if ($row_product['old_price'] < $row_product['new_price'])
                                                    echo 'display_none'; ?>">
                                                    <?php echo $row_product['old_price'] ?><sup>đ</sup>
                                                </span>
                                                <span
                                                    class="product-item__price-current"><?php echo $row_product['new_price'] ?><sup>đ</sup></span>
                                            </div>
                                            <div class="product-item__ation">
                                                <!-- Thêm class  product-item__like--liked khi click yêu thích -->
                                                <span class="product-item__like">
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
                                                echo ' display_none'; ?>">
                                                <span class="product-item__sale-off-percent">
                                                    <?php
                                                    $gg = 0;
                                                    if ($row_product['old_price'] > 0)
                                                        $gg = (($row_product['old_price'] - $row_product['new_price']) / $row_product['old_price']) * 100;
                                                    echo round($gg, 0);
                                                    ?>%
                                                </span>
                                                <span class="product-item__sale-off-lable">Giảm</span>
                                            </div>
                                        </a>
                                    </div>
                                    <?php
                                }
                            }
                            ?>


                        </div>
                    </div>

                    <ul class="pagination home-product__pagination">
                        <li class="pagination-item">
                            <a href="?page=<?php echo $currentPage - 1 ?>" class="pagination-item__link <?php echo ($currentPage > 1)? '' : 'a--disable';?>">
                                <i class="pagination-item__icon fa-solid fa-angle-left"></i>
                            </a>
                        </li>
                        <li class="pagination-item pagination-item--active">
                            <a href="?page=1" class="pagination-item__link a--disable">1</a>
                        </li>
                        <li>
                            <a href="?page=<?php echo $currentPage + 1 ?>" class="pagination-item__link <?php echo ($currentPage < $totalPage)? '' : 'a--disable';?>">
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
    
    <script src="./js/danhmuc.js"></script>

</body>

</html>