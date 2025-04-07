<?php
session_start();
include_once('connect.php');

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {



    // ____________________________________________________________________Xử lí form Đăng nhập__________________________________________________________________//
    if (isset($_POST['dangnhap'])) {
        $account = $_POST['login_account'];
        $password = $_POST['login_password'];

        $sql = "SELECT * FROM tbl_user WHERE user_account = '$account' AND user_pass = '$password'";
        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_array($result);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['user_rule'] = $user['rule'];
            $_SESSION['user'] = $user;

            if (isset($_SESSION['error_login'])) {
                unset($_SESSION['error_login']);
            }
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            $_SESSION['error_login'] = 'Đăng nhập không thành công. Vui lòng thử lại !!!';
            header("Location: ../frontend/index.php");
            exit;
        }
    }



    // _________________________________________________________________________Xử lí form Đăng ký_________________________________________________________________//
    if (isset($_POST['dangky'])) {
        $name = $_POST['register_name'];
        $phone = $_POST['register_phone'];
        $email = $_POST['register_email'];
        $address = $_POST['register_address'];
        $account = $_POST['register_account'];
        $pass = $_POST['register_password'];
        mysqli_query($con, "INSERT INTO tbl_user (user_name, user_phone, user_email, user_address, user_account, user_pass) 
                VALUES ('$name', '$phone', '$email', '$address', '$account', '$pass')");
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }



    // _______________________________________________________________Xử lí xóa Session khi đăng xuất______________________________________________________________//
    if (isset($_POST['logout'])) {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }

        session_destroy();
        header("Location: ../frontend/index.php");
        exit;
    }



    //__________________________________________________________________Xu ly cac action____________________________________________________________________________//
    if (isset($_POST['action'])) {



        // _________________________________________________________________Xử lí cập nhập thông tin tài khoản__________________________________________________________//
        if ($_POST['action'] === 'update_account') {

            if (isset($_FILES['user_picture']) && $_FILES['user_picture']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['user_picture']['tmp_name'];
                $fileName = $_FILES['user_picture']['name'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));

                $uploadFileDir = '../assets/img/user/';
                $dest_path = $uploadFileDir . $fileName;

                if (file_exists($dest_path)) {
                    $dest_path = $uploadFileDir . time() . '_' . $fileName;
                }

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    echo 'Tệp hình ảnh đã được lưu thành công: ' . $dest_path;
                } else {
                    echo 'Có lỗi khi di chuyển tệp hình ảnh.';
                }
            }

            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $email = $_POST['email'] ?? '';
            $address = $_POST['address'] ?? '';

            mysqli_query($con, "UPDATE tbl_user SET user_name = '$name', user_phone = '$phone', user_email = '$email', user_address = '$address', user_picture = '$fileName' WHERE user_id = $user_id");
        }



        // _____________________________________________________Xử lí cập nhập thông tin địa chỉ______________________________________________//
        if ($_POST['action'] === 'update_address') {

            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';

            mysqli_query($con, "UPDATE tbl_user SET user_phone = '$phone', user_address = '$address' WHERE user_id = $user_id");
        }



        // _____________________________________________________Xử lí cập nhập thông tin lịch sử tìm kiếm______________________________________________//
        if ($_POST['action'] === 'update_history') {

            $valueSearch = $_POST['valueSearch'] ?? '';

            mysqli_query($con, "INSERT INTO tbl_searchHistory (searchHistory_name) VALUES ('$valueSearch')");
        }



        // ________________________________________________________________Xử lí xóa sản phẩm trong giỏ hàng__________________________________________________________//
        if ($_POST['action'] === 'cart_delete') {

            $product_id = $_POST['productId'] ?? '';

            $sql_checkgiohang = mysqli_query($con, "SELECT * FROM tbl_cart WHERE product_id = $product_id AND user_id = $user_id");
            if (mysqli_num_rows($sql_checkgiohang) > 0) {
                mysqli_query($con, "DELETE FROM tbl_cart WHERE product_id = $product_id AND user_id = $user_id");
            }
        }



        // ________________________________________________________________Xử lí sửa, thêm sản phẩm vào giỏ hàng_____________________________________________________//
        if ($_POST['action'] === 'cart_update') {

            $product_id = $_POST['productId'] ?? '';
            $quantity = $_POST['quantity'] ?? '';

            $sql_checkgiohang = mysqli_query($con, "SELECT * FROM tbl_cart WHERE product_id = $product_id AND user_id = $user_id");
            if (mysqli_num_rows($sql_checkgiohang) == 0) {
                mysqli_query($con, "INSERT INTO tbl_cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', '$quantity')");
            } else {
                mysqli_query($con, "UPDATE tbl_cart SET quantity = $quantity WHERE product_id = $product_id AND user_id = $user_id");
            }
        }



        // ________________________________________________________________Xử lí thêm sản phẩm vào đơn hàng_______________________________________________________//
        if ($_POST['action'] === 'order_add') {

            $product_id = $_POST['productId'] ?? '';
            $orderCode = $_POST['orderCode'] ?? '';
            $numberBuy = $_POST['numberBuy'] ?? '';
            $onePrice = $_POST['onePrice'] ?? '';
            $payPrice = $onePrice * $numberBuy;

            mysqli_query($con, "INSERT INTO tbl_orders (transaction_code, user_id, product_id, quantity, total_price) VALUES ('$orderCode', '$user_id', '$product_id', '$numberBuy', '$payPrice')");
        }



        // ________________________________________________________________Xử lí xoa đơn hàng_______________________________________________________//
        if ($_POST['action'] === 'delete_order') {

            $orderCode = $_POST['code'] ?? '';

            mysqli_query($con, "DELETE FROM tbl_orders WHERE transaction_code = '$orderCode'");
        }
    }
}
?>