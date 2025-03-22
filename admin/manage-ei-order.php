<?php 
// Bắt đầu output buffering ngay từ đầu
ob_start();

// Bắt đầu session
session_start();
include('../frontend/config/constants.php'); ?>
<?php // Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
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
			<li><a href="manage-online-order.php"><i class='bx bxs-cart'></i><span class="text">Đơn Hàng Online&nbsp;</span>
				<?php if($row_online_order_notif > 0) { ?>
					<span class="num-ei"><?php echo $row_online_order_notif; ?></span>
				<?php } ?>
			</a></li>
			<li class="active" ><a href="manage-ei-order.php"><i class='bx bx-qr-scan'></i><span class="text">Đơn Hàng Ăn Tại Chỗ&nbsp;&nbsp;&nbsp;</span>
				<?php if($row_ei_order_notif > 0) { ?>
					<span class="num-ei"><?php echo $row_ei_order_notif; ?></span>
				<?php } ?>
			</a></li>
            <li ><a href="manage-table.php"><i class='bx bx-table'></i><span class="text">Quản Lý Bàn&nbsp;&nbsp;&nbsp;</span>
				<?php if($row_ei_order_notif > 0) { ?>
					<span class="num-ei"><?php echo $row_ei_order_notif; ?></span>
				<?php } ?>
			</a></li>
			<li><a href="manage-category.php"><i class='bx bxs-category'></i><span class="text">Danh Mục</span></a></li>
			<li><a href="manage-food.php"><i class='bx bxs-food-menu'></i><span class="text">Thực Đơn</span></a></li>
			<li><a href="inventory.php"><i class='bx bxs-box'></i><span class="text">Kho Hàng</span></a></li>
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
					<h1>Đơn Hàng Ăn Tại Chỗ</h1>
					<ul class="breadcrumb">
						<li>
							<a href="index.php">Bảng Điều Khiển</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="manage-online-order.php">Đơn Hàng Ăn Tại Chỗ</a>
						</li>
					</ul>
				</div>
		
</div>

<br>
				
			<div class="table-data">
			<div class="order">
			<div class="head">
</div>

				 <table class="">
                    <tr>
                        <th>S.N.</th>
                        <th>Mã Bàn</th>
                        <th>Số Tiền</th>
                        <th>Mã Giao Dịch</th>
                        <th>Ngày Đặt Hàng</th>
                        <th>Trạng Thái Thanh Toán</th>
						<th>Trạng Thái Đơn Hàng</th>
                        <th>Thao Tác</th>
                    </tr>

				<?php 
				//Lấy dữ liệu đơn hàng ăn tại chỗ từ cơ sở dữ liệu
$sql = "SELECT * FROM tbl_eipay ORDER BY id DESC";

//Thực thi truy vấn
$res = mysqli_query($conn, $sql);
//Đếm số hàng
$count = mysqli_num_rows($res);
$sn = 1; //Tạo số thứ tự và đặt giá trị ban đầu là 1
                        if($count>0)
                        {
                            //Đơn hàng có sẵn
                            while($row=mysqli_fetch_assoc($res))
                            {
                                //Lấy thông tin đơn hàng
                                $id = $row['id'];
                                $table_id = $row['table_id'];
                                $amount = $row['amount'];
                                $tran_id = $row['tran_id'];
                                $order_date = $row['order_date'];
                                $payment_status = $row['payment_status'];
								$order_status = $row['order_status'];
                                ?>

                                    <tr>
                                        <td><?php echo $sn++; ?>. </td>
                                        <td><?php echo $table_id; ?></td>
                                        <td><?php echo $amount; ?></td>
                                        <td><?php echo $tran_id; ?></td>
                                        <td><?php echo $order_date; ?></td>
                                        <td><span class="status process"><?php echo $payment_status; ?></span></td>
										<td>

										<?php 
										if($order_status=="Pending")
											{
											echo "<span class='status process'>$order_status</span>";
											}
											else if($order_status=="Processing")
											{
											echo "<span class='status pending'>$order_status</span>";
											}
											else if($order_status=="Delivered")
											{
											echo "<span class='status completed'>$order_status</span>";
											}
											else if($order_status=="Cancelled")
											{
											echo "<span class='status cancelled'>$order_status</span>";
											}
							
											?>

											
									
										</td>
                                        <td>
                                            <a href="<?php echo SITEURL; ?>update-ei-order.php?id=<?php echo $id; ?>" class="button-5" role="button">Cập Nhật</a>
                                            <a href="<?php echo SITEURL; ?>delete-ei-order.php?id=<?php echo $id; ?>" class="button-7" role="button">Xóa</a>
											
                                        </td>
                                    </tr>

                                <?php

                            }
                        }
                        else
                        {
                            //Đơn hàng không có sẵn
                            echo "<tr><td colspan='7' class='error'>Không Có Đơn Hàng</td></tr>";
                        }





?>




    </table>
				
			</div>
		
               
				</div>
				
			</div>

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
