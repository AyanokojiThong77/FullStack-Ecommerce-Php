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
        'à', 'á', 'ả', 'ã', 'ạ', 'â', 'ầ', 'ấ', 'ẩ', 'ẫ', 'ậ', 'ă', 'ằ', 'ắ', 'ẳ', 'ẵ', 'ặ',
        'è', 'é', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ề', 'ế', 'ể', 'ễ', 'ệ',
        'ì', 'í', 'ỉ', 'ĩ', 'ị',
        'ò', 'ó', 'ỏ', 'õ', 'ọ', 'ô', 'ồ', 'ố', 'ổ', 'ỗ', 'ộ', 'ơ', 'ờ', 'ớ', 'ở', 'ỡ', 'ợ',
        'ù', 'ú', 'ủ', 'ũ', 'ụ', 'ư', 'ừ', 'ứ', 'ử', 'ữ', 'ự',
        'ỳ', 'ý', 'ỷ', 'ỹ', 'ỵ',
        'đ',
        'À', 'Á', 'Ả', 'Ã', 'Ạ', 'Â', 'Ầ', 'Ấ', 'Ẩ', 'Ẫ', 'Ậ', 'Ă', 'Ằ', 'Ắ', 'Ẳ', 'Ẵ', 'Ặ',
        'È', 'É', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ề', 'Ế', 'Ể', 'Ễ', 'Ệ',
        'Ì', 'Í', 'Ỉ', 'Ĩ', 'Ị',
        'Ò', 'Ó', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ồ', 'Ố', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ờ', 'Ớ', 'Ở', 'Ỡ', 'Ợ',
        'Ù', 'Ú', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ừ', 'Ứ', 'Ử', 'Ữ', 'Ự',
        'Ỳ', 'Ý', 'Ỷ', 'Ỹ', 'Ỵ',
        'Đ'
    );
    $no_accents = array(
        'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
        'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
        'i', 'i', 'i', 'i', 'i',
        'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
        'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
        'y', 'y', 'y', 'y', 'y',
        'd',
        'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A',
        'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E',
        'I', 'I', 'I', 'I', 'I',
        'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O',
        'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U',
        'Y', 'Y', 'Y', 'Y', 'Y',
        'D'
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
    
    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

    <h1 class="heading">Danh mục</h1>

    <div class="box-container">

    <?php
    // Kiểm tra xem có giá trị category trong URL không
    if (isset($_GET['category'])) {
        // Lấy giá trị danh mục từ URL và chuyển thành không dấu
        $category = remove_accents($_GET['category']);

        // Lấy tất cả sản phẩm từ cơ sở dữ liệu
        $select_products = $conn->prepare("SELECT * FROM `products`"); 
        $select_products->execute();

        $found_products = false; // Biến để kiểm tra nếu có sản phẩm phù hợp

        while ($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)) {
            // Kiểm tra xem tên sản phẩm không dấu có chứa từ khóa không
            if (strpos(remove_accents($fetch_product['name']), $category) !== false) {
                $found_products = true; // Đánh dấu là tìm thấy ít nhất một sản phẩm
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
            <div class="price"><span>$</span><?= htmlspecialchars($fetch_product['price']); ?><span>/-</span></div>
            <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
        </div>
        <input type="submit" value="Thêm vào giỏ hàng" class="btn" name="add_to_cart">
    </form>
    <?php
            }
        }
        if (!$found_products) {
            echo '<p class="empty">Không tìm thấy sản phẩm nào!</p>';
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
