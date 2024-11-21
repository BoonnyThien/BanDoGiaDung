<?php 
require_once('../../database/connect.php');

function getThuonghieus($con, $limit = 10) {
    $page = isset($_GET['thuonghieu_page']) ? intval($_GET['thuonghieu_page']) : 1;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['thuonghieu_search']) ? $_GET['thuonghieu_search'] : '';
    $search_column = isset($_GET['thuonghieu_search_column']) ? $_GET['thuonghieu_search_column'] : '';
    $sort = isset($_GET['thuonghieu_sort']) ? $_GET['thuonghieu_sort'] : 'brand_id';
    $order = isset($_GET['thuonghieu_order']) ? $_GET['thuonghieu_order'] : 'ASC';

    $searchQuery = "";
    if (!empty($search) && !empty($search_column)) {
        $searchQuery = " AND {$search_column} LIKE '%$search%'";
    }

    $query = "
        SELECT brand_id, brand_name
        FROM tbl_brand
        WHERE 1=1 $searchQuery
        ORDER BY $sort $order
        LIMIT $limit OFFSET $offset
    ";
    $result = mysqli_query($con, $query);

    $total_query = "
        SELECT COUNT(*)
        FROM tbl_brand
        WHERE 1=1 $searchQuery
    ";
    $total_result = mysqli_query($con, $total_query);
    $total_rows = mysqli_fetch_array($total_result)[0];
    $total_pages = ceil($total_rows / $limit);

    return [$result, $total_pages, $page, $sort, $order, $search, $search_column];
}
list($thuonghieus, $total_pages, $page, $sort, $order, $search, $search_column) = getThuonghieus($con);

if($_SERVER['REQUEST_METHOD']== 'POST'){
    if (isset($_POST['huy'])) {
        header('Location: quanlithuonghieu.php');
        exit();
    }
    
    if (isset($_POST['delete_selected']) && !empty($_POST['selected_ids'])) {
            delete($con, $_POST['selected_ids']);
    } 
    if (empty($errors)) {
        if (isset($_POST['add'])) {
            add($con);
            header('Location: quanlithuonghieu.php');
            exit();
        } elseif (isset($_POST['update'])) {
            update($con);
            header('Location: quanlithuonghieu.php');
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
if (isset($_SESSION['errors'])) {
    echo "<div class='error'>" . $_SESSION['errors'] . "</div>";
    unset($_SESSION['errors']); // Xóa lỗi sau khi hiển thị
}

// Khởi tạo biến đầu vào
$brand_nameInput = '';

function update($con) {
    $id = $_POST['id'];
    $brand_name = $_POST['brand_name'];

    $query = "UPDATE tbl_brand SET brand_name='$brand_name' WHERE brand_id=$id";
    if (mysqli_query($con, $query)) {
        header('Location: quanlithuonghieu.php');
    } else {
        $_SESSION['errors'] = "Lỗi khi sửa bản ghi: " . mysqli_error($con); // Lưu lỗi vào phiên
        header("Location: quanlithuonghieu.php?id=$id"); // Giữ lại ID trong URL
        exit();
    }
}

function delete($con, $ids_to_delete)
{
    $ids_string = implode(',', array_map('intval', $ids_to_delete));
    $query = "DELETE FROM tbl_brand WHERE brand_id IN ($ids_string)";
    if (mysqli_query($con, $query)) {
        header('Location: quanlithuonghieu.php');
        exit();
    } else {
        echo "Lỗi khi xóa các bản ghi: " . mysqli_error($con);
    }
}
function add($con) {
    $brand_name = $_POST['brand_name'];

    $query = "INSERT INTO tbl_brand (brand_name) VALUES ('$brand_name')";
    if (mysqli_query($con, $query)) {
        header('Location: quanlithuonghieu.php');
        exit(); // Đảm bảo ngừng thực hiện mã sau khi chuyển hướng
    } else {
        header('Location: quanlithuonghieu.php?add=true');
    }
}

