<?php
// thongke.php
require_once('../../database/connect.php');

if (isset($_POST['action']) && $_POST['action'] == 'getthongke') {
    header('Content-Type: application/json; charset=utf-8');
    
    $start_date = mysqli_real_escape_string($con, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($con, $_POST['end_date']);

    try {
        // Thống kê tổng quan - giữ nguyên như cũ
        $sql_overview = "SELECT 
            COUNT(DISTINCT order_id) as total_orders,
            COALESCE(SUM(quantity), 0) as total_quantity,
            COALESCE(SUM(total_price), 0) as total_revenue
            FROM tbl_orders 
            WHERE order_date BETWEEN ? AND ?
            AND status = 1";
            
        $stmt = mysqli_prepare($con, $sql_overview);
        mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
        mysqli_stmt_execute($stmt);
        $result_overview = mysqli_stmt_get_result($stmt);
        $overview = mysqli_fetch_assoc($result_overview);

        // Tạo bảng tạm chứa tất cả các ngày trong khoảng
        $sql_daily = "
            WITH RECURSIVE date_range AS (
                SELECT ? as date
                UNION ALL
                SELECT DATE_ADD(date, INTERVAL 1 DAY)
                FROM date_range
                WHERE DATE_ADD(date, INTERVAL 1 DAY) <= ?
            )
            SELECT 
                date_range.date as date,
                COALESCE(COUNT(DISTINCT o.order_id), 0) as orders,
                COALESCE(SUM(o.quantity), 0) as quantity,
                COALESCE(SUM(o.total_price), 0) as revenue
            FROM date_range
            LEFT JOIN tbl_orders o ON DATE(o.order_date) = date_range.date 
                AND o.status = 1
            GROUP BY date_range.date
            ORDER BY date_range.date";

        $stmt = mysqli_prepare($con, $sql_daily);
        mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
        mysqli_stmt_execute($stmt);
        $result_daily = mysqli_stmt_get_result($stmt);
        
        $daily_stats = [];
        while ($row = mysqli_fetch_assoc($result_daily)) {
            $daily_stats[] = $row;
        }

        $response = [
            'success' => true,
            'overview' => $overview,
            'daily' => $daily_stats
        ];

        echo json_encode($response);
        exit;
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê doanh thu</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" rel="stylesheet">
    <style>
        .thongke-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .chart-container {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        .stat-label {
            color: #7f8c8d;
            font-size: 14px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Thống kê doanh thu</h2>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="date_range" class="form-control" placeholder="Chọn khoảng thời gian">
                    <button class="btn btn-primary" id="filter_btn">Lọc</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="thongke-card">
                    <div class="stat-value" id="total_orders">0</div>
                    <div class="stat-label">Tổng đơn hàng</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="thongke-card">
                    <div class="stat-value" id="total_quantity">0</div>
                    <div class="stat-label">Tổng số lượng bán</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="thongke-card">
                    <div class="stat-value" id="total_revenue">0</div>
                    <div class="stat-label">Tổng doanh thu</div>
                </div>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

    <script>
        const dateRangePicker = flatpickr("#date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: [
                new Date().setDate(new Date().getDate() - 30),
                new Date()
            ]
        });

        let revenueChart = null;

        function formatCurrency(value) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(value);
        }

        function initChart() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Doanh thu',
                        data: [],
                        borderColor: '#2980b9',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Biểu đồ doanh thu theo ngày'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Doanh thu: ' + formatCurrency(context.raw);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        async function updateThongKe() {
            const dates = dateRangePicker.selectedDates;
            if (dates.length !== 2) return;

            const start_date = dates[0].toISOString().split('T')[0];
            const end_date = dates[1].toISOString().split('T')[0];

            try {
                const formData = new FormData();
                formData.append('action', 'getthongke');
                formData.append('start_date', start_date);
                formData.append('end_date', end_date);

                const response = await fetch('thongke.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                if (!data.success) {
                    throw new Error(data.error || 'Có lỗi xảy ra');
                }

                // Cập nhật thống kê tổng quan
                document.getElementById('total_orders').textContent = data.overview.total_orders.toLocaleString('vi-VN');
                document.getElementById('total_quantity').textContent = data.overview.total_quantity.toLocaleString('vi-VN');
                document.getElementById('total_revenue').textContent = formatCurrency(data.overview.total_revenue);

                // Cập nhật biểu đồ
                const labels = data.daily.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('vi-VN');
                });
                const revenues = data.daily.map(item => parseFloat(item.revenue));

                revenueChart.data.labels = labels;
                revenueChart.data.datasets[0].data = revenues;
                revenueChart.update();

            } catch (error) {
                console.error("Lỗi khi lấy dữ liệu:", error);
                alert('Có lỗi xảy ra khi tải dữ liệu: ' + error.message);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initChart();
            
            document.getElementById('filter_btn').addEventListener('click', updateThongKe);
            
            // Load dữ liệu ban đầu
            updateThongKe();
        });
    </script>
</body>
</html>