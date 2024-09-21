<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit();
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admins = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
   $delete_admins->execute([$delete_id]);
   header('location:admin_accounts.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Các Tài Khoản Quản Trị</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="accounts">

   <h1 class="heading">Các Tài Khoản Quản Trị</h1>

   <div class="box-container">

   <div class="box">
      <p>Thêm quản trị viên mới</p>
      <a href="register_admin.php" class="option-btn">Đăng ký quản trị viên</a>
   </div>

   <?php
      $select_accounts = $conn->prepare("SELECT * FROM `admins`");
      $select_accounts->execute();
      if($select_accounts->rowCount() > 0){
         while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){   
   ?>
   <div class="box">
      <p> ID quản trị viên : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> Tên quản trị viên : <span><?= $fetch_accounts['name']; ?></span> </p>
      <div class="flex-btn">
         <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?')" class="delete-btn">Xóa</a>
         <?php
            if($fetch_accounts['id'] == $admin_id){
               echo '<a href="update_profile.php" class="option-btn">Cập nhật</a>';
            }
         ?>
      </div>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Chưa có tài khoản nào!</p>';
      }
   ?>

   </div>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>
