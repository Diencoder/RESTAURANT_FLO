<?php
// Bắt đầu output buffering ngay từ đầu
ob_start();

// Bắt đầu session
session_start();
include('../frontend/config/constants.php');

// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Nếu không phải admin hoặc chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: ' . SITEURL . 'login.php');
    exit;
}

// Kiểm tra nếu có tham số table_number và area qua URL để xử lý việc đặt bàn
if (isset($_GET['table_number']) && isset($_GET['area'])) {
    $table_number = $_GET['table_number'];
    $area = $_GET['area'];
    
    // Kiểm tra nếu form được submit
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reserve'])) {
        // Lấy thông tin khách hàng từ form
        $customer_name = $_POST['customer_name'];
        $customer_phone = $_POST['customer_phone'];
        $customer_email = $_POST['customer_email'];

        // Thêm thông tin đặt bàn vào cơ sở dữ liệu
        $sql_reserve = "INSERT INTO tbl_reservations (table_number, area, reservation_time, status, customer_name, customer_phone, customer_email) 
                        VALUES ('$table_number', '$area', NOW(), 'Confirmed', '$customer_name', '$customer_phone', '$customer_email')";
        
        if (mysqli_query($conn, $sql_reserve)) {
            // Cập nhật trạng thái bàn sau khi đặt thành 'Reserved'
            $sql_update_table = "UPDATE tbl_tables SET status = 'Reserved' WHERE table_number = '$table_number' AND area = '$area'";
            mysqli_query($conn, $sql_update_table);

            // Chuyển hướng về trang quản lý bàn
            $_SESSION['reserve'] = "<div class='success'>Đặt bàn thành công!</div>";
            header("Location: manage-table.php");
            exit();
        } else {
            // Nếu có lỗi trong việc đặt bàn
            $_SESSION['reserve'] = "<div class='error'>Lỗi trong quá trình đặt bàn, vui lòng thử lại!</div>";
        }
    }
} else {
    // Nếu không có tham số trong URL
    header("Location: manage-table.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style-admin.css">
    <link rel="icon" type="image/png" href="../images/logo.png">
    <title>Quản Trị FLO_RESTAURANT</title>

    <!-- CSS của riêng trang này -->
    <link rel="stylesheet" href="manage-table1.css">
   
</head>
<body>

    
<!-- SIDEBAR -->
<section id="sidebar">
    <a href="index.php" class="brand">
        <img src="../images/logo.png" width="80px" alt="">
    </a>
    <ul class="side-menu top">
        <li><a href="index.php"><i class='bx bxs-dashboard'></i><span class="text">Bảng điều khiển</span></a></li>
        <li><a href="manage-admin.php"><i class='bx bxs-group'></i><span class="text">Quản lý Admin</span></a></li>
        <li><a href="manage-online-order.php"><i class='bx bxs-cart'></i><span class="text">Đơn hàng Online&nbsp;</span></a></li>
        <li><a href="manage-ei-order.php"><i class='bx bx-qr-scan'></i><span class="text">Đơn hàng Ăn tại chỗ&nbsp;&nbsp;&nbsp;</span></a></li>
        <li><a href="manage-table.php"><i class='bx bx-table'></i><span class="text">Quản lý Bàn&nbsp;&nbsp;&nbsp;</span></a></li>
        <li><a href="manage-category.php"><i class='bx bxs-category'></i><span class="text">Danh mục</span></a></li>
        <li><a href="manage-food.php"><i class='bx bxs-food-menu'></i><span class="text">Thực đơn</span></a></li>
        <li><a href="inventory.php"><i class='bx bxs-box'></i><span class="text">Kho</span></a></li>
    </ul>
    <ul class="side-menu">
        <li><a href="#"><i class='bx bxs-cog'></i><span class="text">Cài đặt</span></a></li>
        <li><a href="logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">Đăng xuất</span></a></li>
    </ul>
</section>

<section id="content">
       <nav>
            <i class='bx bx-menu'></i>
            <a href="#" class="nav-link"></a>
            <form action="#">
                <div class="form-input">
                    <input type="search" placeholder="Tìm kiếm...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
        </nav>

    <main>
        <div class="head-title">
            <h1>Đặt Bàn</h1>
        </div>
        <br>

        <!-- Form Đặt Bàn -->
        <div class="reserve-table-form">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Tên khách hàng</label>
                    <input type="text" name="customer_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="customer_phone" class="form-label">Số điện thoại</label>
                    <input type="text" name="customer_phone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="customer_email" class="form-label">Email</label>
                    <input type="email" name="customer_email" class="form-control" required>
                </div>
                <input type="hidden" name="table_number" value="<?php echo $_GET['table_number']; ?>">
                <input type="hidden" name="area" value="<?php echo $_GET['area']; ?>">

                <button type="submit" name="reserve" class="btn btn-primary">Đặt Bàn</button>
            </form>
        </div>
    </main>
</section>

<script src="script-admin.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
