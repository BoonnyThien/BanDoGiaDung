<?php
// SELECT `user_id`, `user_name`, `user_phone`, `user_address`, `user_account`, `user_pass`, `user_picture`, `rule` FROM `tbl_user` WHERE 1
require_once('../../admin/php/user.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lí tài khoản</title>
    <link rel="stylesheet" href="../../admin/css/quanli.css">
</head>

<body>
    <div id="notification" class="error" style="display: <?php echo isset($_SESSION['errors']) ? 'block' : 'none'; ?>">
        <?php echo isset($_SESSION['errors']) ? $_SESSION['errors'] : ''; ?>
    </div>
    <?php unset($_SESSION['errors']); ?> <!-- Xóa lỗi sau khi hiển thị -->
    <div class="left">
        <a href="quanliuser.php?add=true">Thêm</a>
        <a href="#" class="delete-link" onclick="submitDeleteForm()">Xóa đã chọn</a>
        <nav class="left">
            <form method="GET" action="quanliuser.php">
                <div class="leftsearch">
                    <p>Tìm kiếm theo </p>
                    <select name="search_column">
                        <option value="user_id" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'user_id' ? 'selected' : ''; ?>>ID</option>
                        <option value="user_name" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'user_name' ? 'selected' : ''; ?>>Tên cá nhân</option>
                        <option value="user_phone" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'user_phone' ? 'selected' : ''; ?>>Số điện thoại</option>
                        <option value="user_email" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'user_email' ? 'selected' : ''; ?>>Email</option>
                        <option value="user_address" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'user_address' ? 'selected' : ''; ?>>Địa chỉ </option>
                        <option value="user_account" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'user_account' ? 'selected' : ''; ?>>Tài khoản</option>
                        <option value="user_pass" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'user_pass' ? 'selected' : ''; ?>>Mật khẩu</option>
                        <option value="rule" <?php echo isset($_GET['search_column']) && $_GET['search_column'] == 'rule' ? 'selected' : ''; ?>>Quyền</option>
                    </select>
                    <input type="text" name="search" placeholder="Tìm kiếm..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                </div>
                <div class="leftsort">
                    <p>Sắp xếp theo</p>
                    <select name="sort">
                        <option value="user_id" <?php echo $sort == 'user_id' ? 'selected' : ''; ?>>ID</option>
                        <option value="user_name" <?php echo $sort == 'user_name' ? 'selected' : ''; ?>>Tên cá nhân</option>
                        <option value="user_phone" <?php echo $sort == 'user_phone' ? 'selected' : ''; ?>>Số điện thoại</option>
                        <option value="user_email" <?php echo $sort == 'user_email' ? 'selected' : ''; ?>>Email</option>
                        <option value="user_address" <?php echo $sort == 'user_address' ? 'selected' : ''; ?>>Địa chỉ</option>
                        <option value="user_account" <?php echo $sort == 'user_account' ? 'selected' : ''; ?>>Tài khoản</option>
                        <option value="user_pass" <?php echo $sort == 'user_pass' ? 'selected' : ''; ?>>Mật khẩu</option>
                        <option value="rule" <?php echo $sort == 'rule' ? 'selected' : ''; ?>>Quyền</option>
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
        $query = "SELECT * FROM tbl_user WHERE user_id=$id";
        $result = mysqli_query($con, $query);
        $user = mysqli_fetch_assoc($result);
    ?>
        <h2>Sửa thông tin người dùng</h2>
        <form method="POST" enctype="multipart/form-data" action="quanliuser.php?id=<?php echo $id; ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <!-- Các trường input khác -->
            <label for="user_name">Tên cá nhân:
                <input type="text" id="user_name" name="user_name" value="<?php echo $user['user_name']; ?>">
            </label><br>

            <label for="user_phone">Số điện thoại:
                <input type="text" id="user_phone" name="user_phone" value="<?php echo $user['user_phone']; ?>">
            </label><br>

            <label for="user_email">Email:
                <input type="email" id="user_email" name="user_email" value="<?php echo $user['user_email']; ?>">
            </label><br>
            <label for="user_address">Địa chỉ:
                <input type="address" id="user_address" name="user_address" value="<?php echo $user['user_address']; ?>">
            </label><br>

            <label for="user_account">Tên tài khoản:
                <input type="text" id="user_account" name="user_account" value="<?php echo $user['user_account']; ?>">
            </label><br>

            <label for="user_pass">Mật khẩu:
                <input type="text" id="user_pass" name="user_pass" value="<?php echo $user['user_pass']; ?>">
            </label><br>
            <label for="rule">Quyền:
                <input type="text" id="rule" name="rule" min="0" max="1" value="<?php echo $user['rule']; ?>" maxlength="1" pattern="[01]" title="Chỉ nhập 0 hoặc 1">
            </label><br>
            <!-- Hình ảnh -->
            <label for="user_picture" class="pic">Hình ảnh: <br>
                <img src="../../assets/img/user/<?php echo $user_picture; ?>" alt="User Picture">
                <input type="file" id="user_picture" name="user_picture" class="pic" accept="image/*">
                <input type="hidden" name="user_picture_old" value="<?php echo $user_picture; ?>">
            </label><br>
            <label class="bt">
                <input type="submit" value="">
                <input type="submit" id="tick" name="update" value="Cập nhật">
                <input type="button" id="blank" value="Làm rỗng">
                <input type="submit" name="huy" id="huy" value="Hủy">
            </label><br>
        </form>
    <?php endif; ?>
    <?php if (isset($_GET['add'])): ?>
        <h2>Thêm người dùng</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <label for="user_name">Tên cá nhân:
                <input type="text" id="user_name" name="user_name" value="<?php echo $user_nameInput; ?>">
            </label><br>
            <label for="user_phone">Số điện thoại:
                <input type="text" id="user_phone" name="user_phone" value="<?php echo $user_phoneInput; ?>">
            </label><br>
            <label for="user_email">Email:
                <input type="text" id="user_email" name="user_email" value="<?php echo $user_emailInput; ?>">
            </label><br>
            <label for="user_address">Địa chỉ:
                <input type="text" id="user_address" name="user_address" value="<?php echo $user_addressInput; ?>">
            </label><br>
            <label for="user_account">Tên tài khoản:
                <input type="text" id="user_account" name="user_account" value="<?php echo $user_accountInput; ?>">
            </label><br>
            <label for="user_pass">Mật khẩu:
                <input type="text" id="user_pass" name="user_pass" value="<?php echo $user_passInput; ?>">
            </label><br>
            <label for="rule">Quyền:
                <input type="number" id="rule" name="rule" min="0" max="1" value="<?php echo $ruleInput; ?>">
            </label><br>
            <label for="user_picture" class="pic"> <br>
            <input type="file" id="user_picture" name="user_picture" accept="image/*" multiple onchange="previewImage(event,'image_user')">
            <img id="image_user" src="#" alt="Preview Image">
                <input type="hidden" name="user_picture_old" value="<?php echo $user_pictureInput; ?>">
            </label><br>
            <label class="bt">
                <input type="button" name="">
                <input type="submit" id="tick" name="add" value="Thêm người dùng">
                <input type="button" id="blank" value="Làm rỗng">
                <input type="submit" name="huy" id="huy" value="Hủy">
            </label>
        </form>
    <?php endif; ?>

    <form method="POST" action="quanliuser.php">
        <table class="user">
            <thead>
                <tr>
                    <th></th>
                    <th><a href="quanliuser.php?sort=user_id&user=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">ID</a></th>
                    <th><a href="quanliuser.php?sort=user_name&user=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Tên cá nhân</a></th>
                    <th><a href="quanliuser.php?sort=user_phone&user=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Số điện thoại</a></th>
                    <th><a href="quanliuser.php?sort=user_email&user=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Email</a></th>
                    <th><a href="quanliuser.php?sort=user_address&user=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Địa chỉ</a></th>
                    <th>Hình ảnh</th>
                    <th><a href="quanliuser.php?sort=rule&user=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'; ?>&search=<?php echo $search; ?>">Quyền</a></th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($users)): ?>
                    <tr>
                        <td><input type="checkbox" name="selected_ids[]" value="<?php echo $row['user_id']; ?>"></td>
                        <td><?php echo $row['user_id']; ?></td>
                        <td><?php echo $row['user_name']; ?></td>
                        <td><?php echo $row['user_phone']; ?></td>
                        <td><?php echo $row['user_email']; ?></td>
                        <td><?php echo $row['user_address']; ?></td>
                        <td><img src="../../assets/img/user/<?php echo $row['user_picture']; ?>" alt="User Image"></td>
                        <td><?php
                        $role =[
                            '1'=>'Admin',
                            '0'=>'User'
                        ]; 
                        echo $role[$row['rule']]; ?></td>
                        <td class="bto">
                            <div class="bt sua"><a href="quanliuser.php?id=<?php echo $row['user_id']; ?>">Sửa</a></div>
                            <div class="bt chitiet"><a href="#" onclick="showOverlay(<?php echo $row['user_id']; ?>)">Chi tiết</a></div>
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
<script src="../../admin/js/quanliuser.js"></script>

</html>