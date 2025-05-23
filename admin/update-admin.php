<?php include('../frontend/config/constants.php');
      //include('login-check.php');
// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Nếu không phải admin hoặc chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: ' . SITEURL . 'login.php');
    exit;
}
?>
<?php
$ei_order_notif = "SELECT order_status from tbl_eipay
					WHERE order_status='Pending' OR order_status='Processing'";

$res_ei_order_notif = mysqli_query($conn, $ei_order_notif);

$row_ei_order_notif = mysqli_num_rows($res_ei_order_notif);

$online_order_notif = "SELECT order_status from order_manager
					WHERE order_status='Pending'OR order_status='Processing' ";

$res_online_order_notif = mysqli_query($conn, $online_order_notif);

$row_online_order_notif = mysqli_num_rows($res_online_order_notif);

$stock_notif = "SELECT stock FROM tbl_food
				WHERE stock<50";

$res_stock_notif = mysqli_query($conn, $stock_notif);
$row_stock_notif = mysqli_num_rows($res_stock_notif);

//Thông báo tin nhắn
$message_notif = "SELECT message_status FROM message
				 WHERE message_status = 'unread'";
$res_message_notif = mysqli_query($conn, $message_notif);
$row_message_notif = mysqli_num_rows($res_message_notif);


?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- CSS của tôi -->
	<link rel="stylesheet" href="style-admin.css">
	<link rel="icon" 
      type="image/png" 
      href="../images/logo.png">

	<title>Quản Trị FLO_RESTAURANT</title>
</head>
<body>


	<!-- SIDEBAR -->
<section id="sidebar">
    <a href="index.php" class="brand">
        <img src="../images/logo.png" width="80px" alt="">
    </a>
    <ul class="side-menu top">
        <li class="active"><a href="index.php"><i class='bx bxs-dashboard'></i><span class="text">Bảng Điều Khiển</span></a></li>
        <li><a href="manage-admin.php"><i class='bx bxs-group'></i><span class="text">Quản Lý Admin</span></a></li>
        <li><a href="manage-online-order.php"><i class='bx bxs-cart'></i><span class="text">Đơn Hàng Online&nbsp;</span>
            <?php if($row_online_order_notif > 0) { ?>
                <span class="num-ei"><?php echo $row_online_order_notif; ?></span>
            <?php } ?>
        </a></li>
        <li><a href="manage-ei-order.php"><i class='bx bx-qr-scan'></i><span class="text">Đơn Hàng Ăn Tại Chỗ&nbsp;&nbsp;&nbsp;</span>
            <?php if($row_ei_order_notif > 0) { ?>
                <span class="num-ei"><?php echo $row_ei_order_notif; ?></span>
            <?php } ?>
        </a></li>
        <li><a href="manage-table.php"><i class='bx bx-table'></i><span class="text">Quản Lý Bàn&nbsp;&nbsp;&nbsp;</span>
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



	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
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
			<div class="fetch_message">
				<div class="action_message notfi_message">
					<a href="messages.php"><i class='bx bxs-envelope' ></i></a>
					<?php 

					if($row_message_notif>0)
					{
						?>
						<span class="num"><?php echo $row_message_notif; ?></span>
						<?php
					}
					else
					{
						?>
						<span class=""></span>
						<?php

					}
					?>
					
				</div>
					
			</div>
			<div class="notification" >
				<div class="action notif">
				<i class='bx bxs-bell' onclick= "menuToggle();"></i>
				<div class="notif_menu">
				<ul><?php 
							
							if($row_stock_notif>0 and $row_stock_notif !=1 )
							{
								?>
								<li><a href="inventory.php"><?php echo $row_stock_notif ?>&nbsp;Mặt hàng sắp hết</li></a>
								<?php
							}
							else if($row_stock_notif == 1)
							{
								?>
								<li><a href="inventory.php"><?php echo $row_stock_notif ?>&nbsp;Mặt hàng sắp hết</li></a>
								<?php
							}
							else
							{
								
							}
							if($row_ei_order_notif>0)
							{
								?>
								<li><a href="manage-online-order.php"><?php echo $row_online_order_notif ?>&nbsp;Đơn hàng online mới</li></a>
								<?php

							}
							if($row_online_order_notif>0)
							{
								?>
								<li><a href="manage-ei-order.php"><?php echo $row_ei_order_notif ?>&nbsp;Đơn hàng Eat In mới</li></a>
								<?php

							}
							?>
						
					</ul>
				</div>
				<?php 
				if($row_stock_notif>0 || $row_online_order_notif>0 || $row_ei_order_notif>0)
				{
					$total_notif = $row_online_order_notif+$row_ei_order_notif+$row_stock_notif;
					?>
					
					<span class="num"><?php echo $total_notif; ?></span>
					<?php
				}
				else
				{
					?>
					<span class=""></span>
					<?php
				}
				?>
			</a>
			</div>
			</div>
			
		</nav>
		<!-- NAVBAR -->

		<?php ob_start();
// 1. Lấy ID của Admin đã chọn
$id = $_GET['id'];

// 2. Tạo truy vấn SQL để lấy chi tiết từ bảng tbl_users
$sql = "SELECT * FROM tbl_users WHERE id = $id AND role = 'admin'";  // Chỉ lấy admin

// 3. Thực thi truy vấn
$res = mysqli_query($conn, $sql);

// Kiểm tra xem truy vấn có thành công không
if ($res == true) {
    // Kiểm tra xem có dữ liệu không
    $count = mysqli_num_rows($res);

    // Kiểm tra xem có dữ liệu admin không
    if ($count == 1) {
        // Lấy chi tiết
        $row = mysqli_fetch_assoc($res);

        $full_name = $row['name'];  // Lấy tên từ cột 'name'
        $username = $row['username'];
    } else {
        // Nếu không có dữ liệu admin, chuyển hướng về trang quản lý admin
        header('location:'.SITEURL.'manage-admin.php');
        exit();
    }
}

?>

<!-- Form Cập Nhật Admin -->
<div class="table-data">
    <div class="order">
        <div class="head">
            <h1>Cập Nhật Admin</h1>
        </div>

        <form action="" method="POST">
            <table class="rtable">
                <tr>
                    <td>Họ Tên</td>
                    <td>
                        <input type="text" name="full_name" value="<?php echo $full_name; ?>" id="ip2">
                    </td>
                </tr>
                <tr>
                    <td>Tên Đăng Nhập</td>
                    <td>
                        <input type="text" name="username" value="<?php echo $username; ?>" id="ip2">
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

<?php 
// Kiểm tra xem nút Submit có được nhấn hay không
if (isset($_POST['submit'])) {
    // Lấy tất cả giá trị từ form để cập nhật
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];

    // Tạo truy vấn SQL để cập nhật admin trong bảng tbl_users
    $sql = "UPDATE tbl_users SET
            name = '$full_name',  
            username = '$username' 
            WHERE id = '$id' AND role = 'admin'";  // Đảm bảo chỉ cập nhật admin

    // Thực thi truy vấn
    $res = mysqli_query($conn, $sql);

    // Kiểm tra xem truy vấn có thành công không
    if ($res == true) {
        // Truy vấn thành công và admin đã được cập nhật
        $_SESSION['update'] = "<div class='success'>Cập Nhật Admin Thành Công</div>";

        // Chuyển hướng về trang quản lý admin
        header('location:'.SITEURL.'manage-admin.php');
        exit();
    } else {
        // Cập nhật admin thất bại
        $_SESSION['update'] = "<div class='error'>Cập Nhật Admin Thất Bại</div>";

        // Chuyển hướng về trang quản lý admin
        header('location:'.SITEURL.'manage-admin.php');
        exit();
    }
}
ob_end_flush();
?>

		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script-admin.js"></script>
</body>
</html>
