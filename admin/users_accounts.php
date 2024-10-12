<?php 
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

// Xóa người dùng
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_user->execute([$delete_id]);
    $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
    $delete_orders->execute([$delete_id]);
    $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE user_id = ?");
    $delete_messages->execute([$delete_id]);
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart->execute([$delete_id]);
    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
    $delete_wishlist->execute([$delete_id]);
    header('location:users_accounts.php');
    exit();
}

// Thêm người dùng mới
if (isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Mã hóa mật khẩu

    // Kiểm tra xem email đã tồn tại chưa
    $check_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $check_user->execute([$email]);

    if ($check_user->rowCount() > 0) {
        $message[] = 'Email đã tồn tại!';
    } else {
        // Thêm người dùng mới vào cơ sở dữ liệu
        $insert_user = $conn->prepare("INSERT INTO `users` (name, email, password) VALUES (?, ?, ?)");
        $insert_user->execute([$name, $email, $password]);
        $message[] = 'Thêm người dùng thành công!';
        header('location:users_accounts.php');
        exit();
    }
}

// Cập nhật thông tin người dùng
if (isset($_POST['edit_user'])) {
    $edit_id = $_POST['edit_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Cập nhật tên và email của người dùng
    $update_user = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
    $update_user->execute([$name, $email, $edit_id]);
    $message[] = 'Cập nhật thông tin người dùng thành công!';
    header('location:users_accounts.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tài Khoản Người Dùng</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
      body {
         font-size: 1.5rem; /* Tăng kích thước chữ */
      }
      .box {
         margin-bottom: 1.5rem;
         padding: 1rem;
         border: 1px solid #ccc;
         border-radius: 5px;
      }
      .empty {
         text-align: center;
         font-size: 1.5rem;
         color: #888;
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="accounts">
   <h1 class="heading">Tài Khoản Người Dùng</h1>

   <!-- Form thêm người dùng mới -->
   <div class="add-user">
       <form action="" method="post">
           <h3>Thêm Người Dùng Mới</h3>
           <input type="text" name="name" required placeholder="Nhập tên người dùng" class="box">
           <input type="email" name="email" required placeholder="Nhập email" class="box">
           <input type="password" name="password" required placeholder="Nhập mật khẩu" class="box">
           <input type="submit" value="Thêm Người Dùng" name="add_user" class="btn">
       </form>
   </div>

   <div class="box-container">
       <?php
           // Hiển thị thông báo nếu có
           if (isset($message)) {
               foreach ($message as $msg) {
                   echo '<p class="message">'.$msg.'</p>';
               }
           }

           $select_accounts = $conn->prepare("SELECT * FROM `users`");
           $select_accounts->execute();
           if ($select_accounts->rowCount() > 0) {
               while ($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)) {   
       ?>
       <div class="box">
           <p>ID Người Dùng: <span><?= $fetch_accounts['id']; ?></span></p>
           <p>Tên Người Dùng: <span><?= $fetch_accounts['name']; ?></span></p>
           <p>Email: <span><?= $fetch_accounts['email']; ?></span></p>
           
           <!-- Form chỉnh sửa người dùng -->
           <form action="" method="post" class="edit-form">
               <input type="hidden" name="edit_id" value="<?= $fetch_accounts['id']; ?>">
               <input type="text" name="name" value="<?= $fetch_accounts['name']; ?>" class="box" required>
               <input type="email" name="email" value="<?= $fetch_accounts['email']; ?>" class="box" required>
               <input type="submit" value="Cập Nhật" name="edit_user" class="btn">
           </form>
           
           <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" 
              onclick="return confirm('Xóa tài khoản này? Tất cả thông tin liên quan cũng sẽ bị xóa!')" 
              class="delete-btn">Xóa</a>
       </div>
       <?php
               }
           } else {
               echo '<p class="empty">Không có tài khoản nào!</p>';
           }
       ?>
   </div>
</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>
