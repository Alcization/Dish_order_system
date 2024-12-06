<?php
session_start();
$connect = mysqli_connect('localhost', 'root', '', 'pizza');

// Lấy thông tin order_id từ URL
$order_id = $_GET['order_id'];

// Lấy danh sách món ăn trong đơn hàng
$query = "SELECT f.food_id, f.food_name, f.food_description, f.food_price, fi.food_image_url, co.quantity 
          FROM `creat_order` co
          JOIN `food` f ON co.food_id = f.food_id
          LEFT JOIN `food_image` fi ON f.food_id = fi.food_id
          WHERE co.order_id = '$order_id'";
$result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Đánh giá món ăn</title>
</head>

<body class="bg-light">
    <!-- Navigator Bar -->
    <nav class="navbar position-relative navbar-expand-sm navbar-light px-4" style="background-color: #e8e3c5;">
        <div class="container-fluid gap-5">
            <!-- Title and Logo -->
            <a class="navbar-brand" href="home.php">
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
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="rating.php">Đánh giá</a>
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

    <div class="container mt-4">
        <h2 class="text-center">Đánh giá món ăn cho đơn hàng #<?= $order_id ?></h2>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <form action="submit-rating.php" method="POST">
                <input type="hidden" name="order_id" value="<?= $order_id ?>">
                <div class="row">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <img src="<?= $row['food_image_url'] ?>" class="card-img-top" alt="<?= $row['food_name'] ?>"
                                    style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $row['food_name'] ?> (x<?= $row['quantity'] ?>)</h5>
                                    <p class="card-text"><?= $row['food_description'] ?></p>
                                    <p class="card-text text-danger fw-bold">
                                        <?= number_format($row['food_price'], 0, ',', '.') ?> VNĐ
                                    </p>
                                    <div class="mb-3">
                                        <label for="rating_<?= $row['food_id'] ?>" class="form-label">Đánh giá (1-5):</label>
                                        <select name="rating[<?= $row['food_id'] ?>]" id="rating_<?= $row['food_id'] ?>"
                                            class="form-select" required>
                                            <option value="" disabled selected>Chọn số sao</option>
                                            <option value="1">1 sao</option>
                                            <option value="2">2 sao</option>
                                            <option value="3">3 sao</option>
                                            <option value="4">4 sao</option>
                                            <option value="5">5 sao</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="review_<?= $row['food_id'] ?>" class="form-label">Nhận xét:</label>
                                        <textarea name="review[<?= $row['food_id'] ?>]" id="review_<?= $row['food_id'] ?>"
                                            class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <button type="submit" class="btn btn-success w-100">Gửi đánh giá</button>
            </form>
        <?php else: ?>
            <p class="text-center text-danger">Không tìm thấy món ăn trong đơn hàng này.</p>
        <?php endif; ?>
    </div>

    <!-- Footer section -->
    <footer class="mt-4 text-black p-4" style="background-color: #e8e3c5;">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex flex-column">
                    <span class="text-uppercase text-danger fw-bold mb-2">Menu</span>
                    <a href="" class="nav-link text-black">Combo</a>
                    <a href="" class="nav-link text-black">Pizza</a>
                    <a href="" class="nav-link text-black">Khai vị</a>
                    <a href="" class="nav-link text-black">Nước uống</a>
                </div>
                <div class="d-flex flex-column">
                    <span class="text-uppercase text-danger fw-bold mb-2">Về chúng tôi</span>
                    <a href="" class="nav-link text-black">Giới thiệu</a>
                    <a href="" class="nav-link text-black">Tầm nhìn</a>
                    <a href="" class="nav-link text-black">Giá trị cốt lõi</a>
                    <a href="" class="nav-link text-black">Vệ sinh an toàn thực phẩm</a>
                </div>
                <div class="d-flex flex-column">
                    <span class="text-uppercase text-danger fw-bold mb-2">Tìm cửa hàng</span>
                    <a href="" class="nav-link text-black">Miền Bắc</a>
                    <a href="" class="nav-link text-black">Miền Trung</a>
                    <a href="" class="nav-link text-black">Miền Nam</a>
                </div>
                <div class="d-flex flex-column">
                    <span class="text-uppercase text-danger fw-bold mb-2">Liên hệ</span>
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
</body>

</html>