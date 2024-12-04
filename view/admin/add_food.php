<?php
// Kết nối cơ sở dữ liệu
$connect = mysqli_connect('localhost', 'root', '', 'pizza');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($connect, "utf8");

// Kiểm tra nếu form được gửi
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_food'])) {
    $food_name = mysqli_real_escape_string($connect, $_POST['food_name']);
    $food_price = $_POST['food_price'];
    $food_description = mysqli_real_escape_string($connect, $_POST['food_description']);
    $food_image = mysqli_real_escape_string($connect, $_POST['food_image']);

    // Thêm món ăn vào bảng food
    $sql = "INSERT INTO food (food_name, food_price, food_description) VALUES ('$food_name', $food_price, '$food_description')";
    if (mysqli_query($connect, $sql)) {
        $food_id = mysqli_insert_id($connect); // Lấy ID của món vừa thêm

        // Thêm URL hình ảnh vào bảng food_image
        $image_sql = "INSERT INTO food_image (food_image_url, food_id) VALUES ('$food_image', $food_id)";
        if (mysqli_query($connect, $image_sql)) {
            echo "Thêm món mới thành công!"; // Trả về thông báo thành công
        } else {
            echo "Lỗi khi thêm hình ảnh!"; // Trả về thông báo lỗi hình ảnh
        }
    } else {
        echo "Lỗi khi thêm món mới!"; // Trả về thông báo lỗi khi thêm món
    }
}
?>
