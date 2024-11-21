<?php
require_once('../php/baocao.php');
ob_start();
include('../php/quanlibaocao.php');
$content = ob_get_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../admin/css/details.css">
</head>
<body>
    <div class="details">
        <div class="tieude">Chi tiết báo cáo</div>
        <?php if (isset($reports) && !is_string($reports)) { ?>
        <table>
            <tr>
                <th>ID báo cáo</th>
                <td><?php echo $reports['report_id']; ?></td>
            </tr>
            <tr>
                <th>Tên người dùng</th>
                <td><?php echo $reports['user_name']; ?></td>
            </tr>
            <tr>
                <th>Số điện thoại</th>
                <td><?php echo $reports['user_phone']; ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $reports['user_email']; ?></td>
            </tr>
            <tr>
                <th>Vấn đề</th>
                <td><?php echo $reports['problem_name']; ?></td>
            </tr>
            <tr>
                <th>Nội dung</th>
                <td><?php echo $reports['report_content']; ?></td>
            </tr>
            <tr>
                <th>Ngày</th>
                <td><?php echo $reports['report_date']; ?></td>
            </tr>
            <tr>
                <th>Tình trạng</th>
                <td><?php
                $status_map = [
                    'pending' => 'Chờ',
                    'processing' => 'Đang sửa',
                    'resolved' => 'Đã sửa'
                ]; 
                echo $status_map[$reports['report_status']]; ?></td>
            </tr>
            <tr>
                <td><img class="anhuser" src="../../assets/img/reports/<?php echo $reports['report_image']; ?>" alt="Picture"></td>
            </tr>
        </table>
        <?php } ?>
    </div>
</body>
</html>
