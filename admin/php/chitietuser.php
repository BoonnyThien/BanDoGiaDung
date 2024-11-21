<?php

require_once('../php/user.php');
ob_start();
include('../php/quanliuser.php');
$content = ob_get_clean();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết thông tin người dùng</title>
    <link rel="stylesheet" href="../../admin/css/details.css">
</head>

<body>
    <div class="details">
        <div class="tieude">Chi tiết người dùng</div>
        <?php if (isset($user) && !is_string($user)): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <td><?php echo $user['user_id']; ?></td>
                </tr>
                <tr>
                    <th>Tên cá nhân</th>
                    <td><?php echo $user['user_name']; ?></td>
                </tr>
                <tr>
                    <th>Số điện thoại</th>
                    <td><?php echo $user['user_phone']; ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $user['user_email']; ?></td>
                </tr>
                <tr>
                    <th>Địa chỉ</th>
                    <td><?php echo $user['user_address']; ?></td>
                </tr>
                <tr>
                    <th>Tên tài khoản</th>
                    <td><?php echo $user['user_account']; ?></td>
                </tr>
                <tr>
                    <th>Mật khẩu</th>
                    <td ><?php echo $user['user_pass']; ?></td>
                </tr>
                <tr>
                    <th>Quyền</th>
                    <td><?php 
                    $role =['1'=> 'Admin',
                '0'=>'User'];
                    echo $role[$user['rule']]; ?></td>
                </tr>
                <td ><img  class="anhuser" src="../../assets/img/user/<?php echo $user['user_picture']; ?>" alt="User Picture"></td>

            </table>
        <?php else: ?>
            <p>Không tìm thấy người dùng.</p>
        <?php endif; ?>
    </div>
</body>

</html>