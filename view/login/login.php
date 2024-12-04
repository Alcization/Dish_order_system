
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../image/Pizza_DB_favicon.ico">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <title>Pizza DB</title>
</head>
<body>

    <?php
        session_start(); // Start the session at the beginning

        // Database connection
        $connect = mysqli_connect('localhost', 'root', '', 'pizza');
        if (!$connect) {
            die("Connection failed: " . mysqli_connect_error());
        }
        mysqli_set_charset($connect, "utf8");

        if(isset($_POST["dangnhap"])){
            // Check if form fields are set
            if(isset($_POST["user_name_lg"]) && isset($_POST["passlg"])){
                $tk = $_POST["user_name_lg"];
                $mk = $_POST["passlg"];

                // Prepare statement to prevent SQL injection
                $stmt = mysqli_prepare($connect, "SELECT password FROM account WHERE user_name = ?");
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "s", $tk);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $db_password);
                    mysqli_stmt_fetch($stmt);
                    mysqli_stmt_close($stmt);

                    // * Why the passwords are stored in plaintext. It is not secure. It is just for the sake of simplicity.

                    if ($db_password && $mk === $db_password) { 
                        $_SESSION["loged"] = true;
			            $_SESSION["account_id"] = $account_id; // Lưu account_id vào session
                        setcookie("success", "Đăng nhập thành công!", time()+3600, "/", "", false, true);
                        $rows = mysqli_query($connect,"SELECT * FROM restaurant WHERE restaurant_id IN (SELECT account_id FROM account WHERE user_name = '$tk' and password = '$mk')");
                        $count = mysqli_num_rows($rows);
                        // Redirect to the appropriate page
                        if ($count == 1) {
                            header("Location: ../admin/admin.php");
                        }
                        else {
                            header("Location: ../customer_home/home.php");
                        }
                        
                        exit();
                    } else {
                        setcookie("error", "Đăng nhập không thành công!", time()+3600, "/", "", false, true);
                        header("Location: login.php");
                        exit();
                    }
                } else {
                    // Handle prepare statement error
                    error_log("Prepare failed: " . mysqli_error($connect));
                    setcookie("error", "Lỗi hệ thống. Vui lòng thử lại!", time()+3600, "/", "", false, true);
                    header("Location: login.php");
                    exit();
                }
            } else {
                setcookie("error", "Vui lòng nhập đầy đủ thông tin!", time()+3600, "/", "", false, true);
                header("Location: login.php");
                exit();
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
        </div>
    </nav>

    <!-- Body -->
    <section style="min-height: 40.6rem;">
        <div class="wrap">
            <div class="login-container">
                <h1>Pizza DB</h1>
                <form action="" method="POST">
                    <input type="text" name="user_name_lg" placeholder="Nhập tên tài khoản" required name="user_name_lg">
                    <input type="password" name="passlg" placeholder="Nhập mật khẩu" name="passlg" required>
                    <button type="submit" name="dangnhap" style="margin-top:1rem">Đăng nhập</button>
                </form>
                <a href="/register">Tạo tài khoản</a>
            </div>
        </div>
        
    </section>
    
</body>
</html>
