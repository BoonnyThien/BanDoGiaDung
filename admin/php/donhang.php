<?php
require_once('../../database/connect.php');

function getOrders($con, $limit = 18) {
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($page - 1) * $limit;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $search_column = isset($_GET['search_column']) ? $_GET['search_column'] : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'order_id';
    $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

    $searchQuery = "";
    if (!empty($search) && !empty($search_column)) {
        $searchQuery = " AND {$search_column} LIKE '%$search%'";
    }

    $query = "SELECT 
        o.order_id, o.transaction_code, u.user_name, p.product_name, 
        o.quantity, o.total_price, o.status, o.order_date
    FROM tbl_orders AS o
    JOIN tbl_user AS u ON o.user_id = u.user_id
    JOIN tbl_product AS p ON o.product_id = p.product_id
    WHERE 1=1 $searchQuery
    ORDER BY $sort $order
    LIMIT $limit OFFSET $offset";

    $result = mysqli_query($con, $query);

    $total_query = "SELECT COUNT(*) 
    FROM tbl_orders AS o
    JOIN tbl_user AS u ON o.user_id = u.user_id
    JOIN tbl_product AS p ON o.product_id = p.product_id
    WHERE 1=1 $searchQuery";

    $total_result = mysqli_query($con, $total_query);
    $total_rows = mysqli_fetch_array($total_result)[0];
    $total_pages = ceil($total_rows / $limit);

    return [$result, $total_pages, $page, $sort, $order, $search];
}

function details($con, $id) {
    $query = "SELECT o.order_id, o.transaction_code, u.user_name, u.user_address,
        p.product_name, p.new_price,
        o.quantity, o.total_price, o.status, o.order_date
    FROM tbl_orders AS o
    JOIN tbl_user AS u ON o.user_id = u.user_id
    JOIN tbl_product AS p ON o.product_id = p.product_id 
    WHERE order_id = $id";
    
    $result = mysqli_query($con, $query);

    if ($result) {
        return mysqli_fetch_assoc($result);
    } else {
        $_SESSION['errors'] = "Lỗi khi lấy dữ liệu đơn hàng: " . mysqli_error($con);
        exit();
    }
}

function delete($con, $ids_to_delete) {
    $ids_string = implode(',', array_map('intval', $ids_to_delete));
    $query = "DELETE FROM tbl_orders WHERE order_id IN ($ids_string)";
    if (mysqli_query($con, $query)) {
        header('Location: quanlidonhang.php');
        exit();
    } else {
        $_SESSION['errors'] = "Lỗi khi xóa các bản ghi: " . mysqli_error($con);
    }
}

function deleteOrder($con, $id) {
    $query = "DELETE FROM tbl_orders WHERE order_id = $id";
    if (mysqli_query($con, $query)) {
        header('Location: quanlidonhang.php');
        exit();
    } else {
        $_SESSION['errors'] = "Lỗi khi xóa đơn hàng: " . mysqli_error($con);
    }
}

function updateStatus($con, $id) {
    $query = "SELECT status FROM tbl_orders WHERE order_id = $id";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    
    $new_status = $row['status'] == 0 ? 1 : 0;
    
    $update_query = "UPDATE tbl_orders SET status = $new_status WHERE order_id = $id";
    if (mysqli_query($con, $update_query)) {
        header('Location: quanlidonhang.php');
        exit();
    } else {
        $_SESSION['errors'] = "Lỗi khi cập nhật trạng thái: " . mysqli_error($con);
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['delete_selected']) && !empty($_POST['selected_ids'])) {
        delete($con, $_POST['selected_ids']);
    }
    
    if (isset($_POST['update_status']) && isset($_POST['order_id'])) {
        updateStatus($con, $_POST['order_id']);
    }
    
    if (isset($_POST['delete_order']) && isset($_POST['order_id'])) {
        deleteOrder($con, $_POST['order_id']);
    }
}

if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $donhang = details($con, $id);
    
    if ($donhang === null) {
        $_SESSION['error'] = "Lỗi khi lấy dữ liệu đơn hàng: " . mysqli_error($con);
        header("Location: quanlidonhang.php");
        exit();
    }
}

list($orders, $total_pages, $page, $sort, $order, $search) = getOrders($con);
?>