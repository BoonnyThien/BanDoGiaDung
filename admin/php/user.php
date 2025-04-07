<?php
// SELECT `user_id`, `user_name`, `user_phone`, `user_address`, `user_account`, `user_pass`, `user_picture`, `rule` FROM `tbl_user` WHERE 1
require_once('../../database/connect.php');

function getUsers($con, $limit = 9) {
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Trang hiện tại
    $offset = ($page - 1) * $limit; // Vị trí bắt đầu cho trang hiện tại
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $search_column = isset($_GET['search_column']) ? $_GET['search_column'] : ''; // Cột tìm kiếm
    $sort = isset($_GET['sort']) ? $_GET['sort'] : 'user_id';
    $order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; // Thứ tự sắp xếp mặc định là tăng dần

    // Xây dựng điều kiện tìm kiếm
    $searchQuery = "";
    if (!empty($search) && !empty($search_column)) {
        $searchQuery = " AND {$search_column} LIKE '%$search%'";
    }

    // Truy vấn để lấy số bản ghi giới hạn theo trang
    $query = "
        SELECT user_id, user_name, user_phone,user_email, user_address, user_account, user_pass, user_picture, rule
        FROM tbl_user
        WHERE 1=1 $searchQuery
        ORDER BY $sort $order
        LIMIT $limit OFFSET $offset
    ";
    $result = mysqli_query($con, $query);

    // Tính tổng số trang
    $total_query = "SELECT COUNT(*) FROM tbl_user WHERE 1=1 $searchQuery";
    $total_result = mysqli_query($con, $total_query);
    $total_rows = mysqli_fetch_array($total_result)[0];
    $total_pages = ceil($total_rows / $limit); // Tính tổng số trang

    return [$result, $total_pages, $page, $sort, $order, $search];
}

list($users, $total_pages, $page, $sort, $order, $search) = getUsers($con);

session_start(); // Bắt đầu phiên

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $id =  $_POST['id'];
    $inputs = [
        'user_name' => 'Tên',
        'user_account' => 'Tài khoản',
        'user_address'=> 'Địa chỉ',
        'user_pass' => 'Mật khẩu'
        
    ];
    $errors = "";
    $errorss = [];
    $errorss1 = [];
    
    // Kiểm tra các trường bắt buộc
    foreach ($inputs as $key => $input) {
        if (empty($_POST[$key])) {
            $errorss[] = $input;
        }
    }
    
    // Kiểm tra email
    if (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
        $errorss1[] = 'Email không hợp lệ! &emsp;';
    }
    
    // Kiểm tra quyền chỉ có thể là 0 hoặc 1
    if ($_POST['rule'] != '0' && $_POST['rule'] != '1') {
        $errorss1[] = 'Quyền phải là 0 hoặc 1! &emsp;';
    }
    
    // Kiểm tra số điện thoại
    if (!preg_match('/^0[0-9]{9,10}$/', $_POST['user_phone'])) {
        $errorss1[] = 'Số điện thoại không hợp lệ! &emsp;';
    }
    
    // Kiểm tra hình ảnh mới nếu không có ảnh cũ
    if (empty($_FILES['user_picture']['name']) && empty($_POST['user_picture_old'])) {
        $errorss1[] = 'Hình ảnh đại diện không được để trống! &emsp;';
    }
    
    // Tạo chuỗi lỗi nếu có
    if (!empty($errorss)) {
        $errors1 = implode(', ', $errorss);
        $errors .= $errors1 . " không được để trống.&emsp;";
    }
    if (!empty($errorss1)) {
        $errors2 = implode(', ', $errorss1);
        $errors .= $errors2 . '&emsp;';
    }
    
    if (isset($_POST['huy'])) {
        header('Location: quanliuser.php');
        exit();
    }
    
    if (isset($_POST['delete_selected']) && !empty($_POST['selected_ids'])) {
            delete($con, $_POST['selected_ids']);
    }    
    
    if (empty($errors)) {
        if (isset($_POST['add'])) {
            add($con);
            header('Location: quanliuser.php');
            exit();
        } elseif (isset($_POST['update'])) {
            update($con);
            header('Location: quanliuser.php');
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

// Hiển thị lỗi từ phiên
if (isset($_SESSION['errors'])) {
    echo "<div class='error'>" . $_SESSION['errors'] . "</div>";
    unset($_SESSION['errors']); // Xóa lỗi sau khi hiển thị
}

// Khởi tạo biến đầu vào
$user_nameInput = '';
$user_phoneInput = '';
$user_emailInput = '';
$user_addressInput = '';
$user_accountInput = '';
$user_passInput = '';
$ruleInput = '';
$user_pictureInput = '';

// Lấy lại dữ liệu đã nhập từ phiên
if (isset($_SESSION['post_data'])) {
    foreach ($_SESSION['post_data'] as $key => $value) {
        ${$key . 'Input'} = htmlspecialchars($value);
    }
    unset($_SESSION['post_data']); // Xóa dữ liệu sau khi lấy lại
}


if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['id'])) {
    $id = $_GET['id']; // Lấy ID sản phẩm từ URL
    $user_images = getImage($con, $id);
    if ($user_images) {
        $user_picture = $user_images['user_picture'];
    } else {
        $user_picture = "";
    }
    
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $user = details($con, $id);
    
        if ($user === null) {
            $_SESSION['error'] = "Lỗi khi lấy dữ liệu người dùng: " . mysqli_error($con);
            header("Location: quanliuser.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "ID người dùng không được cung cấp!";
        header("Location: quanliuser.php");
        exit();
    }
}
function load()
{
    header('Location: quanliuser.php'); // Điều hướng về trang quản lý sản phẩm
    exit();
}
function details($con, $id) {
    $query = "SELECT * FROM tbl_user WHERE user_id = $id";
    $result = mysqli_query($con, $query);

    if ($result) {
        return mysqli_fetch_assoc($result);
    } else {
        $_SESSION['errors']="Lỗi khi lấy dữ liệu người dùng: " . mysqli_error($con);
        exit();
    }
}
function update($con)
{
    $id =  $_POST['id'];
    $user_name = $_POST['user_name'];
    $user_phone = $_POST['user_phone'];
    $user_email = $_POST['user_email'];
    $user_address =$_POST['user_address'];
    $user_account = $_POST['user_account'];
    $user_pass = $_POST['user_pass'];
    $rule = $_POST['rule'];

    // Xử lý hình ảnh
    $user_picture = $_POST['user_picture_old'];
    if (isset($_FILES['user_picture']) && $_FILES['user_picture']['error'] == UPLOAD_ERR_OK) {
        $user_picture = $_FILES['user_picture']['name'];
        move_uploaded_file($_FILES['user_picture']['tmp_name'], '../../assets/img/user/' . $user_picture);
    }

    $query = "UPDATE tbl_user SET 
                user_name='$user_name',
                user_phone='$user_phone',
                user_email ='$user_email',
                user_address='$user_address',
                user_account='$user_account',
                user_pass='$user_pass',
                rule='$rule',
                user_picture='$user_picture'
              WHERE user_id=$id";

    if (mysqli_query($con, $query)) {
        header('Location: quanliuser.php');
    } else {
        $_SESSION['errors'] = "Lỗi khi sửa bản ghi: " . mysqli_error($con); // Lưu lỗi vào phiên
        header("Location: quanliuser.php?id=$id"); // Giữ lại ID trong URL
        exit();
    }
}

function delete($con, $ids_to_delete)
{
    $ids_string = implode(',', array_map('intval', $ids_to_delete));
    $query = "DELETE FROM tbl_user WHERE user_id IN ($ids_string)";
    if (mysqli_query($con, $query)) {
        header('Location: quanliuser.php');
        exit();
    } else {
        echo "Lỗi khi xóa các bản ghi: " . mysqli_error($con);
    }
}
function add($con)
{
    $user_name = $_POST['user_name'];
    $user_phone = $_POST['user_phone'];
    $user_email = $_POST['user_email'];
    $user_address = $_POST['user_address'];
    $user_account = $_POST['user_account'];
    $user_pass = $_POST['user_pass'];
    $rule = $_POST['rule'];
    $user_picture = $_FILES['user_picture']['name'];

    // Upload hình ảnh
    move_uploaded_file($_FILES['user_picture']['tmp_name'], '../../assets/img/user/' . $user_picture);

    $query = "INSERT INTO tbl_user (user_name, user_phone,user_email, user_address, user_account, user_pass, user_picture, rule)
                  VALUES ('$user_name', '$user_phone','$user_email', '$user_address', '$user_account', '$user_pass', '$user_picture', '$rule')";

    if (mysqli_query($con, $query)) {
        header('Location: quanliuser.php');
        exit(); // Đảm bảo ngừng thực hiện mã sau khi chuyển hướng
    } else {
        header('Location: quanliuser.php?add=true');
    }
}
function getImage($con, $id)
{
    $sql = 'SELECT  user_picture FROM tbl_user WHERE user_id =?';
    $stmt = $con->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}
