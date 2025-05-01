<?php
ob_start(); // Bắt đầu output buffering
include('../frontend/config/constants.php');

// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ' . SITEURL . 'login.php');
    exit;
}

// Lấy ID người dùng từ URL
$id = $_GET['id'];  // ID người dùng cần cập nhật

// Truy vấn để lấy thông tin người dùng
$sql = "SELECT * FROM tbl_users WHERE id = '$id'";  // Lấy thông tin của tất cả người dùng hoặc admin
$res = mysqli_query($conn, $sql);

// Kiểm tra nếu có dữ liệu
if ($res == true) {
    $count = mysqli_num_rows($res);
    if ($count == 1) {
        // Lấy thông tin người dùng
        $row = mysqli_fetch_assoc($res);
        $full_name = $row['name'];
        $username = $row['username'];
        $email = $row['email'];
        $phone = $row['phone'];
        $address = $row['add1'];
        $city = $row['city'];
    } else {
        header('location:' . SITEURL . 'manage-users.php');
        exit();
    }
}

// Cập nhật thông tin khi người dùng nhấn "Cập Nhật"
if (isset($_POST['submit'])) {
    $cus_name = $_POST['full_name'];
    $cus_username = $_POST['username'];
    $cus_email = $_POST['email'];
    $cus_phone = $_POST['phone'];
    $cus_address = $_POST['address'];
    $cus_city = $_POST['city'];

    // Tắt kiểm tra khóa ngoại để tránh lỗi khóa ngoại
    mysqli_query($conn, "SET foreign_key_checks = 0");

    // Cập nhật `username` trong `order_manager` để tránh vi phạm khóa ngoại
    $sql_order_manager_update = "UPDATE order_manager SET username = '$cus_username' WHERE username = '$username'";
    $res_order_manager_update = mysqli_query($conn, $sql_order_manager_update);

    // Cập nhật `username` trong `tbl_users`
    $update_sql = "UPDATE tbl_users SET 
                        name = '$cus_name', 
                        username = '$cus_username',
                        email = '$cus_email', 
                        phone = '$cus_phone', 
                        add1 = '$cus_address', 
                        city = '$cus_city' 
                        WHERE id = '$id'";

    $res_update = mysqli_query($conn, $update_sql);

    // Bật lại kiểm tra khóa ngoại sau khi cập nhật
    mysqli_query($conn, "SET foreign_key_checks = 1");

    if ($res_update == true) {
        $_SESSION['update'] = "<div class='success'>Cập nhật thông tin người dùng thành công</div>";
        header('location:' . SITEURL . 'manage-admin.php');
        exit();
    } else {
        $_SESSION['update'] = "<div class='error'>Cập nhật thông tin người dùng thất bại</div>";
        header('location:' . SITEURL . 'manage-admin.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style-admin.css">
    <link rel="icon" type="image/png" href="../images/logo.png">
    <title>Quản Trị FLO_RESTAURANT - Cập Nhật Người Dùng</title>
</head>
<body>

<!-- SIDEBAR -->
<section id="sidebar">
    <a href="index.php" class="brand">
        <img src="../images/logo.png" width="80px" alt="">
    </a>
    <ul class="side-menu top">
        <li><a href="index.php"><i class='bx bxs-dashboard'></i><span class="text">Bảng Điều Khiển</span></a></li>
        <li><a href="manage-admin.php"><i class='bx bxs-group'></i><span class="text">Quản Lý Người Dùng</span></a></li>
        <li><a href="manage-online-order.php"><i class='bx bxs-cart'></i><span class="text">Đơn Hàng Online&nbsp;</span></a></li>
        <li><a href="manage-table.php"><i class='bx bx-table'></i><span class="text">Quản Lý Bàn&nbsp;</span></a></li>
        <li><a href="manage-category.php"><i class='bx bxs-category'></i><span class="text">Danh Mục</span></a></li>
        <li><a href="manage-food.php"><i class='bx bxs-food-menu'></i><span class="text">Thực Đơn</span></a></li>
        <li><a href="inventory.php"><i class='bx bxs-box'></i><span class="text">Kho Hàng</span></a></li>
        <li><a href="manage-promotions.php"><i class='bx bxs-gift'></i><span class="text">Mã Giảm Giá</span></a></li>
    </ul>
</section>
<!-- SIDEBAR -->

<!-- CONTENT -->
<section id="content">
    <nav>
        <i class='bx bx-menu'></i>
        <form action="#">
            <div class="form-input">
                <input type="search" placeholder="Tìm kiếm...">
                <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
            </div>
        </form>
    </nav>

    <!-- MAIN -->
    <main>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <h1>Cập Nhật Người Dùng</h1>
                </div>

                <!-- Hiển thị thông báo cập nhật thành công hoặc lỗi -->
                <?php 
                    if (isset($_SESSION['update'])) {
                        echo $_SESSION['update'];
                        unset($_SESSION['update']);
                    }
                ?>

                <form action="" method="POST">
                    <table class="rtable">
                        <tr>
                            <td>Họ Tên</td>
                            <td>
                                <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Tên Đăng Nhập</td>
                            <td>
                                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Số Điện Thoại</td>
                            <td>
                                <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td>Địa chỉ</td>
                            <td>
                                <textarea name="address" required><?php echo htmlspecialchars($address); ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Thành phố</td>
                            <td>
                                <input type="text" name="city" value="<?php echo htmlspecialchars($city); ?>" required>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="submit" name="submit" value="Cập Nhật" class="button-8" role="button">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </main>
    <!-- MAIN -->
</section>
<!-- CONTENT -->

<script src="script-admin.js"></script>
</body>
</html>

<?php ob_end_flush(); // Kết thúc buffer sau khi mọi thao tác hoàn tất ?>
