<?php
// Bắt đầu session và kết nối cơ sở dữ liệu
session_start();
include('../frontend/config/constants.php');

// Kiểm tra quyền truy cập của người dùng
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ' . SITEURL . 'login.php');
    exit;
}

// Xử lý xóa mã giảm giá
if (isset($_GET['delete'])) {
    $promo_id = $_GET['delete'];
    $delete_query = "DELETE FROM tbl_promotions WHERE id='$promo_id'";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['delete'] = "<div class='alert alert-success'>Mã giảm giá đã được xóa thành công!</div>";
    } else {
        $_SESSION['delete_error'] = "<div class='alert alert-danger'>Có lỗi xảy ra khi xóa mã giảm giá. Vui lòng thử lại sau.</div>";
    }
    header("Location: manage-promotions.php");
    exit();
}

// Lấy tất cả mã giảm giá
$sql = "SELECT * FROM tbl_promotions ORDER BY valid_from DESC";
$result = mysqli_query($conn, $sql);
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
<?php 

            if(isset($_SESSION['add'])){
                echo $_SESSION['add'];
                unset($_SESSION['add']); //Xóa thông báo session
            }
            if(isset($_SESSION['delete'])){
                echo $_SESSION['delete'];
                unset($_SESSION['delete']);
            }
            if(isset($_SESSION['update'])){
                echo $_SESSION['update'];
                unset($_SESSION['update']);
            }
            if(isset($_SESSION['user-not-found'])){
                echo $_SESSION['user-not-found'];
                unset($_SESSION['user-not-found']);
            }
            if(isset($_SESSION['pwd-not-match'])){
                echo $_SESSION['pwd-not-match'];
                unset($_SESSION['pwd-not-match']);
            }
            if(isset($_SESSION['change-pwd'])){
                echo $_SESSION['change-pwd'];
                unset($_SESSION['change-pwd']);
            }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
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
        <li><a href="index.php"><i class='bx bxs-dashboard'></i><span class="text">Bảng Điều Khiển</span></a></li>
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage-admin.php' ? 'active' : ''; ?>"><a href="manage-admin.php"><i class='bx bxs-group'></i><span class="text">Quản Lý Người Dùng</span></a></li>
        <li><a href="manage-online-order.php"><i class='bx bxs-cart'></i><span class="text">Đơn Hàng Online&nbsp;</span>
            <?php if($row_online_order_notif > 0) { ?>
                <span class="num-ei"><?php echo $row_online_order_notif; ?></span>
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
        <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage-promotions.php' ? 'active' : ''; ?>"><a href="manage-promotions.php"><i class='bx bxs-gift'></i><span class="text">Mã Giảm Giá</span></a></li>
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
								<li><a href="inventory.php"><?php echo $row_stock_notif ?>&nbsp;Món hết hàng</li></a>
								<?php
							}
							else if($row_stock_notif == 1)
							{
								?>
								<li><a href="inventory.php"><?php echo $row_stock_notif ?>&nbsp;Món hết hàng</li></a>
								<?php
							}
							else
							{
								

							}
							if($row_ei_order_notif>0)
							{
								?>
								<li><a href="manage-online-order.php"><?php echo $row_online_order_notif ?>&nbsp;Đơn Hàng Online Mới</li></a>
								<?php

							}
							if($row_online_order_notif>0)
							{
								?>
								<li><a href="manage-ei-order.php"><?php echo $row_ei_order_notif ?>&nbsp;Đơn Hàng Ăn Tại Chỗ Mới</li></a>
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

    <!-- MAIN CONTENT -->
    <main class="container mt-5">
        <div class="head-title">
            <h1>Quản lý Mã Giảm Giá</h1>
            <ul class="breadcrumb">
                <li><a href="index.php">Bảng điều khiển</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="manage-promotions.php">Quản lý Mã Giảm Giá</a></li>
            </ul>
        </div>

        <!-- Thêm Mã Giảm Giá -->
        <a href="add-promotion.php" class="button-8 mb-3" role="button">Thêm Mã Giảm Giá</a>

        <!-- Thông báo thành công và lỗi -->
        <?php
        if (isset($_SESSION['delete'])) {
            echo $_SESSION['delete'];
            unset($_SESSION['delete']);
        }
        if (isset($_SESSION['delete_error'])) {
            echo $_SESSION['delete_error'];
            unset($_SESSION['delete_error']);
        }
        ?>

        <br>

        <!-- Table Quản lý mã giảm giá -->
        <div class="promotion-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>S.N.</th>
                        <th>Mã Giảm Giá</th>
                        <th>Mô Tả</th>
                        <th>Giảm Giá</th>
                        <th>Thời Gian</th>
                        <th>Trạng Thái</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sn = 1; // Khởi tạo biến $sn
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $row['promo_code']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo ($row['discount_percent'] ? $row['discount_percent'] . '%' : $row['discount_amount'] . ' VNĐ'); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['valid_from'])) . ' - ' . date('d/m/Y H:i', strtotime($row['valid_to'])); ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <a href="edit-promotion.php?id=<?php echo $row['id']; ?>" class="button-5" role="button">Sửa</a>
                                <a href="manage-promotions.php?delete=<?php echo $row['id']; ?>" class="button-7" role="button" onclick="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>
</section>

<script src="script-admin.js"></script>

</body>
</html>
