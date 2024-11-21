<?php
session_start();
// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['ad'])) {
    header('Location:login.php');
    exit();
}
$user = $_SESSION['ad'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="../css/ad.css">
    <style>
        .quanli button.active {
            background-color: #fc5a31;
            font-weight: bold;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div id="intro">
        <img src="../Log/Nen.png" alt="">
        <h1>Chào mừng , <?php echo htmlspecialchars($user['user_name']); ?> </h1>
    </div>
    <div id="main" class="main">
        <nav class="top">
            <div class="top2">
                <img src="../Log/Nen.png" alt="">
                <div id="trangchu">Trang chủ</div>
            </div>
            <div class="top1">
                <p><?php echo htmlspecialchars($user['user_account']); ?></p>
                <p><img src="../../assets/img/user/<?php echo htmlspecialchars($user['user_picture']); ?>" alt="User Image"></p>
            </div>
        </nav>
        <div class="leftmenu">
            <div id="quanli" class="quanli">
                <button onclick="showOverlay('quanli.php')">quản lí sản phẩm</button>
                <button onclick="showOverlay('quanliuser.php')">quản lí người dùng</button>
                <button onclick="showOverlay('quanlidonhang.php')">quản lí đơn hàng</button>
                <button onclick="showOverlay('quanlibaocao.php')">liên hệ</button>
                <button onclick="showOverlay('thongke.php')">Thống kê</button>
                <button onclick="showOverlay('quanliluachon.php')">lựa chọn</button>
                <button><a href="login.php">Thoát</a></button>
            </div>
            <iframe id="iframe-detail" src="" frameborder="0" style="width:100%; height: calc(100vh - 50px);"></iframe>
        </div>
    </div>
</body>
<script src="../Log/ad.js"></script>
