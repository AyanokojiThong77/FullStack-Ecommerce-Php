<?php
include '../components/connect.php';

// Nhận kiểu lọc từ yêu cầu AJAX
$filter = $_POST['filter'] ?? 'monthly';
$revenue = [];
$labels = [];

// Tính toán doanh thu theo từng kiểu lọc
if ($filter == 'weekly') {
    for ($i = 0; $i < 7; $i++) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $select_daily = $conn->prepare("SELECT SUM(total_price) AS total FROM `orders` WHERE DATE(placed_on) = ? AND payment_status = 'completed'");
        $select_daily->execute([$date]);
        $result = $select_daily->fetch(PDO::FETCH_ASSOC);
        $revenue[] = $result['total'] ? $result['total'] : 0;
        $labels[] = date('D', strtotime($date)); // Nhãn cho từng ngày trong tuần
    }
} elseif ($filter == 'monthly') {
    for ($i = 1; $i <= 12; $i++) {
        $select_monthly = $conn->prepare("SELECT SUM(total_price) AS total FROM `orders` WHERE MONTH(placed_on) = ? AND payment_status = 'completed'");
        $select_monthly->execute([$i]);
        $result = $select_monthly->fetch(PDO::FETCH_ASSOC);
        $revenue[] = $result['total'] ? $result['total'] : 0;
        $labels[] = 'Tháng ' . $i; // Nhãn cho từng tháng
    }
} elseif ($filter == 'quarterly') {
    for ($i = 1; $i <= 4; $i++) {
        $select_quarterly = $conn->prepare("SELECT SUM(total_price) AS total FROM `orders` WHERE QUARTER(placed_on) = ? AND payment_status = 'completed'");
        $select_quarterly->execute([$i]);
        $result = $select_quarterly->fetch(PDO::FETCH_ASSOC);
        $revenue[] = $result['total'] ? $result['total'] : 0;
        $labels[] = 'Quý ' . $i; // Nhãn cho từng quý
    }
} elseif ($filter == 'yearly') {
    $year = date('Y');
    $select_yearly = $conn->prepare("SELECT SUM(total_price) AS total FROM `orders` WHERE YEAR(placed_on) = ? AND payment_status = 'completed'");
    $select_yearly->execute([$year]);
    $result = $select_yearly->fetch(PDO::FETCH_ASSOC);
    $revenue[] = $result['total'] ? $result['total'] : 0;
    $labels[] = 'Năm ' . $year; // Nhãn cho năm hiện tại
}

// Trả về dữ liệu dưới dạng JSON
echo json_encode(['labels' => $labels, 'revenue' => $revenue]);
?>
