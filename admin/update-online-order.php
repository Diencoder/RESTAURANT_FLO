<?php include('../frontend/config/constants.php');
// Bắt đầu output buffering
ob_start();

// Bắt đầu session
session_start();
// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Nếu không phải admin hoặc chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: ' . SITEURL . 'login.php');
    exit;
}
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
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="style-admin.css">
	<link rel="icon" 
      type="image/png" 
      href="../images/logo.png">

	<title>Quản Trị FLO_RESTAURANT</title>
</head>
<body>

<section id="sidebar">
        <a href="index.php" class="brand">
            <img src="../images/logo.png" width="80px" alt="">
        </a>
        <ul class="side-menu top">
            <li><a href="index.php"><i class='bx bxs-dashboard'></i><span class="text">Bảng điều khiển</span></a></li>
            <li><a href="manage-admin.php"><i class='bx bxs-group'></i><span class="text">Quản lý Admin</span></a></li>
            <li class="active" ><a href="manage-online-order.php"><i class='bx bxs-cart'></i><span class="text">Đơn hàng Online&nbsp;</span></a></li>
            <li><a href="manage-ei-order.php"><i class='bx bx-qr-scan'></i><span class="text">Đơn hàng Ăn tại chỗ&nbsp;&nbsp;&nbsp;</span></a></li>
            <li ><a href="manage-table.php"><i class='bx bx-table'></i><span class="text">Quản lý Bàn&nbsp;&nbsp;&nbsp;</span></a></li>
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
								<li><a href="inventory.php"><?php echo $row_stock_notif ?>&nbsp;Món hàng hết hàng</li></a>
								<?php
							}
							else if($row_stock_notif == 1)
							{
								?>
								<li><a href="inventory.php"><?php echo $row_stock_notif ?>&nbsp;Món hàng hết hàng</li></a>
								<?php
							}
							else
							{
								
							}
							if($row_ei_order_notif>0)
							{
								?>
								<li><a href="manage-online-order.php"><?php echo $row_online_order_notif ?>&nbsp;Đơn hàng Online mới</li></a>
								<?php

							}
							if($row_online_order_notif>0)
							{
								?>
								<li><a href="manage-ei-order.php"><?php echo $row_ei_order_notif ?>&nbsp;Đơn hàng Ăn tại chỗ mới</li></a>
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
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Cập nhật Đơn hàng Online</h1>
					<ul class="breadcrumb">
						<li>
							<a href="index.php">Bảng điều khiển</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="manage-ei-order.php">Đơn hàng Ăn tại chỗ</a>
						</li>
					</ul>
				</div>
</div>

<br>

        <?php 
 
        $id=$_GET['id'];
        $sql="SELECT * FROM order_manager WHERE order_id=$id";
        $res=mysqli_query($conn, $sql);

        if($res == true)
        {
            $count = mysqli_num_rows($res);
            if($count==1)
            {
                $row=mysqli_fetch_assoc($res);

                $order_id = $row['order_id'];
                $cus_name = $row['cus_name'];
                $cus_email = $row['cus_email'];
                $cus_add1 = $row['cus_add1'];
                $cus_phone = $row['cus_phone'];
                $payment_status = $row['payment_status'];
                $order_status = $row['order_status'];
            }
            else
			{
                header('location:'.SITEURL.'manage-online-order.php');
            }
        }

        
        ?>
		<div class="table-data">
			<div class="order">
			<div class="head">

        <form action="" method="POST">


        <table class="rtable">
           
            <tr>
                <td>Tên khách hàng</td>
                <td>
                    <input type="text" name="cus_name" value="<?php echo $cus_name; ?>" id="ip2">
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td>
                    <input type="text" name="cus_email" value="<?php echo $cus_email; ?>" id="ip2">
                </td>
            </tr>
            <tr>
                <td>Địa chỉ</td>
                <td>
                    <input type="text" name="cus_add1" value="<?php echo $cus_add1; ?>" id="ip2">
                </td>
            </tr>

            <tr>
                <td>Số điện thoại</td>
                <td>
                    <input type="text" name="cus_phone" value="<?php echo $cus_phone; ?>" id="ip2">
                </td>
            </tr>
            <tr>
                <td>Trạng thái đơn hàng</td>
                <td>
                     <select name="order_status">
                        <option <?php if($order_status=="Pending"){ echo "selected";} ?> value="Pending">Chờ xử lý</option>
                        <option <?php if($order_status=="Processing"){ echo "selected";} ?> value="Processing">Đang xử lý</option>
                        <option <?php if($order_status=="Delivered"){ echo "selected";} ?> value="Delivered">Đã giao</option>
                        <option <?php if($order_status=="Cancelled"){ echo "selected";} ?> value="Cancelled">Đã hủy</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="submit" name="submit" value="Cập nhật" class="button-8" role="button">
                </td>
            </tr>

        </table>

        </form>
	</div>
    </div>
</div>
	</div>

<?php 

if(isset($_POST['submit']))
{


     $order_id = $_POST['order_id'];
     $cus_name = $_POST['cus_name'];
     $cus_email = $_POST['cus_email'];
     $cus_add1 = $_POST['cus_add1'];
     $cus_phone = $_POST['cus_phone'];
     $order_status = $_POST['order_status'];


     $sql = "UPDATE order_manager SET
     order_id = '$order_id',
     cus_name = '$cus_name',
     cus_email = '$cus_email',
     cus_add1 = '$cus_add1',
     cus_phone = '$cus_phone',
     order_status = '$order_status' 
     WHERE order_id='$order_id'
     ";

     $res = mysqli_query($conn, $sql);

     if($res == true){

         $_SESSION['update'] = "<div class='success'>Cập nhật đơn hàng thành công</div>";
         header('location:'.SITEURL.'manage-online-order.php');
     }

     else{
        $_SESSION['update'] = "<div class='error'>Cập nhật đơn hàng thất bại</div>";
         header('location:'.SITEURL.'manage-online-order.php');
         
     }

}
ob_end_flush();
?>

	
		</main>
	</section>
	<script src="script-admin.js"></script>
</body>
</html>
