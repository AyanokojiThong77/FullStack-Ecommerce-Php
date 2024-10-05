<?php

include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:user_login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = [];

if (isset($_POST['order'])) {
    // Sanitize and validate user inputs
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $number = filter_var(trim($_POST['number']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $method = filter_var(trim($_POST['method']), FILTER_SANITIZE_STRING);

    if (!$email) {
        $message[] = 'Email không hợp lệ!';
    }

    // Build and sanitize address
    $address = filter_var(
        'Số nhà ' . trim($_POST['flat']) . ', ' . trim($_POST['street']) . ', ' . trim($_POST['city']) . ', ' . trim($_POST['state']) . ', ' . trim($_POST['country']) . ' - ' . trim($_POST['pin_code']),
        FILTER_SANITIZE_STRING
    );

    // Get total products and price
    $total_products = $_POST['total_products'] ?? 0;
    $total_price = $_POST['total_price'] ?? 0;

    // Check if the cart is not empty
    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);

    if ($check_cart->rowCount() > 0) {
        if ($method === 'momo') {
         //9704 0000 0000 0018
         //03/07
         //Nguyen Van A
            // Prepare payment data for MoMo
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            $partnerCode = 'MOMOBKUN20180529';
            $accessKey = 'klm05TvNBzhg7h7j';
            $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
            $orderId = time() . "";
            $transId = $orderId;
            $orderInfo = "Thanh toán qua MoMo";
            $amount = $total_price; // Use dynamic total price
            $redirectUrl = "http://localhost:8080/Phamhuythong/successful.php"; // Change to your actual success URL
            $ipnUrl = "https://yourwebsite.com/payment_ipn.php"; // Change to your actual IPN URL
            $extraData = "";

            // Prepare the request data
            $requestId = time() . "";
            $requestType = "payWithATM"; // Use the appropriate request type
            $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            $data = [
                'partnerCode' => $partnerCode,
                'partnerName' => "Test",
                "storeId" => "MomoTestStore",
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            ];

            // Execute the POST request to MoMo
            $result = execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);

            // Redirect to MoMo payment URL
            if (isset($jsonResult['payUrl'])) {
                // Insert order with transId set to the orderId for MoMo payments
                $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price, transId) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price, $transId]);

                header('Location: ' . $jsonResult['payUrl']);
                exit();
            } else {
                $message[] = 'Có lỗi xảy ra với MoMo. Vui lòng thử lại!';
                $errorMessage = json_encode($jsonResult); 
                  echo "<script>console.log($errorMessage);</script>";


            }
        } else {
            // Insert order for other payment methods
            try {
                // For other payment methods, transId will be NULL
                $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price, transId) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL)");
                $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

                // Clear the cart after successful order
                $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
                $delete_cart->execute([$user_id]);

                $message[] = 'Đặt hàng thành công!';
            } catch (PDOException $e) {
                error_log($e->getMessage()); // Log the error for debugging
                $message[] = 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại!';
            }
        }
    } else {
        $message[] = 'Giỏ hàng của bạn đang trống!';
    }
}

function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data))
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    // Execute post
    $result = curl_exec($ch);
    // Close connection
    curl_close($ch);
    return $result;
}

// HTML and other parts of your code follow here...

?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Thanh Toán</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

   <form action="" method="POST">

   <h3>Đơn hàng của bạn</h3>

      <div class="display-orders">
      <?php
         $grand_total = 0;
         $cart_items = [];
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
         <p> <?= htmlspecialchars($fetch_cart['name']); ?> <span>(<?= '$'.htmlspecialchars($fetch_cart['price']).' x '. htmlspecialchars($fetch_cart['quantity']); ?>)</span> </p>
      <?php
            }
         } else {
            echo '<p class="empty">Giỏ hàng của bạn đang trống!</p>';
         }
      ?>
         <input type="hidden" name="total_products" value="<?= htmlspecialchars($total_products); ?>">
         <input type="hidden" name="total_price" value="<?= htmlspecialchars($grand_total); ?>">
         <div class="grand-total">Tổng cộng : <span>$<?= htmlspecialchars($grand_total); ?></span></div>
      </div>

      <h3>Đặt hàng của bạn</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Họ tên :</span>
            <input type="text" name="name" placeholder="Nhập tên của bạn" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>Số điện thoại :</span>
            <input type="number" name="number" placeholder="Nhập số điện thoại của bạn" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
         </div>
         <div class="inputBox">
            <span>Email :</span>
            <input type="email" name="email" placeholder="Nhập email của bạn" class="box" required>
         </div>
         <div class="inputBox">
            <span>Phương thức thanh toán :</span>
            <select name="method" class="box" required>
               <option value="cod">Thanh toán khi nhận hàng</option>
               <option value="momo">MoMo</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Số nhà :</span>
            <input type="text" name="flat" placeholder="Số nhà" class="box" required>
         </div>
         <div class="inputBox">
            <span>Đường :</span>
            <input type="text" name="street" placeholder="Đường" class="box" required>
         </div>
         <div class="inputBox">
            <span>Thành phố :</span>
            <input type="text" name="city" placeholder="Thành phố" class="box" required>
         </div>
         <div class="inputBox">
            <span>Tỉnh :</span>
            <input type="text" name="state" placeholder="Tỉnh" class="box" required>
         </div>
         <div class="inputBox">
            <span>Quốc gia :</span>
            <input type="text" name="country" placeholder="Quốc gia" class="box" required>
         </div>
         <div class="inputBox">
            <span>Mã bưu điện :</span>
            <input type="text" name="pin_code" placeholder="Mã bưu điện" class="box" required>
         </div>
      </div>

      <button type="submit" name="order" class="btn">Đặt hàng</button>

      <?php
      if (!empty($message)) {
          foreach ($message as $msg) {
              echo '<div class="message">' . htmlspecialchars($msg) . '</div>';
          }
      }
      ?>

   </form>

</section>

<?php include 'components/footer.php'; ?>

</body>
</html>
