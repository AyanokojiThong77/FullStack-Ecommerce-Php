<?php
session_start();
include 'components/connect.php';

// Kiểm tra xem có dữ liệu thanh toán không
if (isset($_SESSION['payment_data'])) {
    $payment_data = $_SESSION['payment_data'];

    // Giả lập việc gọi API và nhận phản hồi thành công
    // Thực hiện thêm logic tại đây nếu cần thiết

    // Lưu đơn hàng vào cơ sở dữ liệu
    $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insert_order->execute([$user_id, $payment_data['name'], $payment_data['number'], $payment_data['email'], 'momo', $payment_data['address'], $payment_data['total_products'], $payment_data['total_price']]);

    // Xóa giỏ hàng
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart->execute([$user_id]);

    // Xóa dữ liệu thanh toán khỏi session
    unset($_SESSION['payment_data']);

    // Hiển thị thông báo thành công
    $message[] = 'Đặt hàng thành công qua MoMo!';
} else {
    $message[] = 'Không có dữ liệu thanh toán!';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Thanh Toán MoMo</title>
</head>
<body>

<?php
// Hiển thị thông báo
if (!empty($message)) {
    foreach ($message as $msg) {
        echo '<div class="message">' . htmlspecialchars($msg) . '</div>';
    }
}
?>

<a href="checkout.php">Quay lại trang thanh toán</a>

</body>
</html>
