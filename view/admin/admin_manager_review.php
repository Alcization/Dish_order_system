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

    // Fetch list of foods for the dropdown
    $foods = [];
    $food_query = "SELECT food_id, food_name FROM food";
    $food_result = mysqli_query($connect, $food_query);
    while($row = mysqli_fetch_assoc($food_result)){
        $foods[] = $row;
    }

    // Initialize variables
    $selected_food = '';
    $rating = '';
    $reviews = [];
    $sort_order = 'ASC'; // Default sort order

    // Handle delete review action
    if(isset($_POST['delete_review'])){
        $customer_id = $_POST['customer_id'];
        $food_id = $_POST['food_id'];
        $delete_stmt = mysqli_prepare($connect, "DELETE FROM user_review WHERE customer_id = ? AND food_id = ?");
        if($delete_stmt){
            mysqli_stmt_bind_param($delete_stmt, "ii", $customer_id, $food_id);
            mysqli_stmt_execute($delete_stmt);
            mysqli_stmt_close($delete_stmt);
            setcookie("success", "Review deleted successfully!", time()+3600, "/", "", false, true);
            header("Location: manageReview.php");
            exit();
        } else {
            setcookie("error", "Failed to delete review. Please try again.", time()+3600, "/", "", false, true);
            header("Location: manageReview.php");
            exit();
        }
    }

    // Handle form submission
    if(isset($_GET['search_reviews'])){
        if(isset($_GET['food_id']) && isset($_GET['rating'])){
            $selected_food = $_GET['food_id'];
            $rating = $_GET['rating'];
            $sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

            // Validate rating
            if($rating === 'all' || preg_match('/^[1-5]$/', $rating)){
                // Call the stored procedure to fetch reviews
                $stmt = mysqli_prepare($connect, "CALL GetCustomersByRatingAndFood(?, ?)");
                if($stmt){
                    mysqli_stmt_bind_param($stmt, "si", $rating, $selected_food);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $customer_id, $full_name, $phone_number, $email, $food_id, $rating, $review_description);

                    while(mysqli_stmt_fetch($stmt)){
                        $reviews[] = [
                            'customer_id' => $customer_id,
                            'full_name' => $full_name,
                            'phone_number' => $phone_number,
                            'email' => $email,
                            'food_id' => $food_id,
                            'rating' => $rating,
                            'review_description' => $review_description
                        ];
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    // Handle prepare statement error
                    error_log("Prepare failed: " . mysqli_error($connect));
                    setcookie("error", "System error. Please try again!", time()+3600, "/", "", false, true);
                    header("Location: manageReview.php");
                    exit();
                }
            } else {
                setcookie("error", "Please enter a valid rating (1-5) or 'all'!", time()+3600, "/", "", false, true);
                header("Location: manageReview.php");
                exit();
            }
        } else {
            setcookie("error", "Please select a food item and enter a rating!", time()+3600, "/", "", false, true);
            header("Location: manageReview.php");
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customer Reviews - Pizza DB</title>
    <link rel="icon" href="../image/Pizza_DB_favicon.ico">
    <link rel="stylesheet" href="manageReview.css">
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
        <h2 class="mb-4">Search Customer Reviews</h2>
        <form method="GET" action="admin_manager_review.php" class="mb-4">
            <div class="input-group mb-3">
                <label class="input-group-text" for="food_id">Select Food:</label>
                <select class="form-select" id="food_id" name="food_id" required>
                    <option value="">Choose...</option>
                    <?php foreach($foods as $food): ?>
                        <option value="<?php echo htmlspecialchars($food['food_id']); ?>" <?php if($selected_food == $food['food_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($food['food_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-group mb-3">
                <span class="input-group-text">Rating:</span>
                <input type="text" name="rating" class="form-control" placeholder="Enter rating (1-5) or 'all'" value="<?php echo htmlspecialchars($rating); ?>" required>
            </div>
            <input type="hidden" name="sort_order" value="<?php echo htmlspecialchars($sort_order); ?>">
            <button type="submit" name="search_reviews" class="btn btn-primary">Search</button>
            <button type="submit" name="sort_order" value="<?php echo ($sort_order === 'ASC') ? 'DESC' : 'ASC'; ?>" class="btn btn-secondary ms-2">
                Sort by Rating <?php echo ($sort_order === 'ASC') ? 'Descending' : 'Ascending'; ?>
            </button>
        </form>

        <?php if(isset($_GET['search_reviews'])): ?>
            <h4 class="mb-3">Customers who rated <?php echo htmlspecialchars($rating); ?> for the selected food:</h4>
            <?php if(count($reviews) > 0): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Rating</th>
                            <th>Review Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($reviews as $review): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($review['customer_id']); ?></td>
                                <td><?php echo htmlspecialchars($review['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($review['phone_number']); ?></td>
                                <td><?php echo htmlspecialchars($review['email']); ?></td>
                                <td><?php echo htmlspecialchars($review['rating']); ?></td>
                                <td><?php echo htmlspecialchars($review['review_description']); ?></td>
                                <td>
                                    <form method="POST" action="admin_manager_review.php" style="display:inline;">
                                        <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($review['customer_id']); ?>">
                                        <input type="hidden" name="food_id" value="<?php echo htmlspecialchars($selected_food); ?>">
                                        <button type="submit" name="delete_review" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">No customers found with the specified rating for the selected food.</div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</body>
</html>