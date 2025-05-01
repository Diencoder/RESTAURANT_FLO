<?php 
// Bắt đầu output buffering ngay từ đầu
ob_start();

// Bắt đầu session
session_start();
include('../frontend/config/constants.php');

// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ' . SITEURL . 'login.php');
    exit;
}

// Lấy thông tin bàn và đặt bàn
$sql = "SELECT t.*, r.customer_name, r.customer_phone, r.customer_email, r.status AS reservation_status 
        FROM tbl_tables t
        LEFT JOIN tbl_reservations r ON t.table_number = r.table_number AND t.area = r.area
        LEFT JOIN tbl_users u ON r.customer_email = u.email"; // Lấy tên khách hàng từ bảng tbl_users
$res = mysqli_query($conn, $sql);

// Kiểm tra nếu có dữ liệu
$count = mysqli_num_rows($res);

//Orders

$ei_order_notif = "SELECT order_status from tbl_eipay
					WHERE order_status='Pending' OR order_status='Processing'";

$res_ei_order_notif = mysqli_query($conn, $ei_order_notif);

$row_ei_order_notif = mysqli_num_rows($res_ei_order_notif);

$online_order_notif = "SELECT order_status from order_manager
					WHERE order_status='Pending'OR order_status='Processing' ";

$res_online_order_notif = mysqli_query($conn, $online_order_notif);

$row_online_order_notif = mysqli_num_rows($res_online_order_notif);


// Stock Notification
$stock_notif = "SELECT stock FROM tbl_food
				WHERE stock<50";

$res_stock_notif = mysqli_query($conn, $stock_notif);
$row_stock_notif = mysqli_num_rows($res_stock_notif);

// Revenue Generated
$revenue = "SELECT SUM(total_amount) AS total_amount FROM order_manager
			WHERE order_status='Delivered' ";
$res_revenue = mysqli_query($conn, $revenue);
$total_revenue = mysqli_fetch_array($res_revenue);

//Total Orders Delivered

$orders_delivered = "SELECT order_status FROM order_manager
					 WHERE order_status='Delivered'";
$res_orders_delivered = mysqli_query($conn, $orders_delivered);
$total_orders_delivered = mysqli_num_rows($res_orders_delivered);

//Message Notification
$message_notif = "SELECT message_status FROM message
				 WHERE message_status = 'unread'";
$res_message_notif = mysqli_query($conn, $message_notif);
$row_message_notif = mysqli_num_rows($res_message_notif);

?>

<?php
// Kiểm tra nếu có yêu cầu hủy đặt bàn
if (isset($_GET['table_number']) && isset($_GET['area'])) {
    $table_number = $_GET['table_number'];
    $area = $_GET['area'];

    // Xóa thông tin đặt bàn trong bảng tbl_reservations
    $delete_reservation = "DELETE FROM tbl_reservations WHERE table_number = '$table_number' AND area = '$area'";
    $res_delete = mysqli_query($conn, $delete_reservation);

    // Cập nhật lại trạng thái bàn thành 'Available'
    $update_table_status = "UPDATE tbl_tables SET status = 'Available' WHERE table_number = '$table_number' AND area = '$area'";
    mysqli_query($conn, $update_table_status);

    // Thông báo và chuyển hướng về trang quản lý bàn
    $_SESSION['cancel'] = "<div class='success'>Đặt bàn đã được hủy thành công.</div>";
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
    <link rel="stylesheet" href="style-admin.css">
    <link rel="stylesheet" href="manage-table.css">
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
        <li><a href="index.php"><i class='bx bxs-dashboard'></i><span class="text">Bảng Điều Khiển</span></a></li>
        <li><a href="manage-admin.php"><i class='bx bxs-group'></i><span class="text">Quản Lý Người Dùng</span></a></li>
        <li><a href="manage-online-order.php"><i class='bx bxs-cart'></i><span class="text">Đơn Hàng Online&nbsp;</span>
            <?php if($row_online_order_notif > 0) { ?>
                <span class="num-ei"><?php echo $row_online_order_notif; ?></span>
            <?php } ?>
        </a></li>
        <li  class="active" ><a href="manage-table.php"><i class='bx bx-table'></i><span class="text">Quản Lý Bàn&nbsp;&nbsp;&nbsp;</span>
            <?php if($row_ei_order_notif > 0) { ?>
                <span class="num-ei"><?php echo $row_ei_order_notif; ?></span>
            <?php } ?>
        </a></li>
        <li><a href="manage-category.php"><i class='bx bxs-category'></i><span class="text">Danh Mục</span></a></li>
        <li><a href="manage-food.php"><i class='bx bxs-food-menu'></i><span class="text">Thực Đơn</span></a></li>
        <li><a href="inventory.php"><i class='bx bxs-box'></i><span class="text">Kho Hàng</span></a></li>
        <!-- Thêm mục Mã Giảm Giá -->
        <li><a href="manage-promotions.php"><i class='bx bxs-gift'></i><span class="text">Mã Giảm Giá</span></a></li>
    </ul>
    <ul class="side-menu">
        <li><a href="#"><i class='bx bxs-cog'></i><span class="text">Cài Đặt</span></a></li>
        <li><a href="logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">Đăng Xuất</span></a></li>
    </ul>
</section>
<!-- SIDEBAR -->

<section id="content">
    <nav>
        <i class='bx bx-menu' ></i>
        <a href="#" class="nav-link"></a>
        <form action="#">
            <div class="form-input">
                <input type="search" placeholder="Tìm kiếm...">
                <button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
            </div>
        </form>
        <input type="checkbox" id="switch-mode" hidden>
        <label for="switch-mode" class="switch-mode"></label>
    </nav>

    <main>
        <div class="head-title">
            <h1>Quản lý Bàn</h1>
        </div>
        <br>

        <!-- Nút chuyển sang trang thêm bàn -->
        <a href="/RESTAURANT_FLO/admin/add-table.php" class="button-8 mb-3" role="button">Thêm Bàn Mới</a>

        <br><br>

        <div class="table-data">
            <table class="table">
                <tr>
                    <th>ID Bàn</th>
                    <th>Số bàn</th>
                    <th>Khu vực</th>
                    <th>Sức chứa</th>
                    <th>Trạng thái</th>
                    <th>Tên khách hàng</th>
                    <th>Quản lí</th>
                </tr>

                <?php 
                $sn = 1;
                while ($row = mysqli_fetch_assoc($res)) {
                    $table_number = $row['table_number'];
                    $area = $row['area'];
                    $capacity = isset($row['capacity']) ? $row['capacity'] : 'Chưa có khách hàng';  // Nếu không có dữ liệu, hiển thị 'N/A'
                    $status = $row['status'];
                    // Hiển thị tên khách hàng nếu bàn đã được đặt
                    $customer_name = ($status == 'Reserved') ? $row['customer_name'] : "Chưa có khách hàng"; 
                ?>
                    <tr>
                        <td><?php echo $sn++; ?>.</td>
                        <td><?php echo $table_number; ?></td>
                        <td><?php echo $area; ?></td>
                        <td><?php echo $capacity; ?></td>
                        <td><?php echo $status == 'Available' ? "<span style='color:green;'>Có sẵn</span>" : "<span style='color:red;'>Đã đặt</span>"; ?></td>
                        <td><?php echo $customer_name; ?></td>
                        <td>
    <?php if ($status == 'Available') { ?>
        <a href="reserve-table.php?table_number=<?php echo $table_number; ?>&area=<?php echo $area; ?>" class="button-5" role="button">Đặt bàn</a>
    <?php } else { ?>
        <button disabled class="button-6" role="button">Bàn đã được đặt</button>
    <?php } ?>

    <!-- Xóa bàn -->
    <a href="delete-table.php?table_number=<?php echo $table_number; ?>&area=<?php echo $area; ?>&delete=true" class="button-7" role="button" onclick="return confirm('Bạn có chắc chắn muốn xóa bàn này?');">Xóa bàn</a>
    
    <!-- Hủy đặt bàn -->
    <?php if ($status == 'Reserved') { ?>
        <a href="manage-table.php?table_number=<?php echo $table_number; ?>&area=<?php echo $area; ?>" class="button-7" role="button" onclick="return confirm('Bạn có chắc chắn muốn hủy đặt bàn?');">Hủy đặt bàn</a>
    <?php } else { ?>
        <button disabled class="btn btn-secondary"></button>
    <?php } ?>
</td>


                    </tr>
                <?php } ?>
            </table>
        </div>
    </main>
</section>

<script src="script-admin.js"></script>

</body>
</html>
