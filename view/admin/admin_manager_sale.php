<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: ../index.php');
    exit();
}

// Database connection
$connect = mysqli_connect('localhost', 'root', '', 'pizza');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($connect, "utf8");

// Fetch list of dishes for the dropdown
$dishes = [];
$dish_query = "SELECT food_id, food_name FROM food";
$dish_result = mysqli_query($connect, $dish_query);
while ($row = mysqli_fetch_assoc($dish_result)) {
    $dishes[] = $row;
}

// Initialize variables
$sales_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $food_id = $_POST['food_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Run the MySQL function SaleInTime
    $stmt = mysqli_prepare($connect, "SELECT SaleInTime(?, ?, ?) AS sales");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iss", $food_id, $start_date, $end_date);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($result) {
            $sales_result = mysqli_fetch_assoc($result)['sales'];
        } else {
            echo "Error: " . mysqli_error($connect);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($connect);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customer Points - Pizza DB</title>
    <link rel="icon" href="../image/Pizza_DB_favicon.ico">
    <link rel="stylesheet" href="managerSale.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
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
                        <a class="nav-link text-uppercase text-black fw-bold" href="admin_manager_user.php">Người dùng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="admin_ql.php">Món ăn</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="admin_manager_point.php">Điểm thưởng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="admin_manager_review.php">Bình luận</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="admin_manager_sale.php">Doanh số</a>
                    </li>
                </ul>
            </div>
            <!-- Logout Bar -->
            <div class="navmenu justify-content-end navbar-collapse col-lg-1 position-relative">
                <form action="../logout.php" method="post"> 
                    <button type="submit" class="btn btn-outline-success text-white btn-danger my-2 my-sm-0 ms-2">
                        Đăng xuất
                    </button>
                </form> 
            </div>
        </div>
    </nav>

    <!-- Body -->
    <section class="container my-5">
        <h2 class="mb-4">Quản lý doanh số</h2>
        <form method="POST" action="admin_manager_sale.php" class="mb-4">
            <div class="mb-3">
                <label for="food_id" class="form-label">Lựa chọn món ăn:</label>
                <select class="form-select" id="food_id" name="food_id" required>
                    <option value="">Lựa chọn...</option>
                    <?php foreach ($dishes as $dish): ?>
                        <option value="<?php echo htmlspecialchars($dish['food_id']); ?>">
                            <?php echo htmlspecialchars($dish['food_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="start_date" class="form-label">Ngày bắt đầu:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>
            <div class="mb-3">
                <label for="end_date" class="form-label">Ngày kết thúc:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
            </div>
            <button type="submit" class="btn btn-primary">Lấy doanh số</button>
        </form>

        <?php if ($sales_result !== null): ?>
            <h4 class="mb-3">Kết quả bán hàng:</h4>
            <p>Tổng doanh số bán hàng của sản phẩm: <?= htmlspecialchars(number_format($sales_result, 0, ',', '.')); ?> VNĐ</p>
        <?php endif; ?>
    </section>
</body>
</html>