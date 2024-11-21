<?php
require_once('../../database/connect.php');

// Lấy tất cả sản phẩm
function getProducts($con, $limit = 8)
{
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Trang hiện tại
    $offset = ($page - 1) * $limit; // Vị trí bắt đầu cho trang hiện tại
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $search_column = isset($_GET['search_column']) ? $_GET['search_column'] : ''; // Cột tìm kiếm
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'product_id';
    $order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; // Thứ tự sắp xếp mặc định là tăng dần

    // Xây dựng điều kiện tìm kiếm
    $searchQuery = "";
    if (!empty($search) && !empty($search_column)) {
        $searchQuery = " AND {$search_column} LIKE '%$search%'";
    }

    // Truy vấn để lấy số bản ghi giới hạn theo trang
    $query = "SELECT 
    p.product_id, p.category_id, c.category_name, p.brand_id, b.brand_name, 
    p.product_name, p.old_price, p.new_price, p.quantity, p.is_hot, 
    p.main_image, p.extra_image1, p.extra_image2, p.origin, 
    p.warranty_period, p.warranty_option, p.features, p.material, p.descriptions
    FROM tbl_product p
    JOIN tbl_category c ON p.category_id = c.category_id
    JOIN tbl_brand b ON p.brand_id = b.brand_id
    WHERE 1=1 $searchQuery
    ORDER BY $sort $order
    LIMIT $limit OFFSET $offset";


    $result = mysqli_query($con, $query);

    // Tính tổng số trang
    $total_query = "
        SELECT COUNT(*)
        FROM tbl_product
        WHERE 1=1 $searchQuery
    ";
    $total_result = mysqli_query($con, $total_query);
    $total_rows = mysqli_fetch_array($total_result)[0];
    $total_pages = ceil($total_rows / $limit); // Tính tổng số trang

    return [$result, $total_pages, $page, $sort, $order, $search, $search_column];
}

list($products, $total_pages, $page, $sort, $order, $search, $search_column) = getProducts($con);

function getAllCategories($con)
{
    $query = "SELECT * FROM tbl_category";
    $result = mysqli_query($con, $query);
    return $result;
}
function getAllTrademarks($con)
{
    $query = "SELECT * FROM tbl_brand";
    $result = mysqli_query($con, $query);
    return $result;
}
$categories = getAllCategories($con);
$trademark = getAllTrademarks($con);

session_start(); // Bắt đầu phiên

// Xử lý form thêm/cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? $_POST['id'] : 0; // Thiết lập giá trị mặc định nếu không có id
    $inputs = [
        'name' => 'Tên',
        'soluong' => 'Số lượng',
        'xuatxu' => 'Xuất xứ',
        'tinhnang' => 'Tính năng',
        'chatlieu' => 'Chất liệu',
        'mota' => 'Mô tả'
    ];

    $errors = "";
    $errorss = [];
    $errorss1 = [];

    foreach ($inputs as $key => $input) {
        if (empty($_POST[$key])) {
            $errorss[] = $input;
        }
    }
    $so = [
        'price_old' => 'Giá cũ',
        'price_new' => 'Giá mới',
        'baohanh_date' => 'Ngày bảo hành',
        'soluong' => 'Số lượng'
    ];
    foreach ($so as $key => $input) {
        if ($_POST[$key] < 0) {
            $errorss1[] = $input;
        }
    }

    // Kiểm tra hình ảnh mới nếu không có ảnh cũ
    $image_inputs = ['hinhanh_main' => 'Hình ảnh chính', 'hinhanh_extra1' => 'Hình ảnh phụ 1', 'hinhanh_extra2' => 'Hình ảnh phụ 2'];
    foreach ($image_inputs as $key => $input) {
        if (empty($_POST[$key . '_old']) && (empty($_FILES[$key]['name']) || $_FILES[$key]['error'] != UPLOAD_ERR_OK)) {
            $errorss2[] = $input;
        }
    }


    if (!empty($errorss)) {
        $errors1 = implode(', ', $errorss);
        $errors .= $errors1 . " không được để trống.&emsp;";
    }
    if (!empty($errorss1)) {
        $errors2 = implode(',', $errorss1);
        $errors .= $errors2 . ' không hợp lệ.&emsp;';
    }
    if (!empty($errorss2)) {
        $errors3 = implode(',', $errorss2);
        $errors .= $errors3 . 'không có ảnh ! &emsp; ';
    }
    if (isset($_POST['huy'])) {
        load();
        header('Location: quanli.php');
        exit();
    }
    if (isset($_POST['delete_selected']) && !empty($_POST['selected_ids'])) {
        delete($con, $_POST['selected_ids']);
    }
    if (empty($errors)) {
        if (isset($_POST['add'])) {
            add($con);
            header('Location: quanli.php');
            exit();
        } elseif (isset($_POST['update'])) {
            update($con);
            header('Location: quanli.php');
            exit();
        }
    } else {
        $_SESSION['errors'] = $errors; // Lưu lỗi vào phiên
        $_SESSION['post_data'] = $_POST; // Lưu dữ liệu đã nhập vào phiên
        if (isset($_POST['add'])) {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?add=true');
        } elseif (isset($_POST['update'])) {
            header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $id);
        }
        exit();
    }
}

// Lấy lại dữ liệu đã nhập từ phiên
if (isset($_SESSION['post_data'])) {
    foreach ($_SESSION['post_data'] as $key => $value) {
        ${$key . 'Input'} = htmlspecialchars($value);
    }
    unset($_SESSION['post_data']); // Xóa dữ liệu sau khi lấy lại
} else {
    $danhmuc_idInput = '';
    $thuonghieu_idInput = '';
    $nameInput = '';
    $price_oldInput = '';
    $price_newInput = '';
    $soluongInput = '';
    $hotInput = '';
    $xuatxuInput = '';
    $baohanh_dateInput = '';
    $baohanh_optionInput = '';
    $tinhnangInput = '';
    $chatlieuInput = '';
    $motaInput = '';
}
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sanpham = details($con, $id);

        $sanpham_id = $_GET['id']; // Lấy ID sản phẩm từ URL
        $product_images = getImage($con, $sanpham_id);

        if ($product_images) {
            $hinhanh_main = $product_images['main_image'];
            $hinhanh_extra1 = $product_images['extra_image1'];
            $hinhanh_extra2 = $product_images['extra_image2'];
        } else {
            $hinhanh_main = "";
            $hinhanh_extra1 = "";
            $hinhanh_extra2 = "";
        }
    } else {
        $_SESSION['error'] = "ID báo cáo không được cung cấp!";
        header("Location: quanli.php");
        exit();
    }
}

function details($con, $id)
{
    $query = "SELECT s.*, t.brand_name, a.category_name
          FROM tbl_product s
          JOIN tbl_brand t ON s.brand_id = t.brand_id
          JOIN tbl_category a ON s.category_id = a.category_id
          WHERE s.product_id = $id";

    $result = mysqli_query($con, $query);
    if ($result) {
        return mysqli_fetch_assoc($result);
    } else {
        $_SESSION['errors'] = "Lỗi khi lấy dữ liệu sản phẩm: " . mysqli_error($con);
        exit();
    }
}

function load()
{
    header('Location: quanli.php'); // Điều hướng về trang quản lý sản phẩm
    exit();
}


// Xử lý cập nhật sản phẩm
function update($con)
{
    $id = $_POST['id'];
    $category_id = $_POST['category_id'];
    $brand_id = $_POST['brand_id'];
    $product_name = $_POST['name'];
    $old_price = $_POST['price_old'];
    $new_price = $_POST['price_new'];
    $quantity = $_POST['soluong'];
    $is_hot = $_POST['hot'];
    $origin = $_POST['xuatxu'];
    $warranty_period = $_POST['baohanh_date'];
    $warranty_option = $_POST['baohanh_option'];
    $features = $_POST['tinhnang'];
    $material = $_POST['chatlieu'];
    $description = $_POST['mota'];

    // Xử lý hình ảnh
    $main_image = $_POST['hinhanh_main_old'];
    if (isset($_FILES['hinhanh_main']) && $_FILES['hinhanh_main']['error'] == UPLOAD_ERR_OK) {
        $main_image = $_FILES['hinhanh_main']['name'];
        move_uploaded_file($_FILES['hinhanh_main']['tmp_name'], '../../assets/img/' . $main_image);
    }

    $extra_image1 = $_POST['hinhanh_extra1_old'];
    if (isset($_FILES['hinhanh_extra1']) && $_FILES['hinhanh_extra1']['error'] == UPLOAD_ERR_OK) {
        $extra_image1 = $_FILES['hinhanh_extra1']['name'];
        move_uploaded_file($_FILES['hinhanh_extra1']['tmp_name'], '../../assets/img/' . $extra_image1);
    }

    $extra_image2 = $_POST['hinhanh_extra2_old'];
    if (isset($_FILES['hinhanh_extra2']) && $_FILES['hinhanh_extra2']['error'] == UPLOAD_ERR_OK) {
        $extra_image2 = $_FILES['hinhanh_extra2']['name'];
        move_uploaded_file($_FILES['hinhanh_extra2']['tmp_name'], '../../assets/img/' . $extra_image2);
    }

    $query = "UPDATE tbl_product SET 
              category_id='$category_id',
              brand_id='$brand_id',
              product_name='$product_name',
              old_price='$old_price',
              new_price='$new_price',
              quantity='$quantity',
              is_hot='$is_hot',
              origin='$origin',
              warranty_period='$warranty_period',
              warranty_option='$warranty_option',
              features='$features',
              material='$material',
              descriptions='$description',
              main_image='$main_image',
              extra_image1='$extra_image1',
              extra_image2='$extra_image2'
          WHERE product_id=$id";

    if (mysqli_query($con, $query)) {
        header('Location: quanli.php');
    } else {
        $_SESSION['errors'] = "Lỗi khi sửa bản ghi: " . mysqli_error($con) . " Query: " . $query; // Lưu lỗi vào phiên
        header("Location: quanli.php?id=$id"); // Giữ lại ID trong URL
        exit();
    }
}



// Xử lý xóa sản phẩm
function delete($con, $ids_to_delete)
{
    $ids_string = implode(',', array_map('intval', $ids_to_delete));
    $query = "DELETE FROM tbl_product WHERE product_id IN ($ids_string)";
    if (mysqli_query($con, $query)) {
        header('Location: quanli.php');
        exit();
    } else {
        echo "Lỗi khi xóa các bản ghi: " . mysqli_error($con);
    }
}

// Xử lý thêm sản phẩm
function add($con)
{
    $category_id = $_POST['danhmuc_id'];
    $brand_id = $_POST['thuonghieu_id'];
    $product_name = $_POST['name'];
    $old_price = $_POST['price_old'];
    $new_price = $_POST['price_new'];
    $quantity = $_POST['soluong'];
    $is_hot = $_POST['hot'];
    $origin = $_POST['xuatxu'];
    $warranty_period = $_POST['baohanh_date'];
    $warranty_option = $_POST['baohanh_option'];
    $features = $_POST['tinhnang'];
    $material = $_POST['chatlieu'];
    $description = $_POST['mota'];
    $main_image = $_FILES['hinhanh_main']['name'];
    $extra_image1 = $_FILES['hinhanh_extra1']['name'];
    $extra_image2 = $_FILES['hinhanh_extra2']['name'];

    // Upload hình ảnh
    move_uploaded_file($_FILES['hinhanh_main']['tmp_name'], '../../assets/img/' . $main_image);
    move_uploaded_file($_FILES['hinhanh_extra1']['tmp_name'], '../../assets/img/' . $extra_image1);
    move_uploaded_file($_FILES['hinhanh_extra2']['tmp_name'], '../../assets/img/' . $extra_image2);

    // Kiểm tra tồn tại của brand_id và category_id
    $brand_check_query = "SELECT COUNT(*) AS cnt FROM tbl_brand WHERE brand_id = $brand_id";
    $brand_check_result = mysqli_query($con, $brand_check_query);
    $brand_check_row = mysqli_fetch_assoc($brand_check_result);

    if ($brand_check_row['cnt'] == 0) {
        $_SESSION['errors'] = "Brand ID không tồn tại.";
        header('Location: ' . $_SERVER['PHP_SELF'] . '?add=true');
        exit();
    }

    $category_check_query = "SELECT COUNT(*) AS cnt FROM tbl_category WHERE category_id = $category_id";
    $category_check_result = mysqli_query($con, $category_check_query);
    $category_check_row = mysqli_fetch_assoc($category_check_result);

    if ($category_check_row['cnt'] == 0) {
        $_SESSION['errors'] = "Category ID không tồn tại.";
        header('Location: ' . $_SERVER['PHP_SELF'] . '?add=true');
        exit();
    }

    $query = "INSERT INTO tbl_product (category_id, brand_id, product_name, old_price, new_price, quantity, is_hot, main_image, extra_image1, extra_image2, origin, warranty_period, warranty_option, features, material, descriptions)
    VALUES ('$category_id', '$brand_id', '$product_name', '$old_price', '$new_price', '$quantity', '$is_hot', '$main_image', '$extra_image1', '$extra_image2', '$origin', '$warranty_period', '$warranty_option', '$features', '$material', '$description')";

    if (mysqli_query($con, $query)) {
        header('Location: quanli.php');
        exit(); // Đảm bảo ngừng thực hiện mã sau khi chuyển hướng
    } else {
        $_SESSION['errors'] = "Lỗi khi thêm sản phẩm: " . mysqli_error($con);
        header('Location: ' . $_SERVER['PHP_SELF'] . '?add=true');
        exit();
    }
}


function getImage($con, $sanpham_id)
{
    $sql = "SELECT main_image, extra_image1, extra_image2 FROM tbl_product WHERE product_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $sanpham_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}
