<?php
    session_start();
    $connect = mysqli_connect('localhost', 'root', '', 'pizza');
    if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
    }

    $food_id = $_POST['food_id'];
    $food_price = $_POST['food_price'];
    $customer_id = $_SESSION['account_id'];

    // Check if the order is already created
    $sql = "SELECT order_id FROM `order` WHERE customer_id = $customer_id AND order_status = 0";
    $result = mysqli_query($connect, $sql);
    if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $order_id = $row['order_id'];
    } else {
        // Create a new payment_order
        $sql = "INSERT INTO payment_order (payment_status) VALUES (0)";
        if (mysqli_query($connect, $sql)) {
            $bill_id = mysqli_insert_id($connect);
            $sql = "INSERT INTO `order` (customer_id, order_status, bill_id) VALUES ($customer_id, 0, $bill_id)";
            if (mysqli_query($connect, $sql)) {
                $order_id = mysqli_insert_id($connect);
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($connect);
                exit();
            }
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($connect);
            exit();
        }
    }
    } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($connect);
    exit();
    }

    // Check if the food is already added to the table creat_order
    $sql = "SELECT * FROM creat_order WHERE order_id = $order_id AND food_id = $food_id";
    $result = mysqli_query($connect, $sql);
    if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $quantity = $row['quantity'] + 1;
        $temp_price = $row['temp_price'] + $food_price;
        $sql = "UPDATE creat_order SET quantity = $quantity, temp_price = $temp_price WHERE order_id = $order_id AND food_id = $food_id";
        if (mysqli_query($connect, $sql)) {
            echo "  <script>
                        alert('Món ăn đã được cập nhật thành công!');
                        window.location.href = 'menu.php';
                    </script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($connect);
            exit();
        }
    } else {
        $sql = "INSERT INTO creat_order (order_id, food_id, quantity, temp_price) VALUES ($order_id, $food_id, 1, $food_price)";
        if (mysqli_query($connect, $sql)) {
            echo "  <script>
                        alert('Món ăn đã được thêm thành công!');
                        window.location.href = 'menu.php';
                    </script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($connect);
            exit();
        }
    }
    } else {
    echo "Error: " . $sql . "<br>" . mysqli_error($connect);
    exit();
    }

    