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
							if($row_ei_order_notif>0)
							{
								?>
								<li><a href="manage-ei-order.php"><?php echo $row_ei_order_notif ?>&nbsp;đơn hàng EI mới</li></a>
								<?php

							}
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
					<h1>Đổi Mật Khẩu</h1>
					<ul class="breadcrumb">
						<li>
							<a href="index.php">Bảng Điều Khiển</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="manage-admin.php">Quản Lý Admin</a>
						</li>
                        <li>
							<a class="active" href="manage-admin.php">Đổi Mật Khẩu</a>
						</li>
					</ul>
				</div>
				
			</div>
             <?php 
            if(isset($_GET['id']))
            {
            $id = $_GET['id'];
            }
        
            ?>
            <div class="table-data">
			<div class="order">
			<div class="head">

            <form action="" method="POST">
            <table class="tbl-30">
                <tr>
                    <td>Mật khẩu hiện tại</td>
                    <td>
                        <input type="password" name="current_password" id="ip2">
                    </td>
                </tr>

                <tr>
                    <td>Mật khẩu mới</td>
                    <td>
                        <input type="password" name="new_password" id="ip2">
                    </td>

                </tr>

                <tr>
                    <td>Xác nhận mật khẩu</td>
                    <td>
                        <input type="password" name="confirm_password" id="ip2">
                    </td>

                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <input type="submit" name="submit" value="Đổi Mật Khẩu" class="button-8" role="button">
                    </td>
                </tr>

            </table>

        </form>

        </div>
        </div>
        </div>

        <?php 
// Kiểm tra xem nút submit có được nhấn hay không
if(isset($_POST['submit'])){
   
   // 1. Lấy dữ liệu từ form
   $id = $_POST['id'];
   $current_password = md5($_POST['current_password']);
   $new_password = md5($_POST['new_password']);
   $confirm_password = md5($_POST['confirm_password']);

   // 2. Kiểm tra xem người dùng với ID và mật khẩu hiện tại có tồn tại hay không (trong bảng tbl_users)
   $sql = "SELECT * FROM tbl_users WHERE id=$id AND password='$current_password' AND role='admin'"; // Chỉ kiểm tra người có role = 'admin'

   // Thực hiện truy vấn
   $res = mysqli_query($conn, $sql);

   if($res == true){
       // Kiểm tra xem có dữ liệu không
       $count = mysqli_num_rows($res);

       if($count == 1){
           // Người dùng tồn tại và có thể thay đổi mật khẩu

           // Kiểm tra xem mật khẩu mới và mật khẩu xác nhận có trùng khớp không
           if($new_password == $confirm_password){
               // Cập nhật mật khẩu
               $sql2 = "UPDATE tbl_users SET password = '$new_password' WHERE id=$id AND role='admin'";

               // Thực hiện truy vấn
               $res2 = mysqli_query($conn, $sql2);

               // Kiểm tra xem truy vấn có thành công không
               if($res2 == true){
                   $_SESSION['change-pwd'] = "<div class='success'>Mật khẩu đã được thay đổi thành công.</div>";
                   // Chuyển hướng người dùng
                   header('location:'.SITEURL.'manage-admin.php');
                   exit();
               } else {
                   // Hiển thị thông báo lỗi
                   $_SESSION['pwd-not-match'] = "<div class='error'>Không thể thay đổi mật khẩu. Vui lòng thử lại.</div>";
                   // Chuyển hướng người dùng
                   header('location:'.SITEURL.'manage-admin.php');
                   exit();
               }
           } else {
               $_SESSION['pwd-not-match'] = "<div class='error'>Mật khẩu không trùng khớp. Vui lòng thử lại.</div>";
               // Chuyển hướng người dùng
               header('location:'.SITEURL.'manage-admin.php');
               exit();
           }
       } else {
           // Người dùng không tồn tại hoặc không phải là admin. Thiết lập thông báo và chuyển hướng
           $_SESSION['user-not-found'] = "<div class='error'>Không tìm thấy người dùng</div>";
           // Chuyển hướng người dùng
           header('location:'.SITEURL.'manage-admin.php');
           exit();
       }
   }

}
?>











        </div>
			
        


			


	
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script-admin.js"></script>
</body>
</html>
