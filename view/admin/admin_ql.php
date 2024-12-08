<?php
    session_start();

    if (!isset($_SESSION['admin'])):
        header('Location: ../index.php');
    endif;
    // Kết nối cơ sở dữ liệu
    $connect = mysqli_connect('localhost', 'root', '', 'pizza');
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }
    mysqli_set_charset($connect, "utf8");

    // Phân trang
    $products_per_page = 8;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $offset = ($page - 1) * $products_per_page;

    // Lấy từ khóa tìm kiếm
    $search_query = isset($_GET['query']) ? $_GET['query'] : null;

    // Truy vấn món ăn dựa trên tìm kiếm
    $sql = "
        SELECT 
            f.food_id, 
            f.food_name, 
            f.food_price, 
            f.food_description, 
            fi.food_image_url 
        FROM 
            food f 
        LEFT JOIN 
            food_image fi 
        ON 
            f.food_id = fi.food_id 
        WHERE 
            f.food_name LIKE ?
        GROUP BY 
            f.food_id 
        LIMIT $products_per_page 
        OFFSET $offset";

    $stmt = $connect->prepare($sql);
    $search_term = '%' . $search_query . '%';
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    // Đếm tổng số món ăn khớp với tìm kiếm
    $total_query = "
        SELECT 
            COUNT(*) as total 
        FROM 
            food 
        WHERE 
            food_name LIKE ?";
    $total_stmt = $connect->prepare($total_query);
    $total_stmt->bind_param("s", $search_term);
    $total_stmt->execute();
    $total_result = $total_stmt->get_result();
    $total_products = $total_result->fetch_assoc()['total'];
    $total_pages = ceil($total_products / $products_per_page);

    // Xử lý thêm món
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_food'])) {
            $food_name = $_POST['food_name'];
            $food_price = $_POST['food_price'];
            $food_description = $_POST['food_description'];
            $food_image = $_POST['food_image'];

            $stmt = $connect->prepare("
                INSERT INTO food (food_name, food_price, food_description)
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("sis", $food_name, $food_price, $food_description);
            $stmt->execute();

            $last_id = $connect->insert_id;
            $stmt = $connect->prepare("
                INSERT INTO food_image (food_id, food_image_url)
                VALUES (?, ?)
            ");
            $stmt->bind_param("is", $last_id, $food_image);
            $stmt->execute();

            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }

    
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_food'])) {
            $food_id = $_POST['food_id'];
            $food_name = $_POST['food_name'];
            $food_price = $_POST['food_price'];
            $food_description = $_POST['food_description'];
            $food_image = $_POST['food_image'];
        
            // Cập nhật bảng food
            $stmt = $connect->prepare("
                UPDATE food 
                SET food_name = ?, food_price = ?, food_description = ?
                WHERE food_id = ?
            ");
            $stmt->bind_param("sisi", $food_name, $food_price, $food_description, $food_id);
            $stmt->execute();
        
            // Cập nhật bảng food_image
            $stmt = $connect->prepare("
                UPDATE food_image 
                SET food_image_url = ?
                WHERE food_id = ?
            ");
            $stmt->bind_param("si", $food_image, $food_id);
            $stmt->execute();
        
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
        // Xử lý sửa món ăn (logic bổ sung tại đây nếu cần)
    }
?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['food_name'])) {
    $food_name = $_POST['food_name'];
    $food_price = $_POST['food_price'];
    $food_description = $_POST['food_description'];
    $food_image = $_POST['food_image'];

    $stmt = $connect->prepare("
        INSERT INTO food (food_name, food_price, food_description)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("sis", $food_name, $food_price, $food_description);
    $stmt->execute();

    $last_id = $connect->insert_id;
    $stmt = $connect->prepare("
        INSERT INTO food_image (food_id, food_image_url)
        VALUES (?, ?)
    ");
    $stmt->bind_param("is", $last_id, $food_image);
    $stmt->execute();

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}


// Xử lý xóa món ăn
if (isset($_POST['delete_food'])) {
    $food_id = $_POST['food_id'];
    $stmt = $connect->prepare("DELETE FROM food WHERE food_id = ?");
    $stmt->bind_param("i", $food_id);
    $stmt->execute();

    $stmt = $connect->prepare("DELETE FROM food_image WHERE food_id = ?");
    $stmt->bind_param("i", $food_id);
    $stmt->execute();

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}
                                 

?>
<!DOCTYPE html>
<html lang="en">
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/Pizza_DB_favicon.ico">
    <link rel="stylesheet" href="admin_ql.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Pizza DB</title>
</head>
<body>
    <!-- Thanh điều hướng -->
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
    <div class="container mt-5">
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
    <!-- Ô tìm kiếm -->
    <div class="container mt-4">

        <form method="GET" action="" class="d-flex mb-4">
            <input 
                type="text" 
                name="query" 
                class="form-control me-2" 
                placeholder="Nhập tên món ăn..."  style="width: 250px;" 
                value="<?= htmlspecialchars($search_query) ?>" 
                required>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i> Tìm kiếm
            </button>
        </form>
    </div>

    <!-- Danh sách món ăn -->
    <div class="container">
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <img 
                                src="<?= htmlspecialchars($row['food_image_url'] ?: '../image/logo.jpg') ?>" 
                                class="card-img-top" 
                                alt="<?= htmlspecialchars($row['food_name']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($row['food_name']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($row['food_description']) ?></p>
                                <p class="card-text fw-bold text-danger">
                                    <?= number_format($row['food_price'], 0, ',', '.') ?> VND
                                </p>
                                <div class="d-flex justify-content-between">
                                <!-- Biểu mẫu xóa món ăn -->
                                <form method="POST" action="" onsubmit="return confirmDelete('<?= htmlspecialchars($row['food_name']) ?>')">
                                    <input type="hidden" name="food_id" value="<?= htmlspecialchars($row['food_id']) ?>">
                                    <button type="submit" name="delete_food" class="btn btn-danger">Xóa</button>
                                </form>


                                <button 
                                    type="button" 
                                    class="btn btn-warning edit-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editFoodModal" 
                                    data-food-id="<?= htmlspecialchars($row['food_id']) ?>" 
                                    data-food-name="<?= htmlspecialchars($row['food_name']) ?>" 
                                    data-food-price="<?= htmlspecialchars($row['food_price']) ?>" 
                                    data-food-description="<?= htmlspecialchars($row['food_description']) ?>" 
                                    data-food-image="<?= htmlspecialchars($row['food_image_url']) ?>">
                                    Sửa
                                </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center text-danger">
                        Không tìm thấy món ăn nào phù hợp với từ khóa "<strong><?= htmlspecialchars($search_query) ?></strong>".
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Phân trang -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?query=<?= urlencode($search_query) ?>&page=<?= $page - 1 ?>">Trước</a>
                </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?query=<?= urlencode($search_query) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?query=<?= urlencode($search_query) ?>&page=<?= $page + 1 ?>">Sau</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <script>
            function confirmDelete(foodName) {
                return confirm(`Bạn có chắc muốn xóa món ăn "${foodName}" không?`);
            }
        </script>

    <script> src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   

</body>
</html>
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
                        <input type="text" class="form-control" id="edit_food_image" name="food_image" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary" name="update_food">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const editButtons = document.querySelectorAll(".edit-btn");
        const modal = document.getElementById("editFoodModal");

        editButtons.forEach(button => {
            button.addEventListener("click", function () {
                // Lấy dữ liệu từ các thuộc tính data-*
                const foodId = this.getAttribute("data-food-id");
                const foodName = this.getAttribute("data-food-name");
                const foodPrice = this.getAttribute("data-food-price");
                const foodDescription = this.getAttribute("data-food-description");
                const foodImage = this.getAttribute("data-food-image");

                // Gán dữ liệu vào các input của modal
                modal.querySelector("#edit_food_id").value = foodId;
                modal.querySelector("#edit_food_name").value = foodName;
                modal.querySelector("#edit_food_price").value = foodPrice;
                modal.querySelector("#edit_food_description").value = foodDescription;
                modal.querySelector("#edit_food_image").value = foodImage;
            });
        });
    });
</script>
