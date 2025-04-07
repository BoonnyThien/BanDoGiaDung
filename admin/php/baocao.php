<?php
    require_once('../../database/connect.php');

    function getBaocaos($con, $limit = 10) {
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Trang hiện tại
        $offset = ($page - 1) * $limit; // Vị trí bắt đầu cho trang hiện tại
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $search_column = isset($_GET['search_column']) ? $_GET['search_column'] : ''; // Cột tìm kiếm
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'report_id';
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; // Thứ tự sắp xếp mặc định là tăng dần
    
        // Xây dựng điều kiện tìm kiếm
        $searchQuery = "";
        if (!empty($search) && !empty($search_column)) {
            $searchQuery = " AND {$search_column} LIKE '%$search%'";
        }
    
        // Truy vấn để lấy số bản ghi giới hạn theo trang
        $query = "SELECT r.report_id, r.user_id, u.user_name, r.problem_id ,p.problem_name, 
        r.report_content, r.report_image, r.report_date, r.report_status
        FROM tbl_report r
        JOIN tbl_user u ON r.user_id = u.user_id
        JOIN tbl_problem p ON r.problem_id = p.problem_id
        WHERE 1=1 $searchQuery
        ORDER BY $sort $order
        LIMIT $limit OFFSET $offset ";

        $result = mysqli_query($con, $query);
    
        // Tính tổng số trang
        $total_query = "SELECT COUNT(*) FROM tbl_report WHERE 1=1 $searchQuery";
        $total_result = mysqli_query($con, $total_query);
        $total_rows = mysqli_fetch_array($total_result)[0];
        $total_pages = ceil($total_rows / $limit); // Tính tổng số trang
    
        return [$result, $total_pages, $page, $sort, $order, $search];
    }
    
    list($baocaos, $total_pages, $page, $sort, $order, $search) = getBaocaos($con);

    
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $id =  $_POST['id'];
        if (isset($_POST['huy'])) {
            header('Location: quanlibaocao.php');
            exit();
        }
    
        if (isset($_POST['delete_selected']) && !empty($_POST['selected_ids'])) {
            delete($con, $_POST['selected_ids']);
        }
    
        if (empty($errors)) { 
            if (isset($_POST['update'])) {
                update($con);
                header('Location: quanlibaocao.php');
                exit();
            }
        } else {
            $_SESSION['post_data'] = $_POST; // Lưu dữ liệu đã nhập vào phiên
            if (isset($_POST['update'])) {
                header('Location: ' . $_SERVER['PHP_SELF'] . '?id=' . $id);
            }
            exit();
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) { 
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $reports = details($con, $id);
            if ($reports === null) {
                $_SESSION['error'] = "Lỗi khi lấy dữ liệu báo cáo: " . mysqli_error($con);
                header("Location: quanlibaocao.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "ID báo cáo không được cung cấp!";
            header("Location: quanlibaocao.php");
            exit();
        }
    
    }
function load()
{
    header('Location: quanlibaocao.php'); // Điều hướng về trang quản lý sản phẩm
    exit();
}
function details($con, $id) {
    $query = "SELECT r.*, u.user_name ,u.user_email,u.user_phone, a.problem_name 
    FROM tbl_report r
    JOIN tbl_user u ON r.user_id = u.user_id
    JOIN tbl_problem a ON r.problem_id = a.problem_id
    WHERE r.report_id = $id
    ";
    $result = mysqli_query($con, $query);
    if ($result) {
        return mysqli_fetch_assoc($result);
    } else {
        $_SESSION['errors']="Lỗi khi lấy dữ liệu báo cáo " . mysqli_error($con);
        exit();
    }
}

function update($con) {
    $id = $_POST['id'];
    $user_id = $_POST['user_id'];
    $report_area_id = $_POST['problem_id'];
    $report_content = $_POST['report_content'];
    $report_status = $_POST['report_status'];
    
    // Xử lý hình ảnh
    $report_image = $_POST['report_image_old'];
    if (isset($_FILES['report_image']) && $_FILES['report_image']['error'] == UPLOAD_ERR_OK) {
        $report_image = $_FILES['report_image']['name'];
        move_uploaded_file($_FILES['report_image']['tmp_name'], '../../assets/img/reports/' . $report_image);
    }
    
    $query = "
        UPDATE tbl_report SET 
            user_id='$user_id', 
            problem_id='$report_area_id', 
            report_content='$report_content', 
            report_image='$report_image', 
            report_status='$report_status'
        WHERE report_id=$id
    ";
    if (mysqli_query($con, $query)) {
        header('Location: quanlibaocao.php');
    } else {
        $_SESSION['errors'] = "Lỗi khi sửa bản ghi: " . mysqli_error($con); // Lưu lỗi vào phiên
        header("Location: quanlibaocao.php?id=$id"); // Giữ lại ID trong URL
        exit();
    }
}

function delete($con, $ids_to_delete) {
    $ids_string = implode(',', array_map('intval', $ids_to_delete));
    $query = "DELETE FROM tbl_report WHERE report_id IN ($ids_string)";
    if (mysqli_query($con, $query)) {
        header('Location: quanlibaocao.php');
        exit();
    } else {
        echo "Lỗi khi xóa các bản ghi: " . mysqli_error($con);
    }
}


 