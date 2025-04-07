<!-- SELECT `problem_id`, `problem_name` FROM `tbl_problem` -->
 <?php
 require_once('../../database/connect.php');
 function getLinhvucs($con, $limit = 10) {
     $page = isset($_GET['linhvuc_page']) ? intval($_GET['linhvuc_page']) : 1;
     $offset = ($page - 1) * $limit;
     $search = isset($_GET['linhvuc_search']) ? $_GET['linhvuc_search'] : '';
     $search_column = isset($_GET['linhvuc_search_column']) ? $_GET['linhvuc_search_column'] : '';
     $sort = isset($_GET['linhvuc_sort']) ? $_GET['linhvuc_sort'] : 'problem_id';
     $order = isset($_GET['linhvuc_order']) ? $_GET['linhvuc_order'] : 'ASC';
 
     $searchQuery = "";
     if (!empty($search) && !empty($search_column)) {
         $searchQuery = " AND {$search_column} LIKE '%$search%'";
     }
 
     $query = "
         SELECT problem_id, problem_name
         FROM tbl_problem
         WHERE 1=1 $searchQuery
         ORDER BY $sort $order
         LIMIT $limit OFFSET $offset
     ";
     $result = mysqli_query($con, $query);
 
     $total_query = "
         SELECT COUNT(*)
         FROM tbl_problem
         WHERE 1=1 $searchQuery
     ";
     $total_result = mysqli_query($con, $total_query);
     $total_rows = mysqli_fetch_array($total_result)[0];
     $total_pages = ceil($total_rows / $limit);
 
     return [$result, $total_pages, $page, $sort, $order, $search, $search_column];
 }
 
 list($linhvucs, $total_pages, $page, $sort, $order, $search, $search_column) = getLinhvucs($con);


 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['huy'])) {
        header('Location: quanlivande.php');
        exit();
    }

    if (isset($_POST['delete_selected']) && !empty($_POST['selected_ids'])) {
        deleteLinhvuc($con, $_POST['selected_ids']);
    }

    if (empty($errors)) {
        if (isset($_POST['add'])) {
            addLinhvuc($con);
            header('Location: quanlivande.php');
            exit();
        } elseif (isset($_POST['update'])) {
            updateLinhvuc($con);
            header('Location: quanlivande.php');
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

$problem_nameInput = '';
function addLinhvuc($con) {
    $problem_name = $_POST['problem_name'];
    
    $query = "INSERT INTO tbl_problem (problem_name) VALUES ('$problem_name')";
    if (mysqli_query($con, $query)) {
        header('Location: quanlivande.php');
        exit(); // Đảm bảo ngừng thực hiện mã sau khi chuyển hướng
    } else {
        header('Location: quanlivande.php?add=true');
    }
}

function updateLinhvuc($con) {
    $id = $_POST['id'];
    $problem_name = $_POST['problem_name'];
    
    $query = "UPDATE tbl_problem SET problem_name='$problem_name' WHERE problem_id=$id";
    if (mysqli_query($con, $query)) {
        header('Location: quanlivande.php');
    } else {
        $_SESSION['errors'] = "Lỗi khi sửa bản ghi: " . mysqli_error($con); // Lưu lỗi vào phiên
        header("Location: quanlivande.php?id=$id"); // Giữ lại ID trong URL
        exit();
    }
}

function deleteLinhvuc($con, $ids_to_delete) {
    $ids_string = implode(',', array_map('intval', $ids_to_delete));
    $query = "DELETE FROM tbl_problem WHERE problem_id IN ($ids_string)";
    if (mysqli_query($con, $query)) {
        header('Location: quanlivande.php');
        exit();
    } else {
        echo "Lỗi khi xóa các bản ghi: " . mysqli_error($con);
    }
}





