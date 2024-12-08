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

    
    // Initialize variables
    $threshold = 0;
    $firstName = '';
    $customers = [];
    $total_customers = 0;
    $total_pages = 1;
    $limit = 8; // customers per page
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
    $offset = ($page - 1) * $limit;
    
    // Handle sorting
    $sort = 'DESC'; // default sort order
    if(isset($_GET['sort_order'])){
        $sort = ($_GET['sort_order'] === 'asc') ? 'ASC' : 'DESC';
    } elseif(isset($_GET['sort'])){
        $sort = ($_GET['sort'] === 'asc') ? 'ASC' : 'DESC';
    }
    
    // Handle search parameters
    if(isset($_GET['search_points'])){
        if(isset($_GET['point_threshold'])){
            $threshold_input = $_GET["point_threshold"];
            if(filter_var($threshold_input, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0]])){
                $threshold = intval($threshold_input);
            } else {
                setcookie("error", "Vui lòng nhập số điểm hợp lệ (số nguyên không âm)!", time()+3600, "/", "", false, true);
                header("Location: managePoint.php");
                exit();
            }
        } else {
            setcookie("error", "Vui lòng nhập số điểm!", time()+3600, "/", "", false, true);
            header("Location: managePoint.php");
            exit();
        }
    
        // Handle first name
        if(isset($_GET['first_name']) && !empty(trim($_GET['first_name']))){
            $firstName = trim($_GET['first_name']);
        }
    
        // Prepare SQL query with dynamic conditions
        $conditions = "points >= ?";
        $types = "i";
        $params = [$threshold];
    
        if(!empty($firstName)){
            $conditions .= " AND customer_first_name LIKE ?";
            $types .= "s";
            $params[] = "%" . $firstName . "%";
        }
    
        // Get total number of customers matching criteria
        $stmt_total = mysqli_prepare($connect, "SELECT COUNT(*) as total FROM customer WHERE $conditions");
        if($stmt_total){
            mysqli_stmt_bind_param($stmt_total, $types, ...$params);
            mysqli_stmt_execute($stmt_total);
            mysqli_stmt_bind_result($stmt_total, $total_customers);
            mysqli_stmt_fetch($stmt_total);
            mysqli_stmt_close($stmt_total);
    
            $total_pages = ceil($total_customers / $limit);
    
            // Adjust the current page if it exceeds total_pages
            if($page > $total_pages && $total_pages > 0){
                $redirect_url = "managePoint.php?page=$total_pages&point_threshold=$threshold&search_points=1&sort=".strtolower($sort);
                if(!empty($firstName)){
                    $redirect_url .= "&first_name=" . urlencode($firstName);
                }
                header("Location: $redirect_url");
                exit();
            }
    
            // Fetch customers matching criteria for current page
            $sql = "SELECT customer_id, customer_first_name, customer_last_name, points FROM customer WHERE $conditions ORDER BY points $sort LIMIT ? OFFSET ?";
            $types_extended = $types . "ii";
            $params_extended = $params;
            $params_extended[] = $limit;
            $params_extended[] = $offset;
    
            $stmt = mysqli_prepare($connect, $sql);
            if($stmt){
                mysqli_stmt_bind_param($stmt, $types_extended, ...$params_extended);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $id, $first_name, $last_name, $points);
    
                while(mysqli_stmt_fetch($stmt)){
                    $customers[] = [
                        'customer_id' => $id,
                        'customer_first_name' => $first_name,
                        'customer_last_name' => $last_name,
                        'points' => $points
                    ];
                }
                mysqli_stmt_close($stmt);
            } else {
                // Handle prepare statement error
                error_log("Prepare failed: " . mysqli_error($connect));
                setcookie("error", "Lỗi hệ thống. Vui lòng thử lại!", time()+3600, "/", "", false, true);
                header("Location: managePoint.php");
                exit();
            }
        } else {
            // Handle prepare statement error
            error_log("Prepare failed: " . mysqli_error($connect));
            setcookie("error", "Lỗi hệ thống. Vui lòng thử lại!", time()+3600, "/", "", false, true);
            header("Location: managePoint.php");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customer Points - Pizza DB</title>
    <link rel="stylesheet" href="managePoint.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
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
        <h2 class="mb-4">Quản lý điểm của khách hàng</h2>
        <form method="GET" action="managePoint.php" class="mb-4">
            <div class="input-group">
                <span class="input-group-text">Điểm lớn hơn hoặc bằng:</span>
                <input type="number" name="point_threshold" class="form-control" placeholder="Enter point threshold" value="<?php echo htmlspecialchars($threshold); ?>" min="0" required>
                
                <span class="input-group-text">Tên:</span>
                <input type="text" name="first_name" class="form-control" placeholder="Enter first name" value="<?php echo htmlspecialchars($firstName); ?>">
                
                <button type="submit" name="search_points" class="btn btn-primary">Search</button>
                <!-- Sort Button with Icons -->
                <button type="submit" name="sort_order" value="<?php echo ($sort === 'ASC') ? 'desc' : 'asc'; ?>" class="btn btn-secondary ms-2">
                    Sắp xếp theo <?php echo ($sort === 'ASC') ? 'giảm dần' : 'tăng dần'; ?>
                    <i class="bi bi-arrow-<?php echo ($sort === 'ASC') ? 'down' : 'up'; ?>"></i>
                </button>
                <!-- Hidden input to ensure search_points is set -->
                <input type="hidden" name="search_points" value="1">
            </div>
        </form>

        <?php if(isset($_GET['search_points'])): ?>
            <h4 class="mb-3">
                Khách hàng với điểm lớn hơn hoặc bằng <?php echo htmlspecialchars($threshold); ?>
                <?php if(!empty($firstName)): ?>
                    và tên chứa "<?php echo htmlspecialchars($firstName); ?>"
                <?php endif; ?>:
            </h4>
            <?php if($total_customers > 0): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Họ</th>
                            <th>Tên</th>
                            <th>Điểm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($customers as $customer): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($customer['customer_id']); ?></td>
                                <td><?php echo htmlspecialchars($customer['customer_last_name']); ?></td>
                                <td><?php echo htmlspecialchars($customer['customer_first_name']); ?></td>
                                <td><?php echo htmlspecialchars($customer['points']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if($total_pages > 1): ?>
                    <nav aria-label="Page navigation example" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php
                                $query_params = [
                                    'point_threshold' => $threshold,
                                    'search_points' => 1,
                                    'sort' => strtolower($sort)
                                ];
                                if(!empty($firstName)){
                                    $query_params['first_name'] = $firstName;
                                }
                                $query_string = http_build_query($query_params);
                            ?>
                            <?php if($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="managePoint.php?page=<?php echo $page-1; ?>&<?php echo $query_string; ?>">Trước</a>
                                </li>
                            <?php endif; ?>

                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php if($i == $page) echo 'active'; ?>">
                                    <a class="page-link" href="managePoint.php?page=<?php echo $i; ?>&<?php echo $query_string; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="managePoint.php?page=<?php echo $page+1; ?>&<?php echo $query_string; ?>">Sau</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-info">Không tìm thấy khách hàng với điểm lớn hơn hoặc bằng <?php echo htmlspecialchars($threshold); ?>
                    <?php if(!empty($firstName)): ?>
                        và tên chứa "<?php echo htmlspecialchars($firstName); ?>"
                    <?php endif; ?>.
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</body>
</html>