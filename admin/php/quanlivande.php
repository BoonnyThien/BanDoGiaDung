<?php require_once('../php/vande.php');  ?>
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

<h1 class="h1">Quản lý vấn đề </h1>

<div class="left">
    <nav class="left">
        <form method="GET" action="quanlivande.php">
            <div class="leftsearch">
                <p>Tìm kiếm theo</p>
                <select name="linhvuc_search_column">
                    <option value="problem_id" <?php echo isset($_GET['linhvuc_search_column']) && $_GET['linhvuc_search_column'] == 'problem_id' ? 'selected' : ''; ?>>ID Vấn đề </option>
                    <option value="problem_name" <?php echo isset($_GET['linhvuc_search_column']) && $_GET['linhvuc_search_column'] == 'problem_name' ? 'selected' : ''; ?>>Tên Vấn đề </option>
                </select>
                <input type="text" name="linhvuc_search" placeholder="Tìm kiếm..." value="<?php echo isset($_GET['linhvuc_search']) ? $_GET['linhvuc_search'] : ''; ?>">
            </div>
            <div class="leftsort">
                <p>Sắp xếp theo</p>
                <select name="linhvuc_sort">
                    <option value="problem_id" <?php echo isset($_GET['linhvuc_sort']) && $_GET['linhvuc_sort'] == 'problem_id' ? 'selected' : ''; ?>>ID Vấn đề </option>
                    <option value="problem_name" <?php echo isset($_GET['linhvuc_sort']) && $_GET['linhvuc_sort'] == 'problem_name' ? 'selected' : ''; ?>>Tên Vấn đề </option>
                </select>
                <select name="linhvuc_order">
                    <option value="ASC" <?php echo $order == 'ASC' ? 'selected' : ''; ?>>Tăng dần</option>
                    <option value="DESC" <?php echo $order == 'DESC' ? 'selected' : ''; ?>>Giảm dần</option>
                </select>
            </div>
            <div class="leftsearch">
                <button type="submit">Lọc</button>
                <a href="quanlivande.php?add=true">Thêm</a>
                <a href="#" class="delete-link" onclick="submitDeleteForm()">Xóa đã chọn</a>
            </div>
        </form>
    </nav>
</div>
<?php
if (isset($_GET['id'])):
    $id = $_GET['id'];
    $query = "SELECT * FROM tbl_problem WHERE problem_id=$id";
    $result = mysqli_query($con, $query);
    $linhvuc = mysqli_fetch_assoc($result);
?>
    <h2>Sửa thông tin Vấn đề </h2>
    <form method="POST" enctype="multipart/form-data" action="quanlivande.php?id=<?php echo $id; ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="problem_name">Tên Vấn đề :
            <input type="text" id="problem_name" name="problem_name" value="<?php echo $linhvuc['problem_name']; ?>">
        </label><br>
        <label class="bt">
            <input type="submit" id="tick" name="update" value="Cập nhật">
            <input type="button" id="blank" value="Làm rỗng">
            <input type="submit" name="huy" id="huy" value="Hủy">
        </label><br>
    </form>
<?php endif; ?>

<?php if (isset($_GET['add'])): ?>
    <h2>Thêm Vấn đề </h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <label for="problem_name">Tên Vấn đề :
            <input type="text" id="problem_name" name="problem_name" value="<?php echo $problem_nameInput; ?>">
        </label><br>
        <label class="bt">
            <input type="submit" id="tick" name="add" value="Thêm Vấn đề ">
            <input type="button" id="blank" value="Làm rỗng">
            <input type="submit" name="huy" id="huy" value="Hủy">
        </label>
    </form>
<?php endif; ?>
<form method="POST" action="quanlivande.php">
    <table class="linhvuc">
        <thead>
            <tr>
                <th></th>
                <th><a href="quanlivande.php?sort=problem_id&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">ID</a></th>
                <th><a href="quanlivande.php?sort=problem_name&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Tên Vấn đề </a></th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($linhvucs)): ?>
                <tr>
                    <td><input type="checkbox" name="selected_ids[]" value="<?php echo $row['problem_id']; ?>"></td>
                    <td><?php echo $row['problem_id']; ?></td>
                    <td><?php echo $row['problem_name']; ?></td>
                    <td class="bto">
                        <div class="bt sua"><a href="quanlivande.php?id=<?php echo $row['problem_id']; ?>">Sửa</a></div>
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
<script src="../js/linhvuc.js"></script>
</body>
</html>