<?php require_once('../php/danhmuc.php'); ?>
<!-- SELECT `category_id`, `category_name` FROM `tbl_category` -->
 <!-- SELECT `thuonghieu_id`, `thuonghieu_name` FROM `tbl_thuonghieu` -->
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
    <h1 class="h1">Quản lý danh mục</h1>
    <div class="left">
         <nav class="left">
        <form method="GET" action="quanlidanhmuc.php">
            <div class="leftsearch">
                <p>Tìm kiếm theo</p>
                <select name="danhmuc_search_column">
                    <option value="category_id" <?php echo isset($_GET['danhmuc_search_column']) && $_GET['danhmuc_search_column'] == 'category_id' ? 'selected' : ''; ?>>ID danh mục</option>
                    <option value="category_name" <?php echo isset($_GET['danhmuc_search_column']) && $_GET['danhmuc_search_column'] == 'category_name' ? 'selected' : ''; ?>>Tên danh mục</option>
                </select>
                <input type="text" name="danhmuc_search" placeholder="Tìm kiếm..." value="<?php echo isset($_GET['danhmuc_search']) ? $_GET['danhmuc_search'] : ''; ?>">
            </div>
            <div class="leftsort">
                <p>Sắp xếp theo</p>
                <select name="danhmuc_sort">
                    <option value="category_id" <?php echo isset($_GET['danhmuc_sort']) && $_GET['danhmuc_sort'] == 'category_id' ? 'selected' : ''; ?>>ID danh mục</option>
                    <option value="category_name" <?php echo isset($_GET['danhmuc_sort']) && $_GET['danhmuc_sort'] == 'category_name' ? 'selected' : ''; ?>>Tên danh mục</option>
                </select>
                <select name="danhmuc_order">
                    <option value="ASC" <?php echo $order == 'ASC' ? 'selected' : ''; ?>>Tăng dần</option>
                    <option value="DESC" <?php echo $order == 'DESC' ? 'selected' : ''; ?>>Giảm dần</option>
                </select>
            </div>
            <div class="leftsearch">
            <button type="submit">Lọc</button>
            <a href="quanlidanhmuc.php?add=true">Thêm</a>
            <a href="#" class="delete-link" onclick="submitDeleteForm()">Xóa đã chọn</a> 
        </div>
        </form>
    </nav>
</div>

<?php
if (isset($_GET['id'])):
    $id = $_GET['id'];
    $query = "SELECT * FROM tbl_category WHERE category_id=$id";
    $result = mysqli_query($con, $query);
    $danhmuc = mysqli_fetch_assoc($result);
?>
    <h2>Sửa thông tin danh mục</h2>
    <form method="POST" enctype="multipart/form-data" action="quanlidanhmuc.php?id=<?php echo $id; ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="category_name">Tên :
            <input type="text" id="category_name" name="category_name" value="<?php echo $danhmuc['category_name']; ?>">
        </label><br>
        <label class="bt">
            <input type="submit" id="tick" name="update" value="Cập nhật">
            <input type="button" id="blank" value="Làm rỗng">
            <input type="submit" name="huy" id="huy" value="Hủy">
        </label><br>
    </form>
<?php endif; ?>

<?php if (isset($_GET['add'])): ?>
    <h2>Thêm danh mục</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <label for="category_name">Tên :
            <input type="text" id="category_name" name="category_name" value="<?php echo $category_nameInput; ?>">
        </label><br>
        <label class="bt">
            <input type="submit" id="tick" name="add" value="Thêm danh mục">
            <input type="button" id="blank" value="Làm rỗng">
            <input type="submit" name="huy" id="huy" value="Hủy">
        </label>
    </form>
<?php endif; ?>

<form method="POST" action="quanlidanhmuc.php">
    <table class="danhmuc">
        <thead>
            <tr>
                <th></th>
                <th><a href="quanlidanhmuc.php?sort=category_id&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">ID</a></th>
                <th><a href="quanlidanhmuc.php?sort=category_name&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Tên danh mục</a></th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($danhmucs)): ?>
                <tr>
                    <td><input type="checkbox" name="selected_ids[]" value="<?php echo $row['category_id']; ?>"></td>
                    <td><?php echo $row['category_id']; ?></td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td class="bto">
                            <div class="bt sua"><a href="quanlithuonghieu.php?id=<?php echo $row['category_id']; ?>">Sửa</a></div>
                        </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <input type="submit" id="delete_selected" name="delete_selected" value="Xóa đã chọn" style="display:none">
</form>
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?danhmuc_page=<?php echo $page - 1; ?>&danhmuc_search=<?php echo $search; ?>&danhmuc_search_column=<?php echo $search_column; ?>&danhmuc_sort=<?php echo $sort; ?>&danhmuc_order=<?php echo $order; ?>">Trang trước</a>
    <?php endif; ?>
    <div class="middle">
        <?php
        $range = 3; // phạm vi trang
        $start = max(1, $page - $range);
        $end = min($total_pages, $page + $range);
        for ($i = $start; $i <= $end; $i++): ?>
            <a href="?danhmuc_page=<?php echo $i; ?>&danhmuc_search=<?php echo $search; ?>&danhmuc_search_column=<?php echo $search_column; ?>&danhmuc_sort=<?php echo $sort; ?>&danhmuc_order=<?php echo $order; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
    <?php if ($page < $total_pages): ?>
        <a href="?danhmuc_page=<?php echo $page + 1; ?>&danhmuc_search=<?php echo $search; ?>&danhmuc_search_column=<?php echo $search_column; ?>&danhmuc_sort=<?php echo $sort; ?>&danhmuc_order=<?php echo $order; ?>">Trang sau</a>
    <?php endif; ?>
</div>
<script src="../js/danhmuc.js"></script>

</body>
</html>