<?php
session_start();
$connect = mysqli_connect('localhost', 'root', '', 'pizza');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
$customer_id = $_SESSION["account_id"] ?? null;

// Lấy thông tin từ form
$order_id = $_POST['order_id'];
$ratings = $_POST['rating'];
$reviews = $_POST['review'];

if (!empty($ratings)) {
    foreach ($ratings as $food_id => $rating) {
        $review_description = isset($reviews[$food_id]) ? mysqli_real_escape_string($connect, $reviews[$food_id]) : null;

        // Thêm vào bảng user_review
        $query = "INSERT INTO user_review (customer_id, food_id, rating, review_description)
                  VALUES ('$customer_id', '$food_id', '$rating', '$review_description')";

        if (!mysqli_query($connect, $query)) {
            echo "Lỗi: " . mysqli_error($connect);
            exit();
        }
    }

    // Hiển thị thông báo và chuyển hướng về home.php
    echo "<script>
            alert('Đánh giá của bạn đã được lưu thành công!');
            window.location.href = 'home.php';
          </script>";
} else {
    echo "<script>
            alert('Không có dữ liệu để lưu. Vui lòng thử lại!');
            window.location.href = 'rating.php';
          </script>";
}

mysqli_close($connect);
?>
