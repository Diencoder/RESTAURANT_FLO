<?php
session_start();
include('../frontend/config/constants.php');

// Kiểm tra nếu chưa đăng nhập thì về trang đăng nhập
if (!isset($_SESSION['role']) || !isset($_SESSION['user'])) {
    header('Location: ' . SITEURL . 'login.php');
    exit;
}

// Lấy ID từ URL hoặc session
$id = $_GET['id'] ?? $_SESSION['user_id'] ?? '';

// Xử lý khi người dùng gửi form đổi mật khẩu
if (isset($_POST['submit'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $current_password = mysqli_real_escape_string($conn, md5($_POST['current_password']));
    $new_password = mysqli_real_escape_string($conn, md5($_POST['new_password']));
    $confirm_password = mysqli_real_escape_string($conn, md5($_POST['confirm_password']));

    $sql = "SELECT * FROM tbl_users WHERE id = '$id'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        if ($current_password === $row['password']) {
            if ($new_password === $confirm_password) {
                $update_sql = "UPDATE tbl_users SET password = '$new_password' WHERE id = '$id'";
                $update_res = mysqli_query($conn, $update_sql);

                if ($update_res) {
                    $_SESSION['change-pwd'] = "<div class='success'>✅ Mật khẩu đã được thay đổi thành công.</div>";
                    header('Location: ' . SITEURL . 'manage-admin.php');
                    exit;
                } else {
                    $_SESSION['change-pwd'] = "<div class='error'>❌ Lỗi khi cập nhật mật khẩu.</div>";
                }
            } else {
                $_SESSION['change-pwd'] = "<div class='error'>❌ Mật khẩu mới và xác nhận không khớp.</div>";
            }
        } else {
            $_SESSION['change-pwd'] = "<div class='error'>❌ Mật khẩu hiện tại không đúng.</div>";
        }
    } else {
        $_SESSION['change-pwd'] = "<div class='error'>❌ Không tìm thấy người dùng.</div>";
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
            <li class="active"><a href="manage-admin.php"><i class='bx bxs-group'></i><span class="text">Quản Lý Người Dùng</span></a></li>
            <li><a href="manage-online-order.php"><i class='bx bxs-cart'></i><span class="text">Đơn Hàng Online</span></a></li>
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
                        <li><a href="manage-admin.php">Quản Lý Người Dùng</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="#">Đổi Mật Khẩu</a></li>
                    </ul>
                </div>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <?php 
                            if (isset($_SESSION['change-pwd'])) {
                                echo $_SESSION['change-pwd'];
                                unset($_SESSION['change-pwd']);
                            }
                        ?>
                        <form action="" method="POST">
                            <table class="tbl-30">
                                <tr>
                                    <td>Mật khẩu hiện tại</td>
                                    <td>
                                        <input type="password" name="current_password" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Mật khẩu mới</td>
                                    <td>
                                        <input type="password" name="new_password" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Xác nhận mật khẩu</td>
                                    <td>
                                        <input type="password" name="confirm_password" required>
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
