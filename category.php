<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

function remove_accents($str) {
    $accents = array(
        // Accents mapping
    );
    $no_accents = array(
        // Non-accented characters mapping
    );
    return str_replace($accents, $no_accents, $str);
}

include 'components/wishlist_cart.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh mục</title>
    
    <!-- Font Awesome CDN link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>



<section class="products">
    <h1 class="heading">Sản phẩm</h1>

    <div class="box-container">
        <?php
        // Check if a category is selected
        if (isset($_GET['category'])) {
            $category = $_GET['category'];

            // Get products of the selected category
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE category = ?");
            $select_products->execute([$category]);

            $found_products = false; // Variable to check if there are matching products

            while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
                $found_products = true; // Mark as found at least one product
                ?>
                <form action="" method="post" class="box">
                    <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_product['id']); ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_product['name']); ?>">
                    <input type="hidden" name="price" value="<?= htmlspecialchars($fetch_product['price']); ?>">
                    <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_product['image_01']); ?>">
                    <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
                    <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_product['id']); ?>" class="fas fa-eye"></a>
                    <img src="uploaded_img/<?= htmlspecialchars($fetch_product['image_01']); ?>" alt="">
                    <div class="name"><?= htmlspecialchars($fetch_product['name']); ?></div>
                    <div class="flex">
                        <div class="price">
                            <span>₫</span><?= number_format(htmlspecialchars($fetch_product['price']), 0, ',', '.'); ?><span>/-</span>
                        </div>
                        <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
                    </div>
                    <input type="submit" value="Thêm vào giỏ hàng" class="btn" name="add_to_cart">
                </form>
                <?php
            }

            if (!$found_products) {
                echo '<p class="empty">Không tìm thấy sản phẩm nào trong danh mục này!</p>';
            }
        } else {
            echo '<p class="empty">Không có danh mục nào được chọn!</p>';
        }
        ?>
    </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
