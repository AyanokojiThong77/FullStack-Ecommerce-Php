<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit();
}

// Retrieve the category to edit
if (isset($_GET['category'])) {
    $category_name = $_GET['category'];

    // Check if the category exists
    $check_category = $conn->prepare("SELECT * FROM `products` WHERE category = ?");
    $check_category->execute([$category_name]);
    if ($check_category->rowCount() == 0) {
        header('location:manage_categories.php');
        exit();
    }
}

// Update category
if (isset($_POST['update_category'])) {
    $new_category = $_POST['category'];
    $new_category = filter_var($new_category, FILTER_SANITIZE_STRING);

    // Update category in the database
    $update_category = $conn->prepare("UPDATE `products` SET category = ? WHERE category = ?");
    $update_category->execute([$new_category, $category_name]);
    $message[] = 'Danh mục đã được cập nhật!';
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
    <title>Sửa danh mục sản phẩm</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="container my-5">
    <h1 class="heading mb-4">Sửa danh mục sản phẩm</h1>

    <form action="" method="post">
        <div class="form-group">
            <label for="category">Tên danh mục:</label>
            <input type="text" name="category" id="category" value="<?= htmlspecialchars($category_name); ?>" class="form-control" required>
        </div>
        <input type="submit" value="Cập nhật danh mục" name="update_category" class="btn btn-primary">
    </form>
</section>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
