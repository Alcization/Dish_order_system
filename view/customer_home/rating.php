<?php
    session_start();

    if (!isset($_SESSION['account_id'])):
        header('Location: ../index.php');
    endif;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="../image/Pizza_DB_favicon.ico">
  <link rel="stylesheet" href="rating.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Pizza DB</title>
</head>

<body>

  <?php
  $connect = mysqli_connect('localhost', 'root', '', 'pizza');
  if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
  }
  $query = "
  SELECT o.order_id, o.order_date, o.final_price, o.address_delivery
  FROM `order` o
  WHERE o.order_status = 3 
    AND NOT EXISTS (
      SELECT 1 
      FROM `user_review` ur 
      WHERE ur.customer_id = o.customer_id 
        AND ur.food_id IN (
          SELECT co.food_id 
          FROM `creat_order` co 
          WHERE co.order_id = o.order_id
        )
    )
";
  $result = mysqli_query($connect, $query);
  if (!$result) {
    die("Query failed: " . mysqli_error($connect));
  }
  ?>

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
          <form action="../logout.php" method="post"> 
              <button type="submit" class="btn btn-outline-success text-white btn-danger my-2 my-sm-0 ms-2">
                  Đăng xuất
              </button>
          </form> 
      </div>
    </div>
  </nav>

  <div class="container mt-4" style="min-height: 27.2rem;">
    <h2 class="text-center">Đơn hàng chưa được đánh giá</h2>
    <table class="table table-bordered mt-4">
      <thead class="table-light">
        <tr>
          <th>ID Đơn Hàng</th>
          <th>Ngày Đặt</th>
          <th>Giá Trị Cuối</th>
          <th>Địa Chỉ Giao Hàng</th>
          <th>Hành Động</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['order_id'] . "</td>";
            echo "<td>" . $row['order_date'] . "</td>";
            echo "<td>" . $row['final_price'] . "</td>";
            echo "<td>" . $row['address_delivery'] . "</td>";
            echo "<td>
                  <a href='rating.php?order_id=" . $row['order_id'] . "' class='btn btn-success'>Đánh giá</a>
                </td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='5' class='text-center'>Không có đơn hàng cần đánh giá</td></tr>";
        }
        ?>
      </tbody>
    </table>
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