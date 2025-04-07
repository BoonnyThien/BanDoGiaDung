<?php
require_once('../php/baocao.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/quanli.css">
</head>

<body>
    <div id="notification" class="error" style="display: <?php echo isset($_SESSION['errors']) ? 'block' : 'none'; ?>">
        <?php echo isset($_SESSION['errors']) ? $_SESSION['errors'] : ''; ?>
    </div>
    <?php unset($_SESSION['errors']); ?> <!-- Xóa lỗi sau khi hiển thị -->
    <div class="left">
        <a href="#" class="delete-link" onclick="submitDeleteForm()">Xóa đã chọn</a>
        <nav class="left">
            <form method="GET" action="quanlibaocao.php">
                <div class="leftsearch">
                    <p>Tìm kiếm theo</p>
                    <select name="search_column">
                        <option value="report_id" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'report_id' ? 'selected' : ''; ?>>ID báo cáo</option>
                        <option value="report_id" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'problem_id' ? 'selected' : ''; ?>>Vấn đề ID</option>
                        <option value="user_id" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'user_name' ? 'selected' : ''; ?>>Người dùng</option>
                        <option value="report_content" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'report_content' ? 'selected' : ''; ?>>Nội dung</option>
                    </select>
                    <input type="text" name="search" placeholder="Tìm kiếm..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                </div>
                <div class="leftsort">
                    <p>Sắp xếp theo</p>
                    <select name="sort">
                        <option value="report_id" <?php echo $sort == 'report_id' ? 'selected' : ''; ?>>ID báo cáo</option>
                        <option value="user_id" <?php echo $sort == 'user_name' ? 'selected' : ''; ?>>Người dùng</option>
                        <option value="user_id" <?php echo $sort == 'problem_id' ? 'selected' : ''; ?>>Vấn đề </option>
                        <option value="report_date" <?php echo $sort == 'report_date' ? 'selected' : ''; ?>>Ngày báo cáo</option>
                        <option value="report_status" <?php echo $sort == 'report_status' ? 'selected' : ''; ?>>Trạng thái</option>
                    </select>
                    <select name="order">
                        <option value="ASC" <?php echo $order == 'ASC' ? 'selected' : ''; ?>>Tăng dần</option>
                        <option value="DESC" <?php echo $order == 'DESC' ? 'selected' : ''; ?>>Giảm dần</option>
                    </select>
                </div>
                <button type="submit">Lọc</button>
            </form>
        </nav>
    </div>
    <?php
    if (isset($_GET['id'])):
        $id = $_GET['id'];
        $query = "SELECT * FROM tbl_report WHERE report_id=$id";
        $result = mysqli_query($con, $query);
        $report = mysqli_fetch_assoc($result);
    ?>
        <h2>Sửa thông tin báo cáo</h2>
        <form method="POST" enctype="multipart/form-data" action="quanlibaocao.php?id=<?php echo $id; ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="user_id" value="<?php echo $report['user_id']; ?>">
            <input type="hidden" name="problem_id" value="<?php echo $report['problem_id']; ?>">
            <input type="hidden" name="report_content" value="<?php echo $report['report_content']; ?>">
            <input type="hidden" name="report_image" value="<?php echo $report['report_image']; ?>">

            <label for="report_status">Trạng thái:
                <select id="report_status" name="report_status">
                    <option value="pending" <?php echo $report['report_status'] == 'đang chờ' ? 'selected' : ''; ?>>Đang chờ</option>
                    <option value="processing" <?php echo $report['report_status'] == 'đang xử lý' ? 'selected' : ''; ?>>Đang xử lý</option>
                    <option value="resolved" <?php echo $report['report_status'] == 'đã giải quyết' ? 'selected' : ''; ?>>Đã giải quyết</option>
                </select>
            </label><br>

            <label class="bt">
                <input type="submit" id="tick" name="update" value="Cập nhật">
                <input type="button" id="blank" value="">
                <input type="submit" name="huy" id="huy" value="Hủy">
            </label><br>
        </form>
    <?php endif; ?>
    <form method="POST" action="quanlibaocao.php">
        <table class="report">
            <thead>
                <tr>
                    <th></th>
                    <th><a href="quanlibaocao.php?sort=report_id&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">ID</a></th>
                    <th><a href="quanlibaocao.php?sort=user_id&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Người gửi</a></th>
                    <th><a href="quanlibaocao.php?sort=problem_id&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Vấn đề</a></th>
                    <th><a href="quanlibaocao.php?sort=report_content&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Nội dung</a></th>
                    <th>Ảnh</th>
                    <th><a href="quanlibaocao.php?sort=report_date&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Ngày báo cáo</a></th>
                    <th><a href="quanlibaocao.php?sort=report_status&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Trạng thái</a></th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($baocaos)): ?>
                    <tr>

                        <td><input type="checkbox" name="selected_ids[]" value="<?php echo $row['report_id']; ?>"></td>
                        <td><?php echo $row['report_id']; ?></td>
                        <td><?php echo $row['user_name']; ?></td>
                        <td><?php echo $row['problem_name']; ?></td>
                        <td><?php echo $row['report_content']; ?></td>
                        <td><img src="../../assets/img/reports/<?php echo $row['report_image']; ?>" alt="Report Image"></td>
                        <td><?php echo $row['report_date']; ?></td>
                        <?php $status_map = [
                            'pending' => 'Chờ',
                            'processing' => 'Đang sửa',
                            'resolved' => 'Đã sửa'
                        ];
                        ?>
                        <td><?php echo $status_map[$row['report_status']]; ?></td>
                        <td class="bto">
                            <div class="bt sua"><a href="quanlibaocao.php?id=<?php echo $row['report_id']; ?>">Sửa</a></div>
                            <div class="bt chitiet"><a href="#" onclick="showOverlay(<?php echo $row['report_id']; ?>)">Chi tiết</a></div>

                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <input type="submit" id="delete_selected" name="delete_selected" value="Xóa đã chọn" style="display:none">
    </form>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>&search_column=<?php echo $search_column; ?>&sort=<?php echo $sort; ?>&user=<?php echo $order; ?>">Trang trước</a>
        <?php endif; ?>
        <div class="middle">
            <?php
            $range = 3; // phạm vi trang
            $start = max(1, $page - $range);
            $end = min($total_pages, $page + $range);
            for ($i = $start; $i <= $end; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>&search_column=<?php echo $search_column; ?>&sort=<?php echo $sort; ?>&user=<?php echo $order; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>&search_column=<?php echo $search_column; ?>&sort=<?php echo $sort; ?>&user=<?php echo $order; ?>">Trang sau</a>
        <?php endif; ?>

        <div id="overlay" class="overlay">
            <div class="overlay-content">
                <iframe id="iframe-detail" src="" frameborder="0" width="100%" height="100%"></iframe>
                <button class="dong" onclick="hideOverlay()">Đóng</button>
            </div>
        </div>
    </div>
</body>
<script src="../js/baocao.js"></script>

</html>