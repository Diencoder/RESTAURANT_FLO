<?php include('../frontend/config/constants.php'); 
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
        <li ><a href="index.php"><i class='bx bxs-dashboard'></i><span class="text">Bảng Điều Khiển</span></a></li>
        <li class="active" ><a href="manage-admin.php"><i class='bx bxs-group'></i><span class="text">Quản Lý Admin</span></a></li>
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
					<h1>Quản Lý Admin</h1>
					<ul class="breadcrumb">
						<li>
							<a href="index.php">Bảng Điều Khiển</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="manage-admin.php">Quản Lý Admin</a>
						</li>
					</ul>
				</div>
				
			</div>

			<!-- Table ---> 
			

			<div class="table-data">
				<div class="order">
					<div class="head">
						
						
					</div>
					<table>
						<thead>
							<tr>
								<th>Họ Tên</th>
								<th>Tên Đăng Nhập</th>
								<th>Thao Tác</th>
							</tr>
						</thead>

						<?php
                    
                        $sql = "SELECT * FROM tbl_users WHERE role = 'admin'";
						$res = mysqli_query($conn, $sql);

                        if($res == TRUE)
						{
						 $count = mysqli_num_rows($res); 

                            if($count>0)
							{
                               while($rows = mysqli_fetch_assoc($res))
							   {
								$id = $rows['id'];
                                $full_name = $rows['name'];
                                $username = $rows['username'];
                                ?> 
							<tbody>
								<tr>
									<td><?php echo $full_name; ?></td>
									<td><?php echo $username; ?></td>
									<td>
                            			<a href="<?php echo SITEURL; ?>update-password.php?id=<?php echo $id; ?>" class="button-5" role="button">Thay Đổi Mật Khẩu</a>
                            			<a href="<?php echo SITEURL; ?>update-admin.php?id=<?php echo $id; ?>" class="button-6" role="button">Cập Nhật</a>
                            			<a href="<?php echo SITEURL; ?>delete-admin.php?id=<?php echo $id; ?>" class="button-7" role="button" >Xóa</a>
										
                            	</td>
								</tr>

								<?php 

							   }
							}
						}

						?>
						
						</tbody>
					</table>
				</div>
				
			</div>




			<!-- Table ----> 


	
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script-admin.js"></script>


</body>
</html>
