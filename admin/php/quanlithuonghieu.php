<?php require_once('../php/thuonghieu.php');
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
    <h1 class="h1">Quản lý thương hiệu</h1>
<div class="left">
    <nav class="left">
        <form method="GET" action="quanlithuonghieu.php">
            <div class="leftsearch">
                <p>Tìm kiếm theo</p>
                <select name="thuonghieu_search_column">
                    <option value="brand_id" <?php echo isset($_GET['thuonghieu_search_column']) && $_GET['thuonghieu_search_column'] == 'brand_id' ? 'selected' : ''; ?>>ID thương hiệu</option>
                    <option value="brand_name" <?php echo isset($_GET['thuonghieu_search_column']) && $_GET['thuonghieu_search_column'] == 'brand_name' ? 'selected' : ''; ?>>Tên thương hiệu</option>
                </select>
                <input type="text" name="thuonghieu_search" placeholder="Tìm kiếm..." value="<?php echo isset($_GET['thuonghieu_search']) ? $_GET['thuonghieu_search'] : ''; ?>">
            </div>
            <div class="leftsort">
                <p>Sắp xếp theo</p>
                <select name="thuonghieu_sort">
                    <option value="brand_id" <?php echo isset($_GET['thuonghieu_sort']) && $_GET['thuonghieu_sort'] == 'brand_id' ? 'selected' : ''; ?>>ID thương hiệu</option>
                    <option value="brand_name" <?php echo isset($_GET['thuonghieu_sort']) && $_GET['thuonghieu_sort'] == 'brand_name' ? 'selected' : ''; ?>>Tên thương hiệu</option>
                </select>
                <select name="thuonghieu_order">
                    <option value="ASC" <?php echo $order == 'ASC' ? 'selected' : ''; ?>>Tăng dần</option>
                    <option value="DESC" <?php echo $order == 'DESC' ? 'selected' : ''; ?>>Giảm dần</option>
                </select>
            </div>
            <div class="leftsearch">
            <button type="submit">Lọc</button>
            <a href="quanlithuonghieu.php?add=true">Thêm</a>
            <a href="#" class="delete-link" onclick="submitDeleteForm()">Xóa đã chọn</a>  
            </div>
        </form>
    </nav>
</div>
<?php
if (isset($_GET['id'])):
    $id = $_GET['id'];
    $query = "SELECT * FROM tbl_brand WHERE brand_id=$id";
    $result = mysqli_query($con, $query);
    $thuonghieu = mysqli_fetch_assoc($result);
?>
    <h2>Sửa thông tin thương hiệu</h2>
    <form method="POST" enctype="multipart/form-data" action="quanlithuonghieu.php?id=<?php echo $id; ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="brand_name">Tên :
            <input type="text" id="brand_name" name="brand_name" value="<?php echo $thuonghieu['brand_name']; ?>">
        </label><br>
        <label class="bt">
            <input type="submit" id="tick" name="update" value="Cập nhật">
            <input type="button" id="blank" value="Làm rỗng">
            <input type="submit" name="huy" id="huy" value="Hủy">
        </label><br>
    </form>
<?php endif; ?>

<?php if (isset($_GET['add'])): ?>
    <h2>Thêm thương hiệu</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <label for="brand_name"><p>Tên:</p>
            <input type="text" id="brand_name" name="brand_name" value="<?php echo $brand_nameInput; ?>">
        </label><br>
        <label class="bt">
            <input type="submit" id="tick" name="add" value="Thêm thương hiệu">
            <input type="button" id="blank" value="Làm rỗng">
            <input type="submit" name="huy" id="huy" value="Hủy">
        </label>
    </form>
<?php endif; ?>

<form method="POST" action="quanlithuonghieu.php">
    <table class="thuonghieu">
        <thead>
            <tr>
                <th></th>
                <th><a href="quanlithuonghieu.php?sort=brand_id&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">ID</a></th>
                <th><a href="quanlithuonghieu.php?sort=brand_name&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Tên thương hiệu</a></th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($thuonghieus)): ?>
                <tr>
                    <td><input type="checkbox" name="selected_ids[]" value="<?php echo $row['brand_id']; ?>"></td>
                    <td><?php echo $row['brand_id']; ?></td>
                    <td><?php echo $row['brand_name']; ?></td>
                    <td class="bto">
                            <div class="bt sua"><a href="quanlithuonghieu.php?id=<?php echo $row['brand_id']; ?>">Sửa</a></div>
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
        </div>

</body>
<script src="../js/thuonghieu.js"></script>
</html>