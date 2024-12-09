<?php
    session_start();

    if (!isset($_SESSION['admin'])):
        header('Location: ../index.php');
    endif;

    // Kiểm tra kết nối cơ sở dữ liệu
    $connect = mysqli_connect('localhost', 'root', '', 'pizza');
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_set_charset($connect, "utf8");

    // Số lượng khách hàng trên mỗi trang
    $limit = 8;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Xử lý thêm khách hàng
    if (isset($_POST['add_customer'])) {
        $first_name = mysqli_real_escape_string($connect, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($connect, $_POST['last_name']);
        $phone_number = mysqli_real_escape_string($connect, $_POST['phone_number']);
        $email = mysqli_real_escape_string($connect, $_POST['email']);
        $points = intval($_POST['points']);
        
        // Giả sử bạn có một tài khoản sẵn có để sử dụng cho khách hàng
        $account_id = 13; // Thay đổi tùy theo tài khoản thực tế trong bảng account

        // Kiểm tra dữ liệu
        if (!preg_match('/^\d{10}$/', $phone_number)) {
            echo "<script>alert('Số điện thoại phải có 10 chữ số.');</script>";
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Email không hợp lệ.');</script>";
            return;
        }
        
        if ($points < 0) {
            echo "<script>alert('Điểm phải lớn hơn hoặc bằng 0.');</script>";
            return;
        }

        $insert_query = "INSERT INTO customer (customer_first_name, customer_last_name, phone_number, email, points, customer_id) VALUES ('$first_name', '$last_name', '$phone_number', '$email', $points, $account_id)";
        
        if (mysqli_query($connect, $insert_query)) {
            echo "<script>alert('Thêm khách hàng thành công!'); window.location.reload();</script>";
        } else {
            echo "<script>alert('Lỗi thêm khách hàng: " . mysqli_error($connect) . "');</script>";
        }
    }

    // Xử lý sửa khách hàng
    if (isset($_POST['edit_customer'])) {
        $customer_id = intval($_POST['customer_id']);
        $first_name = mysqli_real_escape_string($connect, $_POST['first_name']);
        $last_name = mysqli_real_escape_string($connect, $_POST['last_name']);
        $phone_number = mysqli_real_escape_string($connect, $_POST['phone_number']);
        $email = mysqli_real_escape_string($connect, $_POST['email']);
        $points = intval($_POST['points']);

        $update_query = "UPDATE customer SET customer_first_name='$first_name', customer_last_name='$last_name', phone_number='$phone_number', email='$email', points=$points WHERE customer_id=$customer_id";
        
        if (mysqli_query($connect, $update_query)) {
            echo "<script>alert('Sửa khách hàng thành công!'); window.location.reload();</script>";
        } else {
            echo "<script>alert('Lỗi sửa khách hàng: " . mysqli_error($connect) . "');</script>";
        }
    }

    // Xử lý xóa khách hàng
    if (isset($_POST['delete_customer'])) {
        $customer_id = intval($_POST['customer_id']);
        $delete_query = "DELETE FROM customer WHERE customer_id=$customer_id";
        
        if (mysqli_query($connect, $delete_query)) {
            echo "<script>alert('Xóa khách hàng thành công!'); window.location.reload();</script>";
        } else {
            echo "<script>alert('Lỗi xóa khách hàng: " . mysqli_error($connect) . "');</script>";
        }
    }

    // Xử lý tìm kiếm
    $search_query = '';
    if (isset($_POST['search'])) {
        $search_term = mysqli_real_escape_string($connect, $_POST['search_term']);
        $search_query = "WHERE customer_first_name LIKE '%$search_term%' OR customer_last_name LIKE '%$search_term%' OR phone_number LIKE '%$search_term%' OR email LIKE '%$search_term%' OR points LIKE '%$search_term%'";
    }

    // Truy vấn để lấy tổng số khách hàng
    $total_query = "SELECT COUNT(*) AS total FROM customer $search_query";
    $total_result = mysqli_query($connect, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_customers = $total_row['total'];
    $total_pages = ceil($total_customers / $limit);

    // Truy vấn để lấy khách hàng với phân trang
    $query = "SELECT * FROM customer $search_query LIMIT $limit OFFSET $offset";
    $result = mysqli_query($connect, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/Pizza_DB_favicon.ico">
    <link rel="stylesheet" href="manageUser.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <title>Pizza DB</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <!-- Navigator Bar -->
    <nav class="navbar position-relative navbar-expand-sm navbar-light px-4" style="background-color: #e8e3c5;">
        <div class="container-fluid gap-5">
            <a class="navbar-brand" href="">
                <img src="../image/logo.jpg" alt="logo" style="width: 3rem;">
                <span class="ms-4" style="font-size: 1.5rem;">Pizza DB</span>
            </a>
            <div class="navmenu justify-content-center navbar-collapse gap-5">
                <ul class="navbar-nav gap-5">
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="admin_manager_user.php">Người dùng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="admin_ql.php">Món ăn</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="admin_manager_point.php">Điểm thưởng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="admin_manager_review.php">Bình luận</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-uppercase text-black fw-bold" href="admin_manager_sale.php">Doanh số</a>
                    </li>
                </ul>
            </div>
            <div class="navmenu justify-content-end navbar-collapse col-lg-1 position-relative">
                <form action="../logout.php" method="post"> 
                    <button type="submit" class="btn btn-outline-success text-white btn-danger my-2 my-sm-0 ms-2">
                        Đăng xuất
                    </button>
                </form> 
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4">Quản lý Khách Hàng</h2>
        
        <!-- Ô tìm kiếm -->
        <form method="POST" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" name="search_term" placeholder="Tìm kiếm theo Họ, Tên, Số Điện Thoại, Email hoặc Điểm">
                <button class="btn btn-primary" type="submit" name="search">Tìm</button>
            </div>
        </form>

        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
            Thêm Khách Hàng
        </button>
        
        <!-- Modal Thêm Khách Hàng -->
        <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCustomerModalLabel">Thêm Khách Hàng</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="first_name" class="form-label">Họ</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Tên</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Số Điện Thoại</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="points" class="form-label">Điểm</label>
                                <input type="number" class="form-control" id="points" name="points" min="0">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary" name="add_customer">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Danh sách khách hàng -->
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Họ</th>
                    <th>Tên</th>
                    <th>Số Điện Thoại</th>
                    <th>Email</th>
                    <th>Điểm</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['customer_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['customer_first_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['customer_last_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['phone_number']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['points']) . "</td>";
                    echo "<td>
                            <button class='btn btn-warning edit-btn' data-id='" . $row['customer_id'] . "'>Sửa</button>
                            <button class='btn btn-danger delete-btn' data-id='" . $row['customer_id'] . "'>Xóa</button>
                        </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Phân trang -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <!-- Modal Sửa Khách Hàng -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editCustomerForm" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCustomerModalLabel">Sửa Khách Hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_customer_id" name="customer_id">
                        <div class="mb-3">
                            <label for="edit_first_name" class="form-label">Họ</label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_last_name" class="form-label">Tên</label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_phone_number" class="form-label">Số Điện Thoại</label>
                            <input type="text" class="form-control" id="edit_phone_number" name="phone_number" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_points" class="form-label">Điểm</label>
                            <input type="number" class="form-control" id="edit_points" name="points" min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary" name="edit_customer">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Xóa -->
    <div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-labelledby="deleteCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa khách hàng này?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Có</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        // Sửa khách hàng
        $(".edit-btn").click(function() {
            var customer_id = $(this).data("id");
            $.ajax({
                url: "get_customer.php",
                type: "GET",
                data: { customer_id: customer_id },
                success: function(response) {
                    try {
                        var customer = JSON.parse(response);
                        if (customer.error) {
                            alert(customer.error);
                        } else {
                            $("#edit_customer_id").val(customer.customer_id);
                            $("#edit_first_name").val(customer.customer_first_name);
                            $("#edit_last_name").val(customer.customer_last_name);
                            $("#edit_phone_number").val(customer.phone_number);
                            $("#edit_email").val(customer.email);
                            $("#edit_points").val(customer.points);
                            $("#editCustomerModal").modal("show");
                        }
                    } catch (e) {
                        alert("Có lỗi xảy ra khi xử lý dữ liệu.");
                    }
                },
                error: function() {
                    alert("Có lỗi xảy ra khi lấy thông tin khách hàng.");
                }
            });
        });

        // Xóa khách hàng
        $(".delete-btn").click(function() {
            var customer_id = $(this).data("id");
            $("#confirmDeleteBtn").data("id", customer_id);
            $("#deleteCustomerModal").modal("show");
        });

        $("#confirmDeleteBtn").click(function() {
            var customer_id = $(this).data("id");
            $.post("admin.php", { delete_customer: true, customer_id: customer_id }, function(response) {
                location.reload();
            });
        });
    });
    </script>
</body>

</html>