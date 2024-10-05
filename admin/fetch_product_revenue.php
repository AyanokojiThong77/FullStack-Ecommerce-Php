<?php
include '../components/connect.php';

// Truy vấn tất cả các đơn hàng đã hoàn thành từ bảng orders
$select_orders = $conn->prepare("SELECT total_products FROM `orders` WHERE payment_status = 'completed'");
$select_orders->execute();

// Tạo một mảng để lưu số lượng bán của từng sản phẩm
$product_sales = [];

// Duyệt qua từng đơn hàng để phân tích chuỗi sản phẩm
while ($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)) {
    // Lấy danh sách sản phẩm từ cột `total_products`
    $products = explode(" - ", $fetch_order['total_products']);
    
    foreach ($products as $product) {
        if (!empty($product)) {
            // Tách chuỗi để lấy tên sản phẩm và số lượng
            preg_match('/(.+)\((\d+) x (\d+)\)/', $product, $matches);
            
            if (!empty($matches)) {
                $product_name = trim($matches[1]); // Lấy tên sản phẩm
                $quantity_sold = (int) $matches[3]; // Lấy số lượng đã bán

                // Cộng dồn số lượng sản phẩm vào mảng
                if (isset($product_sales[$product_name])) {
                    $product_sales[$product_name] += $quantity_sold;
                } else {
                    $product_sales[$product_name] = $quantity_sold;
                }
            }
        }
    }
}

// Chuyển đổi dữ liệu thành định dạng JSON cho biểu đồ tròn
$labels = array_keys($product_sales);
$revenue = array_values($product_sales);

// Trả về dữ liệu dưới dạng JSON
echo json_encode(['labels' => $labels, 'revenue' => $revenue]);
?>
