<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
    exit();
}

if (isset($_POST['order'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
    $address = 'Số nhà ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
    $address = filter_var($address, FILTER_SANITIZE_STRING);
    $total_products = $_POST['total_products'];
    $total_price = $_POST['total_price'];

    $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $check_cart->execute([$user_id]);

    if ($check_cart->rowCount() > 0) {
        // If the payment method is MoMo
        if ($method === 'momo') {
            // Prepare payment data
            $_SESSION['payment_data'] = [
                'name' => $name,
                'number' => $number,
                'email' => $email,
                'address' => $address,
                'total_products' => $total_products,
                'total_price' => $total_price
            ];
            // Redirect to MoMo payment processing page
            header('location:init_payment.php');
            exit();
        } else {
            // Insert order for other payment methods
            $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);

            $message[] = 'Đặt hàng thành công!';
        }
    } else {
        $message[] = 'Giỏ hàng của bạn đang trống!';
    }
}
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
         }else{
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
            <input type="email" name="email" placeholder="Nhập email của bạn" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Phương thức thanh toán :</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">Thanh toán khi nhận hàng</option>
               <option value="credit card">Thẻ tín dụng</option>
               <option value="momo">Momo</option>
               <option value="paypal">Paypal</option>
               <option value="Vnpay">Vnpay</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Địa chỉ 01 :</span>
            <input type="text" name="flat" placeholder="Ví dụ: số nhà" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Địa chỉ 02 :</span>
            <input type="text" name="street" placeholder="Ví dụ: tên đường" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Thành phố :</span>
            <input type="text" name="city" placeholder="Ví dụ: Hà Nội" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Tỉnh/Thành phố :</span>
            <input type="text" name="state" placeholder="Ví dụ: Hà Nội" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Quốc gia :</span>
            <input type="text" name="country" placeholder="Ví dụ: Việt Nam" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Mã bưu chính :</span>
            <input type="number" min="0" name="pin_code" placeholder="Ví dụ: 123456" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="Đặt hàng">

   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
