<?php
// Xử lý yêu cầu xóa món ăn
if (isset($_POST['action']) && $_POST['action'] == 'delete_food' && isset($_POST['food_id'])) {
    $food_id = intval($_POST['food_id']); // Lấy ID món ăn cần xóa

    // Kết nối database
    $connect = mysqli_connect('localhost', 'root', '', 'pizza');
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_set_charset($connect, "utf8");

    // Xóa hình ảnh liên quan
    $delete_image = "DELETE FROM food_image WHERE food_id = $food_id";
    mysqli_query($connect, $delete_image);

    // Xóa món ăn khỏi bảng food
    $delete_food = "DELETE FROM food WHERE food_id = $food_id";
    if (mysqli_query($connect, $delete_food)) {
        echo "Món ăn đã được xóa thành công!";
    } else {
        echo "Có lỗi xảy ra khi xóa món ăn!";
    }

    mysqli_close($connect);
}
?>
