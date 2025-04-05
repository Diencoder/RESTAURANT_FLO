<?php include('../frontend/config/constants.php'); ?> 
<?php // Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Nếu không phải admin hoặc chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: ' . SITEURL . 'login.php');
    exit;
}?>
<?php
           $payment_status_query = "UPDATE order_manager
                   SET payment_status = 'successful'
                   WHERE EXISTS ( SELECT NULL
                   FROM aamarpay
                   WHERE aamarpay.transaction_id = order_manager.transaction_id )";

                   $payment_status_query_result=mysqli_query($conn,$payment_status_query);

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
	<!-- My CSS -->
	<link rel="stylesheet" href="style-admin.css">
	<link rel="stylesheet" href="manage-table.css">

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
        <li><a href="manage-admin.php"><i class='bx bxs-group'></i><span class="text">Quản Lý Admin</span></a></li>
        <li class="active"><a href="manage-online-order.php"><i class='bx bxs-cart'></i><span class="text">Đơn Hàng Online&nbsp;</span>
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

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Đơn Hàng Online</h1>
					<ul class="breadcrumb">
						<li>
							<a href="index.php">Bảng Điều Khiển</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="manage-online-order.php">Đơn Hàng Online</a>
						</li>
					</ul>
				</div>
				
			</div>
			<br/>

			<div class="table-data">
    <div class="order">
        <div class="head">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Mã Đơn Hàng</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Địa Chỉ</th>
                    <th>Điện Thoại</th>
                    <th>Trạng Thái Thanh Toán</th>
                    <th>Trạng Thái Đơn Hàng</th>
                    <th>Tổng Tiền</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>

            <?php

            // Truy vấn lấy đơn hàng
            $query = "SELECT * FROM `order_manager` ORDER BY order_id DESC";
            $user_result = mysqli_query($conn, $query);

            while ($user_fetch = mysqli_fetch_assoc($user_result)) {
                $order_id = $user_fetch['order_id'];
                $cus_name = $user_fetch['cus_name'];
                $cus_email = $user_fetch['cus_email'];
                $cus_add1 = $user_fetch['cus_add1'];
                $cus_phone = $user_fetch['cus_phone'];
                $payment_status = $user_fetch['payment_status'];
                $order_status = $user_fetch['order_status'];
                $total_amount = $user_fetch['total_amount'];

                // Hiển thị thông tin đơn hàng
                echo "
                <tr>
                    <td>{$order_id}</td>
                    <td>{$cus_name}</td>
                    <td>{$cus_email}</td>
                    <td>{$cus_add1}</td>
                    <td>{$cus_phone}</td>

                    <td>";
                    // Hiển thị trạng thái thanh toán
                    if ($payment_status == "successful") {
                        echo "<span class='status completed'>$payment_status</span>";
                    } else if ($payment_status == "Processing") {
                        echo "<span class='status pending'>$payment_status</span>";
                    }
                echo "</td>
                    <td>";
                    // Hiển thị trạng thái đơn hàng
                    if ($order_status == "Pending") {
                        echo "<span class='status process'>$order_status</span>";
                    } else if ($order_status == "Processing") {
                        echo "<span class='status pending'>$order_status</span>";
                    } else if ($order_status == "Delivered") {
                        echo "<span class='status completed'>$order_status</span>";
                    } else if ($order_status == "Cancelled") {
                        echo "<span class='status cancelled'>$order_status</span>";
                    }
                echo "</td>

                    <td>{$total_amount}</td>
                    <td>
                        <a href='" . SITEURL . "update-online-order.php?id={$order_id}' class='button-6' role='button'>Cập Nhật</a>
                    </td>
                </tr>";

                // Truy vấn chi tiết sản phẩm trong đơn hàng
                $order_query = "SELECT * FROM `online_orders_new` WHERE order_id = '{$user_fetch['order_id']}'";
                $order_result_details = mysqli_query($conn, $order_query);

                echo "<tr><td colspan='9'>
                        <table class='tbl-full'>
                            <thead>
                                <tr>
                                    <th>Tên Sản Phẩm</th>
                                    <th>Giá</th>
                                    <th>Số Lượng</th>
                                </tr>
                            </thead>
                            <tbody>";

                // Kiểm tra nếu có sản phẩm trong đơn hàng
                if (mysqli_num_rows($order_result_details) > 0) {
                    while ($order_details = mysqli_fetch_assoc($order_result_details)) {
                        // Kiểm tra sự tồn tại của các chỉ mục trong mảng
                        $item_name = isset($order_details['item_name']) ? $order_details['item_name'] : 'Không có tên';
                        $price = isset($order_details['price']) ? $order_details['price'] : '0.00';
                        $quantity = isset($order_details['quantity']) ? $order_details['quantity'] : '0';

                        // Hiển thị thông tin sản phẩm
                        echo "
                        <tr>
                            <td>{$item_name}</td>
                            <td>{$price}</td>
                            <td>{$quantity}</td>
                        </tr>";
                    }
                } else {
                    // Hiển thị thông báo nếu không có sản phẩm
                    echo "<tr><td colspan='3'>Không có sản phẩm cho đơn hàng này.</td></tr>";
                }

                echo "
                            </tbody>
                        </table>
                    </td></tr>";
            }

            ?>

            </tbody>
        </table>
    </div>
</div>

		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script-admin.js"></script>
</body>
</html>
