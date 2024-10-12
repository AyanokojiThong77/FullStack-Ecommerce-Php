<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'Email đã tồn tại!';
   }else{
      if($pass != $cpass){
         $message[] = 'Xác nhận mật khẩu không khớp!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $message[] = 'Đăng ký thành công, vui lòng đăng nhập!';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng ký</title>
   
   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      body {
         font-size: 1.25rem; /* Tăng kích thước chữ */
      }
   </style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="container mt-5">

   <form action="" method="post" class="bg-light p-4 rounded shadow">
      <h3 class="text-center">Đăng ký ngay</h3>
      <input type="text" name="name" required placeholder="Nhập tên người dùng của bạn" maxlength="20" class="form-control mb-3">
      <input type="email" name="email" required placeholder="Nhập email của bạn" maxlength="50" class="form-control mb-3" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Nhập mật khẩu của bạn" maxlength="20" class="form-control mb-3" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Xác nhận mật khẩu của bạn" maxlength="20" class="form-control mb-3" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Đăng ký ngay" class="btn btn-primary w-100" name="submit">
      <p class="text-center mt-3">Đã có tài khoản?</p>
      <a href="user_login.php" class="btn btn-link">Đăng nhập ngay</a>
   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
