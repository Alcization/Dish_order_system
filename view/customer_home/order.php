<?php
    session_start();

    if (!isset($_SESSION['account_id'])):
        header('Location: ../index.php');
    endif;
    // error_reporting(E_ERROR | E_PARSE);
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

  // Khởi tạo giá trị ban đầu
  $discount = 0;
  $total_to_pay = 0;
  $customer_id = $_SESSION["account_id"] ?? null;
  $delivery_fee = 20000; // Phí giao hàng cố định

  // Get order_id from customer_id
  $order_query = "SELECT order_id FROM `order` WHERE customer_id = ? AND order_status = 0";
  $order_stmt = mysqli_prepare($connect, $order_query);
  if ($order_stmt) {
      mysqli_stmt_bind_param($order_stmt, "i", $customer_id);
      mysqli_stmt_execute($order_stmt);
      $order_result = mysqli_stmt_get_result($order_stmt);
      $order_row = mysqli_fetch_assoc($order_result);
      $order_id = $order_row['order_id'] ?? null;
      mysqli_stmt_close($order_stmt);
  } else {
      echo "Error: " . mysqli_error($connect);
      exit();
  }
  
  // Check if order_id is valid
  if ($order_id === null) {
    echo "<script>
            alert('Không có đơn hàng nào trong giỏ hàng, bạn hãy thêm món hàng đầu tiên để truy cập đơn hàng');
            window.location.href = 'menu.php';
          </script>";
    exit();
  }

  // Get the items in the order
  $sql = "SELECT 
      c.food_id, 
      f.food_name, 
      f.food_price, 
      c.quantity, 
      c.temp_price
  FROM 
      creat_order c
  JOIN 
      food f
  ON 
      c.food_id = f.food_id
  JOIN 
      `order` o
  ON
      c.order_id = o.order_id
  WHERE 
      c.order_id = ? AND o.order_status = 0";
  $stmt = mysqli_prepare($connect, $sql);
  if ($stmt) {
      mysqli_stmt_bind_param($stmt, "i", $order_id);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      // Check if the result is valid
      if ($result) {
          // Process the result if needed
      } else {
          echo "Error: " . mysqli_error($connect);
      }
      mysqli_stmt_close($stmt);
  } else {
      echo "Error: " . mysqli_error($connect);
  }

  // Calculate the total temporary price
  $total_query = "SELECT SUM(temp_price) AS total_price FROM creat_order WHERE order_id = ?";
  $total_stmt = mysqli_prepare($connect, $total_query);
  if ($total_stmt) {
      mysqli_stmt_bind_param($total_stmt, "i", $order_id);
      mysqli_stmt_execute($total_stmt);
      $total_result = mysqli_stmt_get_result($total_stmt);

      // Check if the result is valid
      if ($total_result) {
          $total_row = mysqli_fetch_assoc($total_result);
          if ($total_row) {
              $total_price = $total_row['total_price'];
          } else {
              $total_price = 0; // Default value if no rows are returned
          }
      } else {
          echo "Error: " . mysqli_error($connect);
      }
      mysqli_stmt_close($total_stmt);
  } else {
      echo "Error: " . mysqli_error($connect);
  }



  // Xử lý mã giảm giá
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['coupon_code'])) {
    $coupon_code = $_POST['coupon_code'];

    $query = "
        SELECT cd.type_discount, cd.expired_date, cd.discount_id, d.status_use,
               dop.percent, don.discount_number
        FROM customer_discounts cd
        LEFT JOIN discount_on_percent dop ON cd.discount_id = dop.discount_id
        LEFT JOIN discount_on_number don ON cd.discount_id = don.discount_id
        JOIN discount d ON cd.discount_id = d.discount_id
        WHERE cd.customer_id = ? 
          AND d.discount_name = ?
          AND cd.expired_date > CURDATE() 
          AND d.status_use = 0
    ";

    $stmt = mysqli_prepare($connect, $query);
    mysqli_stmt_bind_param($stmt, "is", $customer_id, $coupon_code);
    mysqli_stmt_execute($stmt);
    $discount_result = mysqli_stmt_get_result($stmt);

    if ($discount_row = mysqli_fetch_assoc($discount_result)) {
      if ($discount_row['type_discount'] === 'percent') {
        $discount = ($total_price * $discount_row['percent']) / 100;
      } elseif ($discount_row['type_discount'] === 'number') {
        $discount = $discount_row['discount_number'];
      }
      $discount = min($discount, $total_price); // Đảm bảo không vượt tổng giá
      $total_to_pay = $total_price + $delivery_fee - $discount;
      $valid_discount = true;
    } else {
      $error_message = "Invalid or expired coupon code.";
      $total_to_pay = $total_price + $delivery_fee;
    }
  } else {
    $total_to_pay = $total_price + $delivery_fee;
  }

  // Xử lý nút FINISH
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finish_order'])) {
    // Kiểm tra và lọc dữ liệu đầu vào
    $receiver_name = trim($_POST['receiver_name'] ?? '');
    $phone_number = trim($_POST['receiver_phone'] ?? '');
    $address = trim($_POST['receiver_address'] ?? '');

    // Kết nối và chuẩn bị lệnh SQL
    $insert_order_query = "
          INSERT INTO `receiver` (receiver_name, receiver_phone, receiver_address, order_id)
          VALUES (?, ?, ?, ?)
      ";
    $stmt = mysqli_prepare($connect, $insert_order_query);
    // Update $total_to_pay into final_price and switch order_status to 1 in order table
    $update_order_query = "
          UPDATE `order` 
          SET final_price = ?, order_status = 3
          WHERE order_id = ?
      ";
    $update_stmt = mysqli_prepare($connect, $update_order_query);
    mysqli_stmt_bind_param($update_stmt, "ii", $total_to_pay, $order_id);
    mysqli_stmt_execute($update_stmt);
    echo "<script>
            alert('Đơn hàng đã được xử lý thành công!');
            window.location.href = 'menu.php';
          </script>";


  }


  // Handle delete food action
  if (isset($_POST['delete_food'])) {
    $food_id = intval($_POST['food_id']);
    $order_id = intval($_POST['order_id']);
    $delete_query = "DELETE FROM creat_order WHERE order_id = ? AND food_id = ?";
    $delete_stmt = mysqli_prepare($connect, $delete_query);
    if ($delete_stmt) {
        mysqli_stmt_bind_param($delete_stmt, "ii", $order_id, $food_id);
        if (mysqli_stmt_execute($delete_stmt)) {
            echo "<script>alert('Món ăn đã được xóa thành công!'); window.location.href = 'order.php';</script>";
        } else {
            echo "<script>alert('Lỗi xóa món ăn: " . mysqli_stmt_error($delete_stmt) . "');</script>";
        }
        mysqli_stmt_close($delete_stmt);
    } else {
        echo "<script>alert('Lỗi chuẩn bị truy vấn: " . mysqli_error($connect) . "');</script>";
    }
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


  <main>
    <!-- Phần Order Detail -->
    <section class="order-detail">
      <h2>Order Detail</h2>
      <table>
        <thead>
          <tr>
            <th>Dish Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Temporary Total</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
              <td><?php echo htmlspecialchars($row['food_name']); ?></td>
              <td><?php echo number_format($row['food_price']); ?> VND</td>
              <td><?php echo htmlspecialchars($row['quantity']); ?></td>
              <td><?php echo number_format($row['temp_price']); ?> VND</td>
              <td>
                <form method="POST" action="order.php" style="display:inline;">
                    <input type="hidden" name="food_id" value="<?php echo htmlspecialchars($row['food_id']); ?>">
                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>">
                    <button type="submit" name="delete_food" class="btn btn-danger btn-sm">Delete</button>
                </form>
              </td>
            </tr>
          <?php } ?>
        </tbody>


      </table>
    </section>

    <!-- Phần Shipping Detail + Payment-->
    <section class="shipping-detail">
      <h2>Shipping And Discount</h2>
      <!-- Form chứa toàn bộ thông tin -->
      <form method="POST" action="">
        <label for="receiver-name">Receiver Name:</label>
        <input type="text" id="receiver-name" name="receiver_name" placeholder="Enter name" required>

        <label for="phone-number">Phone Number:</label>
        <input type="text" id="phone-number" name="phone_number" placeholder="Enter phone" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" placeholder="Enter address" required>

        <label for="shipping-time">Coupon:</label>
        <input type="text" id="shipping-time" name="coupon_code" placeholder="Enter coupon code">

        <h2>Payment</h2>
        <div class="payment-detail">
          <p>Sub Total: <span><?php echo number_format($total_price); ?> VND</span></p>
          <p>Discount: <span><?php echo number_format($discount); ?> VND</span></p>
          <p>Delivery Fee: <span><?php echo number_format($delivery_fee); ?> VND</span></p>
          <p class="total">TOTAL TO PAY: <span><?php echo number_format($total_to_pay); ?> VND</span></p>
        </div>

        <!-- Nút FINISH nằm trong <form> -->
        <button class="finish" type="submit" name="finish_order">FINISH</button>

      </form>
    </section>


  </main>

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

<script>
  function showFinishMessage() {
    alert("Thank you for your order! Your order is being processed.");
    window.location.href = "home.php";
  }
</script>


</html>