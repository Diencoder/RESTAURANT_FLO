<?php ob_start();
include('../frontend/config/constants.php'); ?>
<?php //include('login-check.php'); 
// // Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Nếu không phải admin hoặc chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: ' . SITEURL . 'login.php');
    exit;
}?>
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

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Cập Nhật Đơn Hàng </h1>
					<ul class="breadcrumb">
						<li>
							<a href="index.php">Bảng Điều Khiển</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="manage-ei-order.php">Đơn Hàng </a>
						</li>
					</ul>
				</div>
</div>

<br>

        			<?php 
 
        $id=$_GET['id'];
        $sql="SELECT * FROM tbl_eipay WHERE id=$id";
        $res=mysqli_query($conn, $sql);

        if($res == true)
        {
            $count = mysqli_num_rows($res);
            if($count==1)
            {
                $row=mysqli_fetch_assoc($res);

                $table_id = $row['table_id'];
                $amount = $row['amount'];
                $tran_id = $row['tran_id'];
                $order_date = $row['order_date'];
                $payment_status = $row['payment_status'];
                $order_status = $row['order_status'];
            }
            else{
                //Redirect to manage admin page
                header('location:'.SITEURL.'manage-ei-order.php');
            }
        }

        
        ?>
		<div class="table-data">
			<div class="order">
			<div class="head">

        <form action="" method="POST">


        <table class="rtable">
            <tr>
                <td>Table ID</td>
                <td>
                    <input type="text" name="table_id" value="<?php echo $table_id; ?>" id="ip2">
                </td>
            </tr>
            <tr>
                <td>Số Tiền</td>
                <td>
                    <input type="text" name="amount" value="<?php echo $amount; ?>" id="ip2">
                </td>
            </tr>
            <tr>
                <td>Transaction ID</td>
                <td>
                    <input type="text" name="tran_id" value="<?php echo $tran_id; ?>" id="ip2">
                </td>
            </tr>
            <tr>
                <td>Ngày Đặt Hàng</td>
                <td>
                    <input type="text" name="order_date" value="<?php echo $order_date; ?>" id="ip2">
                </td>
            </tr>

            <tr>
                <td>Trạng Thái Thanh Toán</td>
                <td>
                    <input type="text" name="payment_status" value="<?php echo $payment_status; ?>" id="ip2">
                </td>
            </tr>
            <tr>
                <td>Trạng Thái Đơn Hàng</td>
                <td>
                     <select name="order_status">
                        <option <?php if($order_status=="Pending"){ echo "selected";} ?> value="Pending">Pending</option>
                        <option <?php if($order_status=="Processing"){ echo "selected";} ?> value="Processing">Processing</option>
                        <option <?php if($order_status=="Delivered"){ echo "selected";} ?> value="Delivered">Delivered</option>
                        <option <?php if($order_status=="Cancelled"){ echo "selected";} ?> value="Cancelled">Cancelled</option>
                    </select>
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
</div>
	</div>

<?php 
//Kiểm tra xem nút Submit có được nhấn hay không

if(isset($_POST['submit'])){
    //echo "Button Clicked";
    //Lấy tất cả giá trị từ form để cập nhật

     $id = $_POST['id'];
     $table_id = $_POST['table_id'];
     $amount = $_POST['amount'];
     $tran_id = $_POST['tran_id'];
     $order_date = $_POST['order_date'];
     $payment_status = $_POST['payment_status'];
     $order_status = $_POST['order_status'];


     $sql = "UPDATE tbl_eipay SET
     table_id = '$table_id',
     amount = '$amount',
     tran_id = '$tran_id',
     order_date = '$order_date',
     payment_status = '$payment_status',
     order_status = '$order_status' 
     WHERE id='$id'
     ";

     $res = mysqli_query($conn, $sql);

     if($res == true){

         $_SESSION['update'] = "<div class='success'>Cập Nhật Đơn Hàng Thành Công</div>";
         header('location:'.SITEURL.'manage-ei-order.php');
     }

     else{
        $_SESSION['update'] = "<div class='error'>Cập Nhật Đơn Hàng Thất Bại</div>";
         header('location:'.SITEURL.'manage-ei-order.php');
         
     }

}
// Kết thúc bộ đệm đầu ra và gửi tất cả dữ liệu đã được lưu vào bộ đệm
ob_end_flush();
?>

	
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script-admin.js"></script>
</body>
</html>
