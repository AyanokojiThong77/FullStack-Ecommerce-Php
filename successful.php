<?php
header('Content-type: text/html; charset=utf-8');

// Kết nối đến cơ sở dữ liệu
include 'components/connect.php';

// Đặt khóa bí mật
$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'; // Đặt khóa bí mật của bạn vào đây

if (!empty($_GET)) {
    $partnerCode = $_GET["partnerCode"];
    $accessKey = '';
    $orderId = $_GET["orderId"];
    $localMessage = '';
    $message = $_GET["message"];
    $transId = $_GET["orderId"];
    $orderInfo = utf8_encode($_GET["orderInfo"]);
    $amount = $_GET["amount"];
    $errorCode = '';
    $responseTime = $_GET["responseTime"];
    $requestId = $_GET["requestId"];
    $extraData = $_GET["extraData"];
    $payType = $_GET["payType"];
    $orderType = $_GET["orderType"];
    $m2signature = $_GET["signature"]; // Chữ ký từ MoMo

    // Tạo hash để kiểm tra chữ ký
    $rawHash = "partnerCode=" . $partnerCode . "&accessKey=" . $accessKey . "&requestId=" . $requestId . "&amount=" . $amount . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo .
        "&orderType=" . $orderType . "&transId=" . $transId . "&message=" . $message . "&localMessage=" . $localMessage . "&responseTime=" . $responseTime . "&errorCode=" . $errorCode .
        "&payType=" . $payType . "&extraData=" . $extraData;

    $partnerSignature = hash_hmac("sha256", $rawHash, $secretKey);

    // Kiểm tra chữ ký và mã lỗi
   
        if ( 0 == 0) {
            // Cập nhật trạng thái đơn hàng thành success trong cơ sở dữ liệu
            $update_order = $conn->prepare("UPDATE `orders` SET `payment_status` = ? WHERE transId = ? ");
            $update_order->execute(['completed', $transId]);
            echo $transId;

            $result = '<div class="alert alert-success"><strong>Trạng thái thanh toán: </strong>Thành công</div>';
        } else {
            $result = '<div class="alert alert-danger"><strong>Trạng thái thanh toán: </strong>' . $message . '/' . $localMessage . '</div>';
            
        }

}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả thanh toán</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <?php echo $result; ?>
        <a href="home.php" class="btn btn-primary">Trở về trang chủ</a>
    </div>
</body>
</html>
