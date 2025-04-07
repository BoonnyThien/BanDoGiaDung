<?php
require_once __DIR__ . '/../../database/connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['add'])) {
        $result = addHelp($con);
        $_SESSION['post_data']=$_POST;
        if ($result['status'] == 'error') {
            $_SESSION['error'] = $result['message'];
        } else {
            $_SESSION['success'] = $result['message'];
        }
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

function addHelp($con){
 
    $name = trim(mysqli_real_escape_string($con, $_POST['name']));
$phone = trim(mysqli_real_escape_string($con, $_POST['phone']));
$email = trim(mysqli_real_escape_string($con, $_POST['email']));
$report_content = trim(mysqli_real_escape_string($con, $_POST['report_content']));

if (strlen($name) == 0 || strlen($phone) == 0 || strlen($email) == 0 || strlen($report_content) == 0) {
    return ['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin!'];
}

if (empty($name) || empty($phone) || empty($email) || empty($report_content)) {
    return ['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin!'];
}

if (strlen($name) < 3) { 
    return ['status' => 'error', 'message' => 'Tên phải có ít nhất 3 ký tự!'];
}

if (strlen($report_content) < 10) { 
    return ['status' => 'error', 'message' => 'Nội dung báo cáo phải dài hơn 10 ký tự!'];
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return ['status' => 'error', 'message' => 'Email không hợp lệ!'];
}

if (!preg_match("/^[0-9]{10}$/", $phone)) {
    return ['status' => 'error', 'message' => 'Số điện thoại không hợp lệ!'];}

    // Thêm thông tin người dùng vào nội dung báo cáo
    $full_content = "<body>
      <p><strong>Người gửi:</strong> $name</p>
      <p><strong>Số điện thoại:</strong> $phone</p>
      <p><strong>Email:</strong> $email</p>
      <p><strong>Nội dung:</strong>$report_content</p>
    </body>";

    $query = "INSERT INTO tbl_report (user_id, problem_id, report_content,  report_date, report_status)
              VALUES (0, 6, '$full_content', NOW(), 'pending')";

    if (mysqli_query($con, $query)) {
        return ['status' => 'success', 'message' => 'Gửi trợ giúp thành công!'];
    } else {
        return ['status' => 'error', 'message' => 'Lỗi: ' . mysqli_error($con)];
    }
}
if (isset($_SESSION['post_data'])) {
    foreach ($_SESSION['post_data'] as $key => $value) {
        ${$key . 'Input'} = htmlspecialchars($value);
    }
    unset($_SESSION['post_data']); // Xóa dữ liệu sau khi lấy lại
} else {
    $nameInput = '';
    $phoneInput = '';
    $emailInput = '';
    $contentInput = '';

}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gửi trợ giúp</title>
    <link rel="stylesheet" href="../../admin/css/them.css">
</head>
<body>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="notification error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="notification success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <label for="name">Họ và tên:
            <input type="text" id="name" name="name" value="<?php echo $nameInput; ?>" required>
        </label><br>
        <label for="phone">Số điện thoại:
            <input type="tel" id="phone" name="phone" value="<?php echo $phoneInput; ?>" 
            required pattern="[0-9]{10}" 
            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);">
        </label><br>
        <label for="email">Email:
            <input type="email" id="email" name="email" value="<?php echo $emailInput; ?>" required>
        </label><br>
        <label for="report_content">Nội dung:
            <textarea id="report_content" name="report_content" value="<?php echo $contentInput; ?>" required></textarea>
        </label><br>
        <label class="bt">
            <input type="submit" id="tick" name="add" value="Gửi">
        </label>
    </form>
</body>
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('image_preview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const errorDiv = document.querySelector('.notification.error');
        const successDiv = document.querySelector('.notification.success');
        if (errorDiv) {
            setTimeout(function() { errorDiv.style.display = 'none'; }, 5000);
        }
        if (successDiv) {
            setTimeout(function() { successDiv.style.display = 'none'; }, 5000);
        }
    });
</script>
</html>