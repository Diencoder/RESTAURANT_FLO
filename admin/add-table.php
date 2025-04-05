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

// Xử lý thêm bàn
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_table'])) {
    $table_number = mysqli_real_escape_string($conn, $_POST['table_number']);
    $area = mysqli_real_escape_string($conn, $_POST['area']);
    $capacity = mysqli_real_escape_string($conn, $_POST['capacity']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Kiểm tra xem bàn đã tồn tại hay chưa
    $check_table = "SELECT * FROM tbl_tables WHERE table_number='$table_number' AND area='$area'";
    $res_check = mysqli_query($conn, $check_table);

    if (mysqli_num_rows($res_check) > 0) {
        echo "<script>alert('Bàn đã tồn tại trong khu vực này. Vui lòng thử lại với số bàn khác.');</script>";
    } else {
        // Thêm bàn mới vào bảng tbl_tables
        $insert_table = "INSERT INTO tbl_tables (table_number, area, capacity, status) 
                         VALUES ('$table_number', '$area', '$capacity', '$status')";
        mysqli_query($conn, $insert_table);

        // Thông báo và chuyển hướng về trang quản lý bàn
        $_SESSION['add'] = "<div class='success'>Bàn đã được thêm thành công!</div>";
        header("Location: manage-table.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS của tôi -->
    <link rel="stylesheet" href="style-admin.css">
    <link rel="stylesheet" href="manage-table1.css">

    <link rel="icon" type="image/png" href="../images/logo.png">
    <title>Quản Trị FLO_RESTAURANT</title>
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
        <li class="active"><a href="manage-table.php"><i class='bx bx-table'></i><span class="text">Quản lý Bàn&nbsp;&nbsp;&nbsp;</span></a></li>
        <li><a href="manage-category.php"><i class='bx bxs-category'></i><span class="text">Danh mục</span></a></li>
        <li><a href="manage-food.php"><i class='bx bxs-food-menu'></i><span class="text">Thực đơn</span></a></li>
        <li><a href="inventory.php"><i class='bx bxs-box'></i><span class="text">Kho</span></a></li>
        <li><a href="manage-promotions.php"><i class='bx bxs-gift'></i><span class="text">Mã Giảm Giá</span></a></li>
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
                <h1>Thêm Bàn Mới</h1>
                <ul class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="manage-table.php">Quản lý Bàn</a></li>
                    <li><i class='bx bx-chevron-right'></i></li>
                    <li><a class="active" href="add-table.php">Thêm Bàn</a></li>
                </ul>
            </div>
            <br>

            <!-- Form Thêm Bàn -->
            <div class="add-table-form">
                <form method="POST" action="add-table.php">
                    <label for="table_number">Số Bàn:</label>
                    <input type="text" name="table_number" id="table_number" required>

                    <label for="area">Khu Vực:</label>
                    <input type="text" name="area" id="area" required>

                    <label for="capacity">Sức Chứa:</label>
                    <input type="number" name="capacity" id="capacity" required>

                    <label for="status">Trạng Thái:</label>
                    <select name="status" id="status" required>
                        <option value="Available">Có Sẵn</option>
                        <option value="Reserved">Đã Đặt</option>
                    </select>

                    <input type="submit" name="add_table" value="Thêm Bàn" class="btn btn-success">
                </form>
            </div>
        </main>
    </section>

    <script src="script-admin.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
