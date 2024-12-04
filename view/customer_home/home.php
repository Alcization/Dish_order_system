<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/Pizza_DB_favicon.ico">
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Pizza DB</title>
</head>

<body>
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



    <!-- Body -->
    <section style="min-height: 40.6rem; max-width: 120rem">
        <div class="flex-container">
            <div class="flex-item-left">
                <div class="promotion_text">
                    Ưu đãi hấp dẫn chỉ có tại Pizza DB. Giảm <span style="color: #ebd244;">5%</span> cho đơn hàng đầu
                    tiên
                </div>
                <div class="promotion_button_container">
                    <button class="promotion_button">
                        <a href="menu.php" class="login">Đặt hàng ngay</a>
                    </button>
                </div>

            </div>
            <div class="flex-item-right">
                <img src="../image/pizza_home.png" alt="">
            </div>

        </div>
    </section>

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