<?php
// Start session and include constants
session_start();
include('../frontend/config/constants.php');

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ' . SITEURL . 'login.php');
    exit;
}

// Process form submission before any output
if (isset($_POST['submit'])) {
    // Get data from form
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch user to verify current password
    $sql = "SELECT password FROM tbl_users WHERE id = ? AND role = 'admin'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        // Verify current password
        if (password_verify($current_password, $row['password'])) {
            // Check if new password matches confirm password
            if ($new_password === $confirm_password) {
                // Hash new password
                $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                
                // Update password
                $sql2 = "UPDATE tbl_users SET password = ? WHERE id = ? AND role = 'admin'";
                $stmt2 = mysqli_prepare($conn, $sql2);
                mysqli_stmt_bind_param($stmt2, 'si', $hashed_new_password, $id);
                
                if (mysqli_stmt_execute($stmt2)) {
                    $_SESSION['change-pwd'] = "<div class='success'>Mật khẩu đã được thay đổi thành công.</div>";
                    header('Location: ' . SITEURL . 'manage-admin.php');
                    exit();
                } else {
                    $_SESSION['pwd-not-match'] = "<div class='error'>Không thể thay đổi mật khẩu. Vui lòng thử lại.</div>";
                    header('Location: ' . SITEURL . 'manage-admin.php');
                    exit();
                }
            } else {
                $_SESSION['pwd-not-match'] = "<div class='error'>Mật khẩu không trùng khớp. Vui lòng thử lại.</div>";
                header('Location: ' . SITEURL . 'manage-admin.php');
                exit();
            }
        } else {
            $_SESSION['user-not-found'] = "<div class='error'>Mật khẩu hiện tại không đúng.</div>";
            header('Location: ' . SITEURL . 'manage-admin.php');
            exit();
        }
    } else {
        $_SESSION['user-not-found'] = "<div class='error'>Không tìm thấy người dùng.</div>";
        header('Location: ' . SITEURL . 'manage-admin.php');
        exit();
    }
    mysqli_stmt_close($stmt);
}

// Get ID from URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
} else {
    header('Location: ' . SITEURL . 'manage-admin.php');
    exit;
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
    <title>Quản Trị FLO_RESTAURANT - Đổi Mật Khẩu</title>
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="index.php" class="brand">
            <img src="../images/logo.png" width="80px" alt="">
        </a>
        <ul class="side-menu top">
            <li><a href="index.php"><i class='bx bxs-dashboard'></i><span class="text">Bảng Điều Khiển</span></a></li>
            <li class="active"><a href="manage-admin.php"><i class='bx bxs-group'></i><span class="text">Quản Lý Admin</span></a></li>
            <li><a href="manage-online-order.php"><i class='bx bxs-cart'></i><span class="text">Đơn Hàng Online</span></a></li>
            <li><a href="manage-ei-order.php"><i class='bx bx-qr-scan'></i><span class="text">Đơn Hàng Ăn Tại Chỗ</span></a></li>
            <li><a href="manage-table.php"><i class='bx bx-table'></i><span class="text">Quản Lý Bàn</span></a></li>
            <li><a href="manage-category.php"><i class='bx bxs-category'></i><span class="text">Danh Mục</span></a></li>
            <li><a href="manage-food.php"><i class='bx bxs-food-menu'></i><span class="text">Thực Đơn</span></a></li>
            <li><a href="inventory.php"><i class='bx bxs-box'></i><span class="text">Kho Hàng</span></a></li>
            <li><a href="manage-promotions.php"><i class='bx bxs-gift'></i><span class="text">Mã Giảm Giá</span></a></li>
        </ul>
        <ul class="side-menu">
            <li><a href="#"><i class='bx bxs-cog'></i><span class="text">Cài Đặt</span></a></li>
            <li><a href="logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">Đăng Xuất</span></a></li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Tìm kiếm...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Đổi Mật Khẩu</h1>
                    <ul class="breadcrumb">
                        <li><a href="index.php">Bảng Điều Khiển</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a href="manage-admin.php">Quản Lý Admin</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Đổi Mật Khẩu</a></li>
                    </ul>
                </div>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <form action="" method="POST">
                            <table class="tbl-30">
                                <tr>
                                    <td>Mật khẩu hiện tại</td>
                                    <td>
                                        <input type="password" name="current_password" id="ip2" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Mật khẩu mới</td>
                                    <td>
                                        <input type="password" name="new_password" id="ip2" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Xác nhận mật khẩu</td>
                                    <td>
                                        <input type="password" name="confirm_password" id="ip2" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                                        <input type="submit" name="submit" value="Đổi Mật Khẩu" class="button-8" role="button">
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="script-admin.js"></script>
</body>
</html>