<?php
// Kết nối cơ sở dữ liệu
$connect = mysqli_connect('localhost', 'root', '', 'pizza');
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($connect, "utf8");

if (isset($_GET['customer_id'])) {
    $customer_id = intval($_GET['customer_id']);
    $query = "SELECT customer_first_name, customer_last_name, phone_number, email, points 
              FROM customer 
              WHERE customer_id = $customer_id";
    $result = mysqli_query($connect, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Khách hàng không tồn tại."]);
    }
} else {
    echo json_encode(["error" => "Thiếu ID khách hàng."]);
}

mysqli_close($connect);
?>