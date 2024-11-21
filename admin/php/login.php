<?php
session_start();
require_once('../../database/connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_account = $_POST['user_account'];
    $user_pass = $_POST['user_pass'];

    // Truy vấn dữ liệu từ cơ sở dữ liệu
    $query = "SELECT * FROM tbl_user WHERE user_account = '$user_account' AND user_pass = '$user_pass'";
    $result = mysqli_query($con, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        if ($user['rule'] == 1) {
            $_SESSION['ad'] = $user; // Đăng nhập thành công
            header('Location: ../php/ad.php'); // Chuyển hướng tới trang mới
            exit();
        } else {
            $_SESSION['error'] = "Không đủ quyền.";
        }
    } else {
        $_SESSION['error'] = "Tài khoản hoặc mật khẩu không đúng.";
    }
    header('Location: login.php'); // Trở lại trang đăng nhập với thông báo lỗi
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        @import url("https://fonts.googleapis.com/css?family=Raleway:400,700");

        *,
        *:before,
        *:after {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: "Raleway", sans-serif;
            position: relative;
        }

        .container {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .container:hover .top:before,
        .container:hover .top:after,
        .container:hover .bottom:before,
        .container:hover .bottom:after,
        .container:active .top:before,
        .container:active .top:after,
        .container:active .bottom:before,
        .container:active .bottom:after {
            margin-left: 200px;
            transform-origin: -200px 50%;
            transition-delay: 0s;
        }

        .container:hover .center,
        .container:active .center {
            opacity: 1;
            transition-delay: 0.2s;
        }

        .top:before,
        .top:after,
        .bottom:before,
        .bottom:after {
            content: "";
            display: block;
            position: absolute;
            width: 200vmax;
            height: 200vmax;
            top: 50%;
            left: 50%;
            margin-top: -100vmax;
            transform-origin: 0 50%;
            transition: all 0.5s cubic-bezier(0.445, 0.05, 0, 1);
            z-index: 10;
            opacity: 0.65;
            transition-delay: 0.2s;
        }

        .top:before {
            transform: rotate(45deg);
            background: #e46569;
        }

        .top:after {
            transform: rotate(135deg);
            background: #ecaf81;
        }

        .bottom:before {
            transform: rotate(-45deg);
            background: #60b8d4;
        }

        .bottom:after {
            transform: rotate(-135deg);
            background: #3745b5;
        }

        .center {
            position: absolute;
            width: 400px;
            height: 400px;
            top: 50%;
            left: 50%;
            margin-left: -200px;
            margin-top: -200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.445, 0.05, 0, 1);
            transition-delay: 0s;
            color: #333;
        }

        .center input {
            width: 100%;
            padding: 15px;
            margin: 5px;
            border-radius: 1px;
            border: 1px solid #ccc;
            font-family: inherit;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 5px;
            transform: translateY(-50%);
            font-size: 10px;
            cursor: pointer;
        }

        .error {
            background-color: red;
            color: white;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            box-shadow: 3px 3px 2px lightsalmon;
            text-shadow: 2px 2px 5px lightgray;
            width: 100%;
            position: absolute;
            z-index: 100;
        }

        input[type="submit"] {
            padding: 15px 25px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.1s ease;
        }

        input[type="submit"]:hover {
            cursor: pointer;
            font-weight: bold;
            color: lightsalmon;
            background-color: white;
            border-radius: 20px;
            transform: scale(1.1);
            text-shadow: 2px 2px 10px liht;
        }

        .password-container {
            position: relative;
            width: 100%;
        }

        .password-container input[type="password"],
        .password-container input[type="text"] {
            padding-right: 40px;
            /* Đảm bảo đủ không gian cho nút con mắt */
            transition: background-color 0.3s ease, transform 0.3s ease;
            border-radius: 5px;
        }

        input[type="password"]:hover,
        input[type="text"]:hover {
            transform: scale(1.1);
            border: 3px solid lightsalmon;
            box-shadow: 5px 5px 30px lightsalmon;
            border-radius: 10px;
        }

        h2 {
            color: lightsalmon;
            text-transform: uppercase;
            border-radius: 25px;
            font-size: 28x;
            box-shadow:  5px  5px 200px lightsalmon;
        }
    </style>
</head>

<body>
    <div class="container">
        <div id="error" class="error"><?php if (isset($_SESSION['error'])) echo $_SESSION['error']; ?></div>
        <div class="top"></div>
        <div class="bottom"></div>
        <div class="center">
            <h2>Đăng nhập</h2>
            <form method="POST" action="login.php">
                <div class="password-container">
                    <input type="text" name="user_account" placeholder="Tên tài khoản" required />
                </div>
                <div class="password-container">
                    <input type="password" id="password" name="user_pass" placeholder="Mật khẩu" required />
                    <span class="toggle-password" onclick="togglePasswordVisibility()">👀</span>
                </div>
                <input type="submit" value="Sign In" />
            </form>
            <h2>&nbsp;</h2>
        </div>
</body>

<script>
    // Kiểm tra nếu có thông báo lỗi
    if (document.getElementById('error').innerText.trim() !== "") {
        setTimeout(function() {
            document.getElementById('error').style.display = 'none';
        }, 5000); // 5000ms tương đương với 5 giây
    }

    function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password');
        var toggleIcon = document.querySelector('.toggle-password');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.textContent = '🙈'; // Biểu tượng khác khi hiển thị mật khẩu
        } else {
            passwordInput.type = 'password';
            toggleIcon.textContent = '👀'; // Biểu tượng con mắt khi ẩn mật khẩu
        }
    }
</script>
</body>

</html>