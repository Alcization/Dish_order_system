<?php
// Kết nối cơ sở dữ liệu
$connect = mysqli_connect('localhost', 'root', '', 'pizza');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($connect, "utf8");

if (isset($_POST['food_id'])) {
    $food_id = $_POST['food_id'];
    $food_name = mysqli_real_escape_string($connect, $_POST['food_name']);
    $food_price = intval($_POST['food_price']);
    $food_description = mysqli_real_escape_string($connect, $_POST['food_description']);
    $food_image = mysqli_real_escape_string($connect, $_POST['food_image']);

    $query = "UPDATE food SET food_name='$food_name', food_price=$food_price, food_description='$food_description' WHERE food_id=$food_id";
    
    if (mysqli_query($connect, $query)) {
        if (!empty($food_image)) {
            $update_image_query = "UPDATE food_image SET food_image_url='$food_image' WHERE food_id=$food_id";
            mysqli_query($connect, $update_image_query);
        }
        echo "Cập nhật món ăn thành công!";
        
    } else {
        echo "Lỗi cập nhật món ăn: " . mysqli_error($connect);
    }
} else {
    echo "Thiếu thông tin món ăn.";
}

mysqli_close($connect);
?> 