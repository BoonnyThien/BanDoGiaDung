<?php
require_once('../../database/connect.php');
require_once('../../admin/php/sanpham.php');

//SELECT `donhang_id`, `magiaodich`, `user_id`, `sanpham_id`, `soluong_mua`, `thanhtien`, `trangthai` FROM `tbl_donhang` WHERE 1
//SELECT `sanpham_id`, `danhmuc_id`, `thuonghieu_id`, `sanpham_name`, `price_old`, `price_new`, `sanpham_soluong`, `sanpham_hot`, `hinhanh_main`, `hinhanh_extra1`, `hinhanh_extra2`, `sanpham_xuatxu`, `baohanh_date`, `baohanh_option`, `tinhnang`, `chatlieu`, `mota` FROM `tbl_sanpham` WHERE 1
//SELECT `user_id`, `user_name`, `user_phone`, `user_address`, `user_account`, `user_pass`, `user_picture`, `rule` FROM `tbl_user` WHERE 1
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lí sản phẩm</title>
    <link rel="stylesheet" href="../../admin/css/quanli.css">
</head>

<body>
    <div id="notification" class="error" style="display: <?php echo isset($_SESSION['errors']) ? 'block' : 'none'; ?>">
        <?php echo isset($_SESSION['errors']) ? $_SESSION['errors'] : ''; ?>
    </div>
    <?php unset($_SESSION['errors']); ?> <!-- Xóa lỗi sau khi hiển thị -->
    <div class="left">
        <a href="quanli.php?add=true">Thêm</a>
        <a href="#" class="delete-link" onclick="submitDeleteForm()">Xóa đã chọn</a>
        <nav class="left">
            <form method="GET" action="quanli.php">
                <div class="leftsearch">
                    <p>Tìm kiếm theo </p>
                    <select name="search_column">
                        <option value="sanpham_id" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'sanpham_id' ? 'selected' : ''; ?>>ID</option>
                        <option value="danhmuc_id" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'danhmuc_id' ? 'selected' : ''; ?>>Danh mục</option>
                        <option value="thuonghieu_id" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'thuonghieu_id' ? 'selected' : ''; ?>>Thương hiệu</option>
                        <option value="sanpham_name" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'sanpham_name' ? 'selected' : ''; ?>>Tên sản phẩm</option>
                        <option value="price_old" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'price_old' ? 'selected' : ''; ?>>Giá cũ</option>
                        <option value="price_new" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'price_new' ? 'selected' : ''; ?>>Giá mới</option>
                        <option value="sanpham_soluong" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'sanpham_soluong' ? 'selected' : ''; ?>>Số lượng</option>
                        <option value="sanpham_hot" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'sanpham_hot' ? 'selected' : ''; ?>>Hot</option>
                        <option value="sanpham_xuatxu" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'sanpham_xuatxu' ? 'selected' : ''; ?>>Xuất xứ</option>
                        <option value="baohanh_date" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'baohanh_date' ? 'selected' : ''; ?>>Bảo hành</option>
                        <option value="baohanh_option" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'baohanh_option' ? 'selected' : ''; ?>>Tùy chọn bảo hành</option>
                        <option value="tinhnang" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'tinhnang' ? 'selected' : ''; ?>>Tính năng</option>
                        <option value="chatlieu" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'chatlieu' ? 'selected' : ''; ?>>Chất liệu</option>
                        <option value="mota" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'mota' ? 'selected' : ''; ?>>Mô tả</option>
                    </select>
                    <input type="text" name="search" placeholder="Tìm kiếm..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                </div>
                <div class="leftsort">
                    <p>Sắp xếp theo</p>
                    <select name="sort">
                        <option value="sanpham_id" <?php echo $sort == 'sanpham_id' ? 'selected' : ''; ?>>ID</option>
                        <option value="danhmuc_id" <?php echo $sort == 'danhmuc_id' ? 'selected' : ''; ?>>Danh mục</option>
                        <option value="thuonghieu_id" <?php echo $sort == 'thuonghieu_id' ? 'selected' : ''; ?>>Thương hiệu</option>
                        <option value="sanpham_name" <?php echo $sort == 'sanpham_name' ? 'selected' : ''; ?>>Tên sản phẩm</option>
                        <option value="price_old" <?php echo $sort == 'price_old' ? 'selected' : ''; ?>>Giá cũ</option>
                        <option value="price_new" <?php echo $sort == 'price_new' ? 'selected' : ''; ?>>Giá mới</option>
                        <option value="sanpham_soluong" <?php echo $sort == 'sanpham_soluong' ? 'selected' : ''; ?>>Số lượng</option>
                        <option value="sanpham_hot" <?php echo $sort == 'sanpham_hot' ? 'selected' : ''; ?>>Hot</option>
                        <option value="sanpham_xuatxu" <?php echo $sort == 'sanpham_xuatxu' ? 'selected' : ''; ?>>Xuất xứ</option>
                        <option value="baohanh_date" <?php echo $sort == 'baohanh_date' ? 'selected' : ''; ?>>Bảo hành</option>
                        <option value="baohanh_option" <?php echo $sort == 'baohanh_option' ? 'selected' : ''; ?>>Tùy chọn bảo hành</option>
                        <option value="tinhnang" <?php echo $sort == 'tinhnang' ? 'selected' : ''; ?>>Tính năng</option>
                        <option value="chatlieu" <?php echo $sort == 'chatlieu' ? 'selected' : ''; ?>>Chất liệu</option>
                        <option value="mota" <?php echo $sort == 'mota' ? 'selected' : ''; ?>>Mô tả</option>
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

    <?php
    // Chèn Form Sửa Sản Phẩm
    if (isset($_GET['id'])):
        $id = $_GET['id'];
        $query = "SELECT * FROM tbl_product WHERE product_id=$id";
        $result = mysqli_query($con, $query);
        $product = mysqli_fetch_assoc($result);
    ?>
        <h2>Sửa sản phẩm</h2>
        <form method="POST" enctype="multipart/form-data" action="quanli.php?id=<?php echo $sanpham_id; ?>">
            <input type="hidden" name="id" value="<?php echo $sanpham_id; ?>">
            <label for="category_id">Danh mục:
                <select id="category_id" name="category_id">
                    <?php while ($row = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?php echo $row['category_id']; ?>" <?php echo $row['category_id'] == $product['category_id'] ? 'selected' : ''; ?>>
                            <?php echo $row['category_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </label><br>

            <label for="brand_id">Thương hiệu:
                <select id="brand_id" name="brand_id">
                    <?php while ($row = mysqli_fetch_assoc($trademark)): ?>
                        <option value="<?php echo $row['brand_id']; ?>" <?php echo $row['brand_id'] == $product['brand_id'] ? 'selected' : ''; ?>>
                            <?php echo $row['brand_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </label><br>
            <label for="xuatxu">Xuất xứ:
                <input type="text" id="xuatxu" name="xuatxu" value="<?php echo $product['origin']; ?>"></label><br>
            <label for="name" class="chu">Tên:
                <input type="text" id="name" name="name" value="<?php echo $product['product_name']; ?>"></label><br>
            <label for="tinhnang" class="chu">Tính năng:
                <input type="text" id="tinhnang" name="tinhnang" value="<?php echo $product['features']; ?>"></label><br>
            <label for="chatlieu" class="chu">Chất liệu:
                <input type="text" id="chatlieu" name="chatlieu" value="<?php echo $product['material']; ?>"></label><br>
            <label for="hinhanh_main" class="pic">Hình ảnh chính: <br>
                <img src="../../assets/img/<?php echo $hinhanh_main; ?>" alt="Hình ảnh chính">
                <input type="file" id="hinhanh_main" name="hinhanh_main" class="pic" accept="image/*">
                <input type="hidden" name="hinhanh_main_old" value="<?php echo $hinhanh_main; ?>">
            </label><br>
            <label for="hinhanh_extra1" class="pic">Hình ảnh phụ 1: <br>
                <img src="../../assets/img/<?php echo $hinhanh_extra1; ?>" alt="Hình ảnh phụ 1">
                <input type="file" id="hinhanh_extra1" name="hinhanh_extra1" accept="image/*">
                <input type="hidden" name="hinhanh_extra1_old" value="<?php echo $hinhanh_extra1; ?>">
            </label><br>
            <label for="hinhanh_extra2" class="pic">Hình ảnh phụ 2: <br>
                <img src="../../assets/img/<?php echo $hinhanh_extra2; ?>" alt="Hình ảnh phụ 2">
                <input type="file" id="hinhanh_extra2" name="hinhanh_extra2" accept="image/*">
                <input type="hidden" name="hinhanh_extra2_old" value="<?php echo $hinhanh_extra2; ?>">
            </label><br>

            <label for="price_old">Giá cũ:
                <input type="number" id="price_old" name="price_old" min="1" value="<?php echo number_format($product['old_price'],0,',','.'); ?>"></label><br>
            <label for="price_new">Giá mới:
                <input type="number" id="price_new" name="price_new" min="1" value="<?php echo number_format($product['new_price'],0,',','.'); ?>"></label><br>
            <label for="soluong">Số lượng:
                <input type="number" id="soluong" name="soluong" min="1" value="<?php echo $product['quantity']; ?>"></label><br>
            <label for="hot">Hot:
                <input type="number" id="hot" name="hot" min="0" max="1" value="<?php echo $product['is_hot']; ?>"></label><br>
            <label for="baohanh_date">Bảo hành (ngày):
                <input type="text" id="baohanh_date" name="baohanh_date" min="1" value="<?php echo $product['warranty_period']; ?>"></label><br>
            <label for="baohanh_option">Tùy chọn bảo hành:
                <input type="text" id="baohanh_option" name="baohanh_option" value="<?php echo $product['warranty_option']; ?>"></label><br>
            <label for="mo-ta" class="mo-ta">Mô tả:
                <textarea id="mota" name="mota"><?php echo $product['descriptions']; ?></textarea></label><br>
            <label class="bt">
                <input type="submit" value="">
                <input type="submit" id="tick" name="update" value="Cập nhật">
                <input type="button" id="blank" value="Làm rỗng">
                <input type="submit" name="huy" id="huy" value="Hủy"></label><br>
        </form>
    <?php endif ?>

    <?php if (isset($_GET['add'])): ?>
        <h2>Thêm sản phẩm</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <label for="danhmuc_id">Danh mục:
                <select id="danhmuc_id" name="danhmuc_id">
                    <?php while ($row = mysqli_fetch_assoc($categories)): ?>
                        <option value="<?php echo $row['category_id']; ?>" <?php echo $row['category_id'] == $danhmuc_idInput ? 'selected' : ''; ?>><?php echo $row['category_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </label><br>

            <label for="thuonghieu_id">Thương hiệu:
                <select id="thuonghieu_id" name="thuonghieu_id">
                    <?php while ($row = mysqli_fetch_assoc($trademark)): ?>
                        <option value="<?php echo $row['brand_id']; ?>" <?php echo $row['brand_id'] == $thuonghieu_idInput ? 'selected' : ''; ?>><?php echo $row['brand_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </label><br>

            <label for="xuatxu">Xuất xứ:<input type="text" id="xuatxu" name="xuatxu" value="<?php echo $xuatxuInput; ?>"></label><br>
            <label for="name" class="chu">Tên:<input type="text" id="name" name="name" value="<?php echo $nameInput; ?>"></label><br>
            <label for="tinhnang" class="chu">Tính năng:<input type="text" id="tinhnang" name="tinhnang" value="<?php echo $tinhnangInput; ?>"></label><br>
            <label for="chatlieu" class="chu">Chất liệu:<input type="text" id="chatlieu" name="chatlieu" value="<?php echo $chatlieuInput; ?>"></label><br>
            <label for="hinhanh_main " class="pic">Hình ảnh chính: <br>
                <input type="file" id="hinhanh_main" name="hinhanh_main" accept="image/*" multiple onchange="previewImage(event,'image_main')">
                <img id="image_main" src="#" alt="Preview Image">
                <input type="hidden" name="hinhanh_main_old" value="<?php echo $hinhanh_main; ?>"></label><br>
            <label for="hinhanh_extra1" class="pic">Hình ảnh phụ 1: <br>
                <input type="file" id="hinhanh_extra1" name="hinhanh_extra1" accept="image/*" multiple onchange="previewImage(event,'image_extra1')">
                <img id="image_extra1" src="#" alt="Preview Image">
                <input type="hidden" name="hinhanh_extra1_old" value="<?php echo $hinhanh_extra1; ?>"></label><br>
            <label for="hinhanh_extra2" class="pic ">Hình ảnh phụ 2: <br>
                <input type="file" id="hinhanh_extra2" name="hinhanh_extra2" accept="image/*" multiple onchange="previewImage(event,'image_extra2')">
                <img id="image_extra2" src="#" alt="Preview Image">
                <input type="hidden" name="hinhanh_extra2_old" value="<?php echo $hinhanh_extra2; ?>"></label><br>
            <label for="price_old">Giá cũ:<input type="number" id="price_old" name="price_old" min="1" value="<?php echo $price_oldInput; ?>"></label><br>
            <label for="price_new">Giá mới:<input type="number" id="price_new" name="price_new" min="1" value="<?php echo $price_newInput; ?>"></label><br>
            <label for="soluong">Số lượng:<input type="number" id="soluong" name="soluong" min="1" value="<?php echo $soluongInput; ?>"></label><br>
            <label for="hot">Hot:<input type="number" id="hot" name="hot" min="0" max="1" <?php echo $hotInput; ?>"></label><br>

            <label for="baohanh_date">Bảo hành (ngày):<input type="number" id="baohanh_date" min="1" name="baohanh_date" value="<?php echo $baohanh_dateInput; ?>"></label><br>
            <label for="baohanh_option">Tùy chọn bảo hành:<input type="text" id="baohanh_option" name="baohanh_option" value="<?php echo $baohanh_optionInput; ?>"></label><br>

            <label for="mo-ta" class="mo-ta">Mô tả:<textarea id="mota" name="mota"><?php echo $motaInput; ?></textarea></label><br>
            <label class="bt">
                <input type="button" name="">
                <input type="submit" id="tick" name="add" value="Thêm sản phẩm">
                <input type="button" id="blank" value="Làm rỗng">
                <input type="submit" name="huy" id="huy" value="Hủy"></label>
        </form>
    <?php endif; ?>

    <form method="POST" action="quanli.php">
        <table class="product">
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <!-- <th>Xuất xứ</th> -->
                    <th>Tên sản phẩm</th>
                    <!-- <th>Tính năng</th>
                    <th>Chất liệu</th> -->
                    <th>Hình ảnh chính</th>
                    <th>Hình ảnh phụ 1</th>
                    <th>Hình ảnh phụ 2</th>
                    <th>Giá cũ</th>
                    <th>Giá mới</th>
                    <th>Số lượng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($products)): ?>
                    <td><input type="checkbox" name="selected_ids[]" value="<?php echo $row['product_id']; ?>"></td>
                    <td><?php echo $row['product_id']; ?></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td><?php echo $row['brand_name']; ?></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><img src="../../assets/img/<?php echo $row['main_image']; ?>" alt="Product Image"></td>
                    <td><img src="../../assets/img/<?php echo $row['extra_image1']; ?>" alt="Product Image"></td>
                    <td><img src="../../assets/img/<?php echo $row['extra_image2']; ?>" alt="Product Image"></td>
                    <td><?php echo number_format($row['old_price'],0,',','.'); ?></td>
                    <td><?php echo number_format($row['new_price'],0,',','.'); ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td class="bto">
                        <div class="bt sua"><a href="quanli.php?id=<?php echo $row['product_id']; ?>">Sửa</a></div>
                        <div class="bt chitiet"><a href="#" onclick="showOverlay(<?php echo $row['product_id']; ?>)">Chi tiết</a></div>
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
<script src="../../admin/js/quanli.js"></script>

</html>