<?php
// thongke.php
require_once('../../database/connect.php');

if (isset($_POST['action']) && $_POST['action'] == 'getthongke') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Thống kê tổng quan
    $sql_overview = "SELECT 
        COUNT(DISTINCT order_id) as total_orders,
        SUM(quantity) as total_quantity,
        SUM(total_price) as total_revenue
        FROM tbl_orders 
        WHERE order_date BETWEEN '$start_date' AND '$end_date'
        AND status = 1";

    $result_overview = mysqli_query($con, $sql_overview);
    if (!$result_overview) {
        die("Lỗi truy vấn: " . mysqli_error($con));
    }

    $overview = mysqli_fetch_assoc($result_overview);

    // Thống kê theo ngày
    $sql_daily = "SELECT 
        DATE(order_date) as date,
        COUNT(DISTINCT order_id) as orders,
        SUM(quantity) as quantity,
        SUM(total_price) as revenue
        FROM tbl_orders
        WHERE order_date BETWEEN '$start_date' AND '$end_date'
        AND status = 1
        GROUP BY DATE(order_date)
        ORDER BY date";

    $result_daily = mysqli_query($con, $sql_daily);
    $daily_stats = array();
    while ($row = mysqli_fetch_assoc($result_daily)) {
        $daily_stats[] = $row;
    }

    // Kiểm tra lỗi JSON encode
    $response_data = json_encode([
        'overview' => $overview,
        'daily' => $daily_stats
    ]);

    header('Content-Type: application/json');
    if ($response_data === false) {
        echo json_encode([
            'error' => json_last_error_msg()
        ]);
    } else {
        echo $response_data;
    }
} else {
    // Hiển thị kết quả truy vấn trong HTML
    $sql_overview = "SELECT 
        COUNT(DISTINCT order_id) as total_orders,
        SUM(quantity) as total_quantity,
        SUM(total_price) as total_revenue
        FROM tbl_orders 
        WHERE order_date BETWEEN '2024-11-01' AND '2024-11-30'
        AND status = 1";

    $result_overview = mysqli_query($con, $sql_overview);
    $overview = mysqli_fetch_assoc($result_overview);

    $sql_daily = "SELECT 
        DATE(order_date) as date,
        COUNT(DISTINCT order_id) as orders,
        SUM(quantity) as quantity,
        SUM(total_price) as revenue
        FROM tbl_orders
        WHERE order_date BETWEEN '2024-11-01' AND '2024-11-30'
        AND status = 1
        GROUP BY DATE(order_date)
        ORDER BY date";

    $result_daily = mysqli_query($con, $sql_daily);
    $daily_stats = array();
    while ($row = mysqli_fetch_assoc($result_daily)) {
        $daily_stats[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="results">
        <h2>Tổng quan</h2>
        <pre><?php print_r($overview); ?></pre>

        <h2>Theo ngày</h2>
        <pre><?php print_r($daily_stats); ?></pre>

        <h2>Truy vấn SQL</h2>
        <p><?php echo $sql_overview; ?></p>
        <p><?php echo $sql_daily; ?></p>
    </div>
</body>
</html>
