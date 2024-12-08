<?php

    session_start();

    if (!isset($_SESSION['admin'])):
        header('Location: ../index.php');
    endif;

    // Database connection
    $connect = mysqli_connect('localhost', 'root', '', 'pizza');
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_set_charset($connect, "utf8");

    
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customer Points - Pizza DB</title>
    <link rel="stylesheet" href="managePoint.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
<tbody>
<?php $query = "SELECT f.food_id, f.food_name, f.food_price, f.food_description, fi.food_image_url
          FROM food f
          LEFT JOIN food_image fi ON f.food_id = fi.food_id";
$result = mysqli_query($connect, $query);

if (!$result) {
    die("Truy vấn thất bại: " . mysqli_error($connect));
}



if (isset($_POST['add_food'])) {
    $food_name = mysqli_real_escape_string($connect, $_POST['food_name']);
    $food_price = intval($_POST['food_price']);
    $food_description = mysqli_real_escape_string($connect, $_POST['food_description']);
    $food_image = "";

    // Xử lý file ảnh nếu có
    if (isset($_FILES['food_image']) && $_FILES['food_image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["food_image"]["name"]);
        if (move_uploaded_file($_FILES["food_image"]["tmp_name"], $target_file)) {
            $food_image = $target_file;
        } else {
            echo "<script>alert('Lỗi tải lên hình ảnh.');</script>";
        }
    }

    // Thêm vào bảng food
    $insert_food = "INSERT INTO food (food_name, food_price, food_description) VALUES ('$food_name', $food_price, '$food_description')";
    if (mysqli_query($connect, $insert_food)) {
        $food_id = mysqli_insert_id($connect); // Lấy ID món vừa thêm

        // Nếu có ảnh, thêm vào bảng food_image
        if (!empty($food_image)) {
            $insert_image = "INSERT INTO food_image (food_image_url, food_id) VALUES ('$food_image', $food_id)";
            mysqli_query($connect, $insert_image);
        }

        echo "<script>alert('Thêm món thành công!'); window.location.href='your_page.php';</script>";
    } else {
        echo "<script>alert('Lỗi thêm món: " . mysqli_error($connect) . "');</script>";
    }
}



?>
    <!-- Navigator Bar -->
    <nav class="navbar position-relative navbar-expand-sm navbar-light px-4" style="background-color: #e8e3c5;">
        <div class="container-fluid gap-5">
            <!-- Title and Logo -->
            <a class="navbar-brand" href="">
                <img src="../image/logo.jpg" alt="logo" style="width: 3rem;">
                <span class="ms-4" style="font-size: 1.5rem;">Pizza DB</span>
            </a>
            <!-- Navigator Link -->
            <div class="navmenu justify-content-center navbar-collapse gap-5">
                <ul class="navbar-nav gap-5">
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="admin_manager_user.php">Người dùng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="admin_qlma.php">Món ăn</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="admin_manager_point.php">Điểm thưởng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="admin_manager_review.php">Bình luận</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="">Chi phí</a>
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
    <tbody>
    <tbody>
   
</tbody>

    <!-- Body -->
    <section class="container my-5">
        <?php
            if(isset($_COOKIE['success'])){
                echo "<div class='alert alert-success'>".htmlspecialchars($_COOKIE['success'])."</div>";
                setcookie("success", "", time()-3600, "/");
            }
            if(isset($_COOKIE['error'])){
                echo "<div class='alert alert-danger'>".htmlspecialchars($_COOKIE['error'])."</div>";
                setcookie("error", "", time()-3600, "/");
            }
        ?>
        <h2 class="mb-4">Quản lý món ăn</h2>
        <div class="mb-3 d-flex justify-content-between align-items-center">
                <!-- Ô tìm kiếm -->
            <div class="me-3">
              <label for="searchAction" class="form-label">Tìm kiếm món:</label>
                <input type="text" id="searchAction" class="form-control w-auto" placeholder="Nhập id món ăn..." style="width: 250px;">
                 </div>
             <!-- Nút Thêm Món -->
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addFoodModal">
                    Thêm món mới
                </button>
                <!-- Modal Thêm Món -->
                <div class="modal fade" id="addFoodModal" tabindex="-1" aria-labelledby="addFoodModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <form id="addFoodForm" method="POST">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="food_name" class="form-label">Tên món</label>
                                    <input type="text" class="form-control" id="food_name" name="food_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="food_price" class="form-label">Giá món</label>
                                    <input type="number" class="form-control" id="food_price" name="food_price" required>
                                </div>
                                <div class="mb-3">
                                    <label for="food_description" class="form-label">Mô tả món</label>
                                    <textarea class="form-control" id="food_description" name="food_description" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="food_image" class="form-label">Hình ảnh (URL)</label>
                                    <input type="text" class="form-control" id="food_image" name="food_image" placeholder="Nhập URL hình ảnh" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Xong</button>
                            </div>
                        </form>


                        </div>
                    </div>
                </div>

        </div>


        <div class="container my-5">
        <h2 class="mb-4">Danh sách món ăn</h2>
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID món ăn</th>
                    <th>Tên món</th>
                    <th>Mô tả</th>
                    <th>Giá</th>
                    <th>Hình ảnh</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Truy vấn dữ liệu
                $query = "SELECT f.food_id, f.food_name, f.food_price, f.food_description, fi.food_image_url
                          FROM food f
                          LEFT JOIN food_image fi ON f.food_id = fi.food_id";
                $result = mysqli_query($connect, $query);

                if (!$result) {
                    die("Truy vấn thất bại: " . mysqli_error($connect));
                }

                // Hiển thị dữ liệu
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['food_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['food_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['food_description']) . "</td>";
                    echo "<td>" . htmlspecialchars(number_format($row['food_price'])) . " VND</td>";
                    echo "<td>";
                    if (!empty($row['food_image_url'])) {
                        echo "<img src='" . htmlspecialchars($row['food_image_url']) . "' alt='Hình ảnh món ăn' style='width: 100px; height: auto;'>";
                    } else {
                        echo "Không có ảnh";
                    }
                    echo "</td>";
                    echo "<td>";
                    echo "<button class='btn btn-warning edit-btn' data-id='" . $row['food_id'] . "'>Sửa</button> ";
                        echo "<button class='btn btn-danger delete-btn' data-id='" . $row['food_id'] . "'>Xóa</button>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <script>
              

                $(document).ready(function(){
                    // Khi nhấn nút "Sửa"
                        $(".edit-btn").click(function() {
                             var food_id = $(this).data("id"); // Lấy ID món ăn từ thuộc tính data-id
                            
                    // Gửi yêu cầu AJAX để lấy thông tin món ăn từ cơ sở dữ liệu
                    $.ajax({
                         url: "get_food.php", // Tạo một trang PHP để lấy thông tin món ăn
                         type: "GET",
                          data: { food_id: food_id },
                         success: function(response) {
                             var food = JSON.parse(response);
                                    // Gán food_id vào input hidden
            $("#edit_food_id").val(food_id);
                   // Điền thông tin vào form trong modal
                   
                        $("#edit_food_name").val(food.food_name);
                        $("#edit_food_price").val(food.food_price);
                        $("#edit_food_description").val(food.food_description);
                        $("#edit_food_image").val(food.food_image_url);
                                    
                   // Mở modal sửa món ăn
                  $("#editFoodModal").modal("show");
                               },
                                        error: function() {
                                           alert("Có lỗi xảy ra khi lấy thông tin món ăn.");
                                        }
                                    });
                                });

                  // Xử lý gửi form sửa món ăn
                                $("#editFoodForm").submit(function(event) {
                                    event.preventDefault(); // Ngừng hành động mặc định của form

                                    // Lấy dữ liệu từ form
                                  
                                    var food_id = $("#edit_food_id").val(); // Lấy ID món ăn từ input hidden; // Lấy ID món ăn từ nút sửa
                                    var food_name = $("#edit_food_name").val();
                                    var food_price = $("#edit_food_price").val();
                                    var food_description = $("#edit_food_description").val();
                                    var food_image = $("#edit_food_image").val();

                                    // Gửi yêu cầu AJAX để cập nhật món ăn
                                    $.ajax({
                                        url: "update_food.php",  // Tạo một trang PHP để cập nhật thông tin món ăn
                                        type: "POST",
                                        data: {
                                            food_id: food_id,
                                            food_name: food_name,
                                            food_price: food_price,
                                            food_description: food_description,
                                            food_image: food_image
                                        },
                                        success: function(response) {
                                            alert(response); // Hiển thị thông báo thành công
                                            location.reload(); // Tải lại trang để cập nhật danh sách
                                        },
                                        error: function() {
                                            alert("Có lỗi xảy ra khi cập nhật món ăn.");
                                        }
                                    });
                                });
                    // Khi nhấn nút "Xóa"
                    $(".delete-btn").click(function(){
                        var food_id = $(this).data("id"); // Lấy ID món ăn từ thuộc tính data-id
                        $("#confirmDeleteBtn").data("id", food_id); // Lưu ID vào nút xác nhận xóa
                        $('#deleteFoodModal').modal('show'); // Hiển thị modal xác nhận xóa
                    });

                    // Khi nhấn "Có" trong modal xác nhận xóa
                    $("#confirmDeleteBtn").click(function(){
                        var food_id = $(this).data("id"); // Lấy lại ID món ăn cần xóa

                        // Gửi yêu cầu AJAX để xóa món ăn
                        $.ajax({
                            url: "delete_food.php", // URL để xử lý xóa
                            type: "POST",
                            data: {
                                action: "delete_food",  // Chỉ thị xóa món ăn
                                food_id: food_id        // ID món ăn cần xóa
                            },
                            success: function(response) {
                                alert(response);  // Hiển thị thông báo khi xóa thành công
                                location.reload();  // Tải lại trang để cập nhật danh sách món ăn
                            },
                            error: function(xhr, status, error) {
                                alert("Có lỗi xảy ra. Vui lòng thử lại.");
                            }
                        });

                        // Đóng modal sau khi xóa thành công
                        $('#deleteFoodModal').modal('hide');
                    });
                   
                    // Khi form được gửi
                    $("#addFoodForm").submit(function(event){
                        event.preventDefault();  // Ngừng hành động mặc định của form (ngừng tải lại trang)

                        // Lấy dữ liệu từ form
                        var food_name = $("#food_name").val();
                        var food_price = $("#food_price").val();
                        var food_description = $("#food_description").val();
                        var food_image = $("#food_image").val();

                        // Gửi yêu cầu AJAX
                        $.ajax({
                            url: "add_food.php",  // URL để gửi dữ liệu đến
                            type: "POST",  // Phương thức gửi dữ liệu
                            data: {
                                food_name: food_name,
                                food_price: food_price,
                                food_description: food_description,
                                food_image: food_image,
                                add_food: true // Chỉ thị thêm món
                            },
                            success: function(response) {
                                // Xử lý phản hồi khi thành công
                                alert(response);  // Hiển thị thông báo từ PHP
                                // Cập nhật lại nội dung trên trang, ví dụ như thêm món ăn mới vào danh sách (nếu cần)
                                location.reload();  // Tải lại trang hiện tại để cập nhật giao diện
                            },
                            error: function(xhr, status, error) {
                                // Xử lý lỗi nếu có
                                alert("Có lỗi xảy ra. Vui lòng thử lại.");
                            }
                        });
                    });
                });
                
                </script>


            </tbody>
        </table>
    </div>
    

        
    </section>
    <!-- Modal Xóa -->
<div class="modal fade" id="deleteFoodModal" tabindex="-1" aria-labelledby="deleteFoodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa món ăn này?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Có</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Sửa Món -->
<div class="modal fade" id="editFoodModal" tabindex="-1" aria-labelledby="editFoodModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editFoodForm" method="POST">

                    <div class="modal-body">
                    <input type="hidden" id="edit_food_id" name="food_id">

                    <div class="mb-3">
                        <label for="edit_food_name" class="form-label">Tên món</label>
                        <input type="text" class="form-control" id="edit_food_name" name="food_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_food_price" class="form-label">Giá món</label>
                        <input type="number" class="form-control" id="edit_food_price" name="food_price" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_food_description" class="form-label">Mô tả món</label>
                        <textarea class="form-control" id="edit_food_description" name="food_description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_food_image" class="form-label">Hình ảnh (URL)</label>
                        <input type="text" class="form-control" id="edit_food_image" name="food_image" placeholder="Nhập URL hình ảnh" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>