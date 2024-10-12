<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

// Add category
if (isset($_POST['add_category'])) {
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $insert_category = $conn->prepare("INSERT INTO `products` (category) VALUES (?)");
    $insert_category->execute([$category]);
    $message[] = 'Danh mục đã được thêm!';
}

// Delete category
if (isset($_GET['delete'])) {
    $delete_category = $_GET['delete'];
    // Xóa các sản phẩm thuộc loại này
    $delete_products = $conn->prepare("DELETE FROM `products` WHERE category = ?");
    $delete_products->execute([$delete_category]);
    header('location:manage_categories.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý loại sản phẩm</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        .category-box {
            font-size: 1.2rem; /* Adjust this for larger text */
        }
    </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="container my-5">

    <h1 class="heading mb-4">Quản lý loại sản phẩm</h1>

    <form action="" method="post" class="mb-4">
        <div class="form-group">
            <input type="text" name="category" placeholder="Nhập loại sản phẩm" class="form-control" required>
        </div>
        <input type="submit" value="Thêm danh mục" name="add_category" class="btn btn-primary">
    </form>

    <div class="row">
        <?php
        // Truy vấn tất cả các loại sản phẩm có trong bảng products
        $select_categories = $conn->prepare("SELECT DISTINCT category FROM `products`");
        $select_categories->execute();
        if ($select_categories->rowCount() > 0) {
            while ($fetch_category = $select_categories->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <div class="col-md-4 mb-3 category-box">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">Tên danh mục: <strong><?= $fetch_category['category']; ?></strong></p>
                            <a href="manage_categories.php?delete=<?= $fetch_category['category']; ?>" onclick="return confirm('Bạn có chắc muốn xóa danh mục này? Tất cả các sản phẩm thuộc danh mục cũng sẽ bị xóa!')" class="btn btn-danger">Xóa</a>
                            <a href="edit_category.php?category=<?= $fetch_category['category']; ?>" class="btn btn-warning">Sửa</a>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo '<p class="empty">Không có danh mục nào!</p>';
        }
        ?>
    </div>

</section>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../js/admin_script.js"></script>

</body>
</html>
