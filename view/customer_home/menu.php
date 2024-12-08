




<body>
<?php
session_start();
$connect = mysqli_connect('localhost', 'root', '', 'pizza');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Phân trang
$products_per_page = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $products_per_page;


// Lấy giá tối đa từ tham số GET
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : null;
// Lấy danh sách món ăn và hình ảnh
$sql = "
    SELECT 
        f.food_id, 
        f.food_name, 
        f.food_price, 
        f.food_description, 
        fi.food_image_url 
    FROM 
        food f 
    LEFT JOIN 
        food_image fi 
    ON 
        f.food_id = fi.food_id 
   WHERE 
        1 = 1";

if ($max_price !== null) {
    $sql .= " AND f.food_price <= $max_price";
}

$sql .= "
    GROUP BY 
        f.food_id 
    LIMIT $products_per_page 
    OFFSET $offset";
$result = mysqli_query($connect, $sql);

$total_query = "SELECT COUNT(*) as total FROM food WHERE 1 = 1";
if ($max_price !== null) {
    $total_query .= " AND food_price <= $max_price";
}
$total_result = mysqli_query($connect, $total_query);
$total_products = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_products / $products_per_page);




?>





<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/Pizza_DB_favicon.ico">
    <link rel="stylesheet" href="menu.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Pizza DB</title>
</head>

    <!-- Navigator Bar -->
    <nav class="navbar position-relative navbar-expand-sm navbar-light px-4" style="background-color: #e8e3c5;">
  <div class="container-fluid gap-5">
    <!-- Title and Logo -->
    <a class="navbar-brand" href="">
      <img src="../image/logo.jpg" alt="logo" style="width: 3rem;">
      <span class="ms-4" style="font-size: 1.5rem;">Pizza DB</span>
    </a>
    <!-- Navigator Link -->
    <div class="navmenu justify-content-center navbar-collapse gap-5">
      <ul class="navbar-nav gap-5">
        <li class="nav-item">
          <a class="nav-link text-uppercase text-black fw-bold" href="home.php">Trang chủ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-uppercase text-black fw-bold" href="menu.php">Menu</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-uppercase text-black fw-bold" href="order.php">Đơn hàng</a>
        </li>
      </ul>
    </div>
    <!-- Logout Bar -->
    <div class="navmenu justify-content-end navbar-collapse col-lg-1 position-relative">
      <button class="btn btn-outline-success text-white btn-danger my-2 my-sm-0 ms-2">
        <a href="../index.php" class="login">Đăng xuất</a>
      </button>
    </div>
  </div>
</nav>

    




    <!-- Body -->
     <div><?php echo $$_SESSION["account_id"] ?></div>
    <div class="container mt-4">
    <form id="price-filter-form" style="width: 250px;" method="GET" action="">
        <div class="input-group">
            <input type="number" name="max_price" class="form-control" placeholder="Nhập giá món tối đa"  value="<?= isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : '' ?>" min="0">
            <button class="btn btn-primary" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </form>
</div>



</div>
        <div class="container mt-5">
            <div class="row">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <!-- Hình ảnh -->
                            <img 
                                src="<?= htmlspecialchars($row['food_image_url'] ?: '../image/logo.jpg') ?>" 
                                class="card-img-top" 
                                alt="<?= htmlspecialchars($row['food_name']) ?>">
                            
                            <div class="card-body">
                                <!-- Tên món ăn -->
                                <form method="POST" action="" onsubmit="showNotification(event, this)">           
                                    <h5 class="card-title" value="<?= htmlspecialchars($row['food_name']) ?>"><?= htmlspecialchars($row['food_name']) ?></h5>
                                    <!-- Mô tả -->
                                    <p class="card-text"><?= htmlspecialchars($row['food_description']) ?></p>
                                    <!-- Giá -->
                                    <p class="card-text fw-bold text-danger price">
                                        <?= number_format($row['food_price'], 0, ',', '.') ?> VND
                                    </p>
                                    <input type="hidden" class="food_id" name="food_id" value="<?= $row['food_id'] ?>">
                                    <button type="submit" name="add_to_cart" class="btn btn-primary">Thêm món</button>
                                    <span class="notification-message" style="display: none; margin-left: 10px; color: green;">Đã thêm món!</span>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
   

    

<nav>
    <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page - 1 ?>">Trước</a>
            </li>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
        <?php if ($page < $total_pages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?= $page + 1 ?>">Sau</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

            

    
    <!-- Footer section -->
    <footer class="mt-4 bg-dark text-black p-4">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex flex-column">
                    <span class="text-uppercase text-warning fw-bold mb-2">Menu</span>
                    <a href="" class="nav-link text-white">Combo</a>
                    <a href="" class="nav-link text-white">Pizza</a>
                    <a href="" class="nav-link text-white">Khai vị</a>
                    <a href="" class="nav-link text-white">Nước uống</a>
                </div>
                <div class="d-flex flex-column">
                    <span class="text-uppercase text-warning fw-bold mb-2">Về chúng tôi</span>
                    <a href="" class="nav-link text-white">Giới thiệu</a>
                    <a href="" class="nav-link text-white">Tầm nhìn</a>
                    <a href="" class="nav-link text-white">Giá trị cốt lõi</a>
                    <a href="" class="nav-link text-white">Vệ sinh an toàn thực phẩm</a>
                </div>
                <div class="d-flex flex-column">
                    <span class="text-uppercase text-warning fw-bold mb-2">Tìm cửa hàng</span>
                    <a href="" class="nav-link text-white">Miền Bắc</a>
                    <a href="" class="nav-link text-white">Miền Trung</a>
                    <a href="" class="nav-link text-white">Miền Nam</a>
                </div>
                <div class="d-flex flex-column">
                    <span class="text-uppercase text-warning fw-bold mb-2">Liên hệ</span>
                    <button onclick="location.href='#'" type="button" class="btn btn-round btn-fb mr-md-3 mb-2 mb-md-0">
                    <img src="../image/facebook.png" alt="facebook" style="width: 1.5rem;">
                    </button>
                    <button onclick="location.href='#'" type="button" class="btn btn-round btn-fb mr-md-3 mb-2 mb-md-0">
                        <img src="../image/email.png" alt="facebook" style="width: 1.5rem;">
                    </button>
                    <button onclick="location.href='#'" type="button" class="btn btn-round btn-fb mr-md-3 mb-2 mb-md-0">
                        <img src="../image/youtube.png" alt="facebook" style="width: 1.5rem;">
                    </button>
                </div>
            </div>
        </div>
    </footer>
    <script>
    function showNotification(event, formElement) {
        event.preventDefault(); // Ngăn form gửi dữ liệu nếu bạn chỉ muốn hiển thị thông báo
        const notification = formElement.querySelector('.notification-message');
        const id = formElement.querySelector('.food_id');
        const qty = 1;
        const choose_id = id.value;
        let date = new Date();
        const date_order = date.toISOString().slice(0, 19).replace("T", " ");;
        const title = formElement.querySelector('.card-title').innerText;
        const price = formElement.querySelector('.price').innerText;
        console.log(title);
        console.log(parseInt(price.replace(/[^0-9]/g, ""),10));
        console.log(date_order);


        if (notification) {
            notification.style.display = 'inline'; // Hiển thị thông báo
            setTimeout(() => {
                notification.style.display = 'none'; // Ẩn sau 2 giây
            }, 2000);
        }

        fetch('./save_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                choose_id: choose_id,
                title: title,
                price: price,
                qty: qty,
                date_order: date_order
            })
        });
        console.log("ok")
        // Nếu bạn muốn tiếp tục gửi dữ liệu sau khi hiển thị thông báo, hãy bỏ comment dòng dưới:
        // formElement.submit();
    }
</script>

</body>
</html>