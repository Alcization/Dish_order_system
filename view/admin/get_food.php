<?php
// Kết nối cơ sở dữ liệu
$connect = mysqli_connect('localhost', 'root', '', 'pizza');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($connect, "utf8");

if (isset($_GET['food_id'])) {
    $food_id = $_GET['food_id'];
    $query = "SELECT f.food_name, f.food_price, f.food_description, fi.food_image_url
              FROM food f
              LEFT JOIN food_image fi ON f.food_id = fi.food_id
              WHERE f.food_id = $food_id";
    $result = mysqli_query($connect, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Món ăn không tồn tại."]);
    }
} else {
    echo json_encode(["error" => "Thiếu ID món ăn."]);
}

mysqli_close($connect);
?>
