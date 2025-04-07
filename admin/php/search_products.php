<?php
require_once __DIR__ . '/../../database/connect.php';

if (isset($_GET['q'])) {
    $search = '%' . $_GET['q'] . '%';
    
    $query = "SELECT p.*, b.brand_name 
              FROM tbl_product p
              LEFT JOIN tbl_brand b ON p.brand_id = b.brand_id
              WHERE p.product_name LIKE ? 
              OR b.brand_name LIKE ? 
              LIMIT 6";
              
    $stmt = $con->prepare($query);
    $stmt->bind_param('ss', $search, $search);
    $stmt->execute();
    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($results);
}
?>