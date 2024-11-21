<!-- thembaocao.php -->
<?php
require_once __DIR__ . '/../../database/connect.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['add'])) {
        $result = add($con);
        if ($result['status'] == 'error') {
            $_SESSION['error'] = $result['message'];
        } else {
            $_SESSION['success'] = $result['message'];
        }
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}

function add($con){
    $user = $_SESSION['user'];
    if (!isset($user['user_id'])) {
        return ['status' => 'error', 'message' => 'Bạn chưa đăng nhập!'];
    }

    $user_id = $user['user_id'];
    $problem_id = $_POST['problem_id'];
    $report_content = trim(mysqli_real_escape_string($con, $_POST['report_content']));
    $report_image = $_FILES['report_image']['name'];
    if (strlen($report_content) == 0 ) {
        return ['status' => 'error', 'message' => 'Vui lòng điền đầy đủ thông tin!'];
    }

    // Kiểm tra xem có tệp ảnh nào được tải lên hay không
    if (!empty($_FILES['report_image']['name'])) {
        // Kiểm tra lỗi khi upload ảnh
        if ($_FILES['report_image']['error'] != UPLOAD_ERR_OK) {
            return ['status' => 'error', 'message' => 'Lỗi khi upload ảnh: ' . $_FILES['report_image']['error']];
        }
        // Đảm bảo thư mục tồn tại và có quyền ghi
        $upload_dir = __DIR__ . '/../../assets/img/reports/';
        if (!is_dir($upload_dir) || !is_writable($upload_dir)) {
            return ['status' => 'error', 'message' => 'Thư mục tải lên không tồn tại hoặc không có quyền ghi!'];
        }
        // Di chuyển tệp đã upload
        if (!move_uploaded_file($_FILES['report_image']['tmp_name'], $upload_dir . $report_image)) {
            return ['status' => 'error', 'message' => 'Lỗi khi di chuyển tệp đã upload!'];
        }
    } else {
        $report_image = null;
    }

    $query = "INSERT INTO tbl_report (user_id, problem_id, report_content, report_image, report_date, report_status)
              VALUES ('$user_id', '$problem_id', '$report_content', '$report_image', NOW(), 'pending')";

    if (mysqli_query($con, $query)) {
        return ['status' => 'success', 'message' => 'Gửi báo cáo thành công!'];
    } else {
        return ['status' => 'error', 'message' => 'Lỗi: ' . mysqli_error($con)];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm báo cáo</title>
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
        <label for="problem_id">Vấn đề:
            <select id="problem_id" name="problem_id" required>
                <?php
                $query = "SELECT problem_id, problem_name FROM tbl_problem";
                $result = mysqli_query($con, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['problem_id']}'>{$row['problem_name']}</option>";
                }
                ?>
            </select>
        </label><br>
        <label for="report_content">Nội dung:
            <textarea id="report_content" name="report_content" required></textarea>
        </label><br>
        <label for="report_image" class="pict">Hình ảnh (nếu có):
            <input type="file" id="report_image" name="report_image" accept="image/*" onchange="previewImage(event)">
            <img id="image_preview" src="#" alt="Preview Image">
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