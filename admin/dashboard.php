<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Trang Tổng Quan</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">

   <h1 class="heading">Trang Tổng Quan</h1>

   <div class="box-container">

      <div class="box">
         <h3>Chào mừng!</h3>
         <p><?= $fetch_profile['name']; ?></p>
         <a href="update_profile.php" class="btn">Cập nhật hồ sơ</a>
      </div>

      <div class="box">
         <?php
            $total_pendings = 0;
            $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_pendings->execute(['pending']);
            if($select_pendings->rowCount() > 0){
               while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
                  $total_pendings += $fetch_pendings['total_price'];
               }
            }
         ?>
         <h3><span>₫</span><?= number_format($total_pendings, 0, ',', '.'); ?><span>/-</span></h3>
         <p>Tổng tiền đang chờ xử lý</p>
         <a href="placed_orders.php" class="btn">Xem đơn hàng</a>
      </div>

      <div class="box">
         <?php
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
            $select_completes->execute(['completed']);
            if($select_completes->rowCount() > 0){
               while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                  $total_completes += $fetch_completes['total_price'];
               }
            }
         ?>
         <h3><span>₫</span><?= number_format($total_completes, 0, ',', '.'); ?><span>/-</span></h3>
         <p>Đơn hàng đã hoàn thành</p>
         <a href="placed_orders.php" class="btn">Xem đơn hàng</a>
      </div>

      <div class="box">
         <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $number_of_orders = $select_orders->rowCount();
         ?>
         <h3><?= $number_of_orders; ?></h3>
         <p>Đơn hàng đã đặt</p>
         <a href="placed_orders.php" class="btn">Xem đơn hàng</a>
      </div>

      <div class="box">
         <?php
            $select_products = $conn->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $number_of_products = $select_products->rowCount();
         ?>
         <h3><?= $number_of_products; ?></h3>
         <p>Sản phẩm đã thêm</p>
         <a href="products.php" class="btn">Xem sản phẩm</a>
      </div>

      <div class="box">
         <?php
            $select_users = $conn->prepare("SELECT * FROM `users`");
            $select_users->execute();
            $number_of_users = $select_users->rowCount();
         ?>
         <h3><?= $number_of_users; ?></h3>
         <p>Người dùng bình thường</p>
         <a href="users_accounts.php" class="btn">Xem người dùng</a>
      </div>

      <div class="box">
         <?php
            $select_admins = $conn->prepare("SELECT * FROM `admins`");
            $select_admins->execute();
            $number_of_admins = $select_admins->rowCount();
         ?>
         <h3><?= $number_of_admins; ?></h3>
         <p>Người dùng quản trị</p>
         <a href="admin_accounts.php" class="btn">Xem quản trị viên</a>
      </div>

      <div class="box">
         <?php
            $select_messages = $conn->prepare("SELECT * FROM `messages`");
            $select_messages->execute();
            $number_of_messages = $select_messages->rowCount();
         ?>
         <h3><?= $number_of_messages; ?></h3>
         <p>Thông điệp mới</p>
         <a href="messages.php" class="btn">Xem thông điệp</a>
      </div>

      <div style="
    display: flex; 
    flex-wrap: no-warp; 
    justify-content: space-between; /* Adjusts space between the boxes */
    margin: 15px; /* Adds margin around the flex container */
">
    <div class="box" style="
        width: 500px; /* Fixed width for the revenue box */
        height: 400px; 
        background-color: #f9f9f9; 
        border-radius: 10px; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        padding: 20px; 
        text-align: center; 
        margin: 15px; /* Adds margin between the boxes */
    ">
        <h3>Doanh thu</h3>
        <label for="timeFilter">Lọc theo:</label>
        <select id="timeFilter">
            <option value="daily">Ngày</option>
            <option value="weekly">Tuần</option>
            <option value="monthly">Tháng</option>
            <option value="quarterly">Quý</option>
            <option value="yearly">Năm</option>
        </select>
        <canvas id="revenueChart" width="800" height="400"></canvas>
    </div>

    <div class="box" style="
        flex: 1 1 200px; /* Flexible box that takes up remaining space */
        max-width: 200px; 
        height: 400px; 
        background-color: #f9f9f9; 
        border-radius: 10px; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        padding: 50px; 
        text-align: center; 
        margin: 15px; /* Adds margin between the boxes */
    ">
        <?php
            // Truy vấn tất cả các đơn hàng đã hoàn thành từ bảng orders
            $select_orders = $conn->prepare("SELECT total_products FROM `orders` WHERE payment_status = 'completed'");
            $select_orders->execute();

            // Tạo một mảng để lưu số lượng bán của từng sản phẩm
            $product_sales = [];

            // Duyệt qua từng đơn hàng để phân tích chuỗi sản phẩm
            while ($fetch_order = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                // Lấy danh sách sản phẩm từ cột `total_products`
                $products = explode(" - ", $fetch_order['total_products']);
                
                foreach ($products as $product) {
                    if (!empty($product)) {
                        // Tách chuỗi để lấy tên sản phẩm và số lượng
                        preg_match('/(.+)\((\d+) x (\d+)\)/', $product, $matches);
                        
                        if (!empty($matches)) {
                            $product_name = trim($matches[1]); // Lấy tên sản phẩm
                            $quantity_sold = (int) $matches[3]; // Lấy số lượng đã bán

                            // Cộng dồn số lượng sản phẩm vào mảng
                            if (isset($product_sales[$product_name])) {
                                $product_sales[$product_name] += $quantity_sold;
                            } else {
                                $product_sales[$product_name] = $quantity_sold;
                            }
                        }
                    }
                }
            }

            // Tìm sản phẩm có số lượng bán cao nhất
            $best_selling_product = array_keys($product_sales, max($product_sales));
        ?>
        <h3><?= !empty($best_selling_product) ? $best_selling_product[0] : 'Chưa có'; ?></h3>
        <p>Sản phẩm bán chạy nhất</p>
    </div>

    <div class="box" style="
        flex: 1 1 200px; /* Flexible box that takes up remaining space */
        max-width: 200px; 
        height: 400px; 
        background-color: #f9f9f9; 
        border-radius: 10px; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        padding: 50px; 
        text-align: center; 
        margin: 15px; /* Adds margin between the boxes */
    ">
        <?php
            $select_top_user = $conn->prepare("
                SELECT users.name, SUM(orders.total_price) AS total_spent 
                FROM `orders` 
                INNER JOIN `users` ON orders.user_id = users.id 
                WHERE orders.payment_status = 'completed' 
                GROUP BY orders.user_id 
                ORDER BY total_spent DESC 
                LIMIT 1
            ");
            $select_top_user->execute();
            $top_user = $select_top_user->fetch(PDO::FETCH_ASSOC);
        ?>
        <h3><?= $top_user ? $top_user['name'] : 'Chưa có'; ?></h3>
        <p>Người dùng mua hàng nhiều nhất</p>
    </div>
    <div class="box" style="
    width: 500px; /* Fixed width for the product revenue box */
    height: 500px; 
    background-color: #f9f9f9; 
    border-radius: 10px; 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
    padding: 20px; 
    text-align: center; 
    margin: 15px; /* Adds margin between the boxes */
">
    <h3>Doanh thu theo sản phẩm</h3>
    <canvas id="productRevenueChart" width="400" height="400"></canvas>
</div>
</div>







<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    let revenueChart;

    function fetchRevenueData(filterType) {
        $.ajax({
            url: 'fetch_revenue.php', // Tạo file fetch_revenue.php
            method: 'POST',
            data: {
                filter: filterType
            },
            dataType: 'json',
            success: function(data) {
                if (revenueChart) {
                    revenueChart.destroy(); // Xóa biểu đồ cũ trước khi vẽ lại
                }
                revenueChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Doanh thu',
                            data: data.revenue,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            },
            error: function() {
                alert('Lỗi khi lấy dữ liệu!');
            }
        });
    }

    $("#timeFilter").on('change', function() {
        const filterType = $(this).val();
        fetchRevenueData(filterType);
    });

    // Lấy doanh thu mặc định khi tải trang
    $(document).ready(function() {
        fetchRevenueData('monthly'); // Mặc định hiển thị doanh thu theo tháng
    });
</script>

<script>
    const productCtx = document.getElementById('productRevenueChart').getContext('2d');
let productRevenueChart;

function fetchProductRevenueData() {
    $.ajax({
        url: 'fetch_product_revenue.php', // Tạo file fetch_product_revenue.php
        method: 'POST',
        dataType: 'json',
        success: function(data) {
            if (productRevenueChart) {
                productRevenueChart.destroy(); // Xóa biểu đồ cũ trước khi vẽ lại
            }
            productRevenueChart = new Chart(productCtx, {
                type: 'pie', // Loại biểu đồ là hình tròn
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Doanh thu theo sản phẩm',
                        data: data.revenue,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': $' + tooltipItem.raw; // Hiển thị giá trị doanh thu trong tooltip
                                }
                            }
                        }
                    }
                }
            });
        },
        error: function() {
            alert('Lỗi khi lấy dữ liệu doanh thu theo sản phẩm!');
        }
    });
}

// Gọi hàm để lấy dữ liệu doanh thu theo sản phẩm khi tải trang
$(document).ready(function() {
    fetchProductRevenueData(); // Lấy doanh thu theo sản phẩm
});

</script>


</section>

<script src="../js/admin_script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

   
</body>
</html>
