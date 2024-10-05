<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

// Lấy số trang hiện tại từ URL, nếu không có thì mặc định là trang 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 9; // Số sản phẩm mỗi trang
$offset = ($page - 1) * $items_per_page;

// Lấy tùy chọn sắp xếp từ URL
$sort_option = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'default';
$query = "SELECT * FROM `products`";

// Thêm điều kiện sắp xếp vào truy vấn
if($sort_option == 'price_asc'){
    $query .= " ORDER BY price ASC";
} elseif($sort_option == 'price_desc'){
    $query .= " ORDER BY price DESC";
} elseif($sort_option == 'name_asc'){
    $query .= " ORDER BY name ASC";
} elseif($sort_option == 'name_desc'){
    $query .= " ORDER BY name DESC";
} elseif($sort_option == 'newest'){
    $query .= " ORDER BY created_at DESC"; // Sắp xếp theo ngày đăng mới nhất
} elseif($sort_option == 'bestseller'){
    $query .= " ORDER BY total_sales DESC"; // Sắp xếp theo sản phẩm bán chạy
}

$query .= " LIMIT $items_per_page OFFSET $offset"; // Phân trang

$select_products = $conn->prepare($query); 
$select_products->execute();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cửa hàng</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      /* Định dạng cho phân trang */
      .pagination {
         text-align: center;
         margin-top: 20px;
      }

      .pagination .page-link {
         display: inline-block;
         margin: 0 5px;
         padding: 10px 15px;
         background-color: #f8f8f8;
         border: 1px solid #ddd;
         color: #333;
         border-radius: 5px;
         text-decoration: none;
      }

      .pagination .page-link:hover {
         background-color: #ddd;
      }

   </style>

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h1 class="heading">Sản phẩm mới nhất</h1>

   <!-- Form sắp xếp -->
   <form action="" method="GET" class="sort-form">
      <select name="sort_by" onchange="this.form.submit()">
         <option value="default" <?php if($sort_option == 'default'){ echo 'selected'; } ?>>Sắp xếp theo</option>
         <option value="price_asc" <?php if($sort_option == 'price_asc'){ echo 'selected'; } ?>>Giá: Thấp đến cao</option>
         <option value="price_desc" <?php if($sort_option == 'price_desc'){ echo 'selected'; } ?>>Giá: Cao đến thấp</option>
         <option value="name_asc" <?php if($sort_option == 'name_asc'){ echo 'selected'; } ?>>Tên: A-Z</option>
         <option value="name_desc" <?php if($sort_option == 'name_desc'){ echo 'selected'; } ?>>Tên: Z-A</option>
      </select>
   </form>

   <div class="box-container">

   <?php
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
      <div class="price"><?= number_format($fetch_product['price'], 0, ',', '.'); ?> <span>Vnd</span></div>

         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="Thêm vào giỏ hàng" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">Không tìm thấy sản phẩm!</p>';
   }
   ?>

   </div>

   <!-- Phân trang -->
   <div class="pagination">
   <?php
      // Lấy tổng số sản phẩm để tính tổng số trang
      $total_products_query = $conn->prepare("SELECT COUNT(*) FROM `products`");
      $total_products_query->execute();
      $total_products = $total_products_query->fetchColumn();
      $total_pages = ceil($total_products / $items_per_page);

      // Hiển thị các nút phân trang
      for ($i = 1; $i <= $total_pages; $i++) {
         echo '<a href="?page=' . $i . '&sort_by=' . $sort_option . '" class="page-link">' . $i . '</a>';
      }
   ?>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
