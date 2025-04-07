<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../database/connect.php';

// Initialize compare products array if not exists
if (!isset($_SESSION['compare_products'])) {
    $_SESSION['compare_products'] = array_fill(0, 4, null);
}

$limit = 30;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$search_results = [];
$total_results = 0;
$has_searched = false;

// Only perform search when search term is provided
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search']) && !empty($_POST['search'])) {
    $has_searched = true;
    $search = '%' . $_POST['search'] . '%';
    
    $query = "SELECT p.*, b.brand_name 
              FROM tbl_product p
              LEFT JOIN tbl_brand b ON p.brand_id = b.brand_id
              WHERE p.product_name LIKE ? 
              OR b.brand_name LIKE ? 
              OR p.old_price LIKE ? 
              OR p.new_price LIKE ? 
              OR p.origin LIKE ? 
              OR p.material LIKE ?
              LIMIT ? OFFSET ?";
              
    $stmt = $con->prepare($query);
    $stmt->bind_param('ssssssii', $search, $search, $search, $search, $search, $search, $limit, $offset);
    $stmt->execute();
    $search_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Handle add product to comparison
if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $empty_slot = array_search(null, $_SESSION['compare_products']);
    if ($empty_slot !== false) {
        $_SESSION['compare_products'][$empty_slot] = $product_id;
    } else {
        echo "<script>alert('Bảng so sánh đã đầy!');</script>";
    }
}

// Handle remove products
if (isset($_POST['remove_ids'])) {
    foreach ($_POST['remove_ids'] as $remove_id) {
        $key = array_search($remove_id, $_SESSION['compare_products']);
        if ($key !== false) {
            $_SESSION['compare_products'][$key] = null;
        }
    }
}

// Get comparison products details
$compare_products = [];
foreach ($_SESSION['compare_products'] as $id) {
    if ($id !== null) {
        $stmt = $con->prepare("SELECT p.*, b.brand_name 
                              FROM tbl_product p 
                              JOIN tbl_brand b ON p.brand_id = b.brand_id 
                              WHERE p.product_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $compare_products[] = $stmt->get_result()->fetch_assoc();
    } else {
        $compare_products[] = null;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>So sánh sản phẩm 🔎</title>
    <link rel="stylesheet" href="../../admin/css/sosanh.css">
    <style>
    </style>
</head>
<body>
<script>
    function addToCompare(productId) {
        const form = document.createElement('form');
        form.method = 'POST';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'product_id';
        input.value = productId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
</script>
<h5>SO SÁNH NHANH ✨</h5>
<form method="POST" action="">
    <input type="submit" class="xoa" value="Xóa sản phẩm đã chọn">
    <table class="compare-table">
        <tr>
            <th></th>
            <?php for ($i = 0; $i < 4; $i++): ?>
            <td class="<?= isset($compare_products[$i]) ? 'has-product' : 'empty-cell' ?>">
                <?php if (isset($compare_products[$i])): ?>
                <input type="checkbox" name="remove_ids[]" value="<?= $compare_products[$i]['product_id'] ?>">
                <?php endif; ?>
            </td>
            <?php endfor; ?>
        </tr>
        <?php
        $fields = [
            'Hình ảnh' => ['main_image', 'image'],
            'Thương hiệu' => 'brand_name',
            'Tên sản phẩm' => 'product_name',
            'Giá cũ' => ['old_price', 'price'],
            'Giá mới' => ['new_price', 'price'],
            'Xuất xứ' => 'origin',
            'Bảo hành' => 'warranty_period',
            'Loại bảo hành' => 'warranty_option',
            'Chất liệu' => 'material'
        ];
        foreach ($fields as $label => $field): ?>
        <tr>
            <th><?= $label ?></th>
            <?php for ($i = 0; $i < 4; $i++): ?>
            <td class="<?= isset($compare_products[$i]) ? 'has-product product-cell' : 'empty-cell' ?>">
                <?php if (isset($compare_products[$i])): ?>
                    <a href="../../frontend/chitiet.php?product_id=<?= $compare_products[$i]['product_id'] ?>"  target="_blank" class="product-link">
                        <?php
                        if (is_array($field)) {
                            if ($field[1] === 'image') {
                                echo '<img src="../../assets/img/' . htmlspecialchars($compare_products[$i][$field[0]]) . '" alt="" style="width:100px;height:100px;object-fit:cover;">';
                            } else if ($field[1] === 'price') {
                                echo '<span class="pri">' . number_format($compare_products[$i][$field[0]], 0, ',', '.') . 'đ</span>';
                            }
                        } else {
                            echo htmlspecialchars($compare_products[$i][$field]);
                        }
                        ?>
                    </a>
                <?php endif; ?>
            </td>
            <?php endfor; ?>
        </tr>
        <?php endforeach; ?>
    </table>
</form>

<form method="POST" action="" class="search-form">
    <div class="sosanh">
        <div class="ssname">So sánh sản phẩm 🔎</div>
        <input type="text" name="search" placeholder="Tìm kiếm sản phẩm">
        <input type="submit" value="Tìm kiếm">
    </div>
</form>
<h5>LỰA CHỌN SẢN PHẨM SO SÁNH ✨</h5>
<div class="search-results">
    <?php if (!$has_searched): ?>
        <?php for ($i = 0; $i < 5; $i++): ?>
        <div class="card empty-card">
            <div class="sanpham thuonghieu">Chưa có sản phẩm</div>
            <div class="placeholder-image"></div>
            <div class="sanpham name">Vui lòng tìm kiếm sản phẩm</div>
            <nav class="price">
                <div class="sanpham old_price pri">---</div>
                <div class="sanpham percent">---%</div>
            </nav>
            <div class="sanpham new_price pri">---</div>
        </div>
        <?php endfor; ?>
    <?php else: ?>
        <?php foreach ($search_results as $product): ?>
        <div class="card" onclick="addToCompare(<?= $product['product_id'] ?>)">
            <div class="sanpham thuonghieu"><?= htmlspecialchars($product['brand_name']) ?></div>
            <img src="../../assets/img/<?= htmlspecialchars($product['main_image']) ?>" alt="">
            <div class="sanpham name"><?= htmlspecialchars($product['product_name']) ?></div>
            <nav class="price">
                <div class="sanpham old_price pri"><?= number_format($product['old_price'], 0, ',', '.') ?>đ</div>
                <div class="sanpham percent">
                    <?php 
                    $discount = ($product['old_price'] - $product['new_price']) / $product['old_price'] * 100;
                    echo number_format($discount, 1) . '%';
                    ?>
                </div>
            </nav>
            <div class="sanpham new_price pri"><?= number_format($product['new_price'], 0, ',', '.') ?>đ</div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>