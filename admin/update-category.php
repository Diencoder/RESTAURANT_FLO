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

	<title>Quản Trị Robo Cafe</title>
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
            <li ><a href="manage-table.php"><i class='bx bx-table'></i><span class="text">Quản lý Bàn&nbsp;&nbsp;&nbsp;</span></a></li>
            <li class="active" ><a href="manage-category.php"><i class='bx bxs-category'></i><span class="text">Danh mục</span></a></li>
            <li><a href="manage-food.php"><i class='bx bxs-food-menu'></i><span class="text">Thực đơn</span></a></li>
            <li><a href="inventory.php"><i class='bx bxs-box'></i><span class="text">Kho</span></a></li>
        </ul>
        <ul class="side-menu">
            <li><a href="#"><i class='bx bxs-cog'></i><span class="text">Cài đặt</span></a></li>
            <li><a href="logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">Đăng xuất</span></a></li>
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
					<h1>Cập Nhật Danh Mục</h1>
					<ul class="breadcrumb">
						<li>
							<a href="index.php">Bảng Điều Khiển</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="" href="manage-category.php">Quản Lý Danh Mục</a>
						</li>
						<li>
							<a class="active" href="update-category.php.php">Cập Nhật Danh Mục</a>
						</li>
					</ul>
				</div>
				
			</div>
	
        <br/> 

        <!-- Cập Nhật Danh Mục Form Bắt Đầu -->

<?php 
        
            //Kiểm tra xem ID có được thiết lập hay không
            if(isset($_GET['id']))
            {
                //Lấy ID và tất cả các chi tiết khác
                //echo "Getting the Data";
                $id = $_GET['id'];
                //Tạo truy vấn SQL để lấy tất cả chi tiết khác
                $sql = "SELECT * FROM tbl_category WHERE id=$id";

                //Thực thi truy vấn
                $res = mysqli_query($conn, $sql);

                //Đếm số dòng để kiểm tra xem ID có hợp lệ hay không
                $count = mysqli_num_rows($res);

                if($count==1)
                {
                    //Lấy tất cả dữ liệu
                    $row = mysqli_fetch_assoc($res);
                    $title = $row['title'];
                    $current_image = $row['image_name'];
                    $featured = $row['featured'];
                    $active = $row['active'];
                }
                else
                {
                    //Chuyển hướng về quản lý danh mục với thông báo
                    $_SESSION['no-category-found'] = "<div class='error'>Không tìm thấy danh mục.</div>";
                    header('location:'.SITEURL.'manage-category.php');
                }

            }
            else
            {
                //Chuyển hướng về Quản Lý Danh Mục
                header('location:'.SITEURL.'manage-category.php');
            }
        
        ?>
        <div class="table-data">
			<div class="order">
			<div class="head">	

        <form action="" method="POST" enctype="multipart/form-data">

            <table class="">
                <tr>
                    <td>Tiêu Đề: </td>
                    <td>
                        <input type="text" name="title" value="<?php echo $title; ?>" id="ip2" required>
                    </td>
                </tr>

                <tr>
                    <td>Hình Ảnh Hiện Tại: </td>
                    <td>
                        <?php 
                            if($current_image != "")
                            {
                                //Hiển thị hình ảnh
                                ?>
                                <img src="<?php echo SITEURL; ?>../images/category/<?php echo $current_image; ?>" width="150px">
                                <?php
                            }
                            else
                            {
                                //Hiển thị thông báo
                                echo "<div class='error'>Chưa thêm hình ảnh.</div>";
                            }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>Hình Ảnh Mới: </td>
                    <td>
                        <input type="file" name="image" required>
                    </td>
                </tr>

                <tr>
                    <td>Featured: </td>
                    <td>
                        <input <?php if($featured=="Yes"){echo "checked";} ?> type="radio" name="featured" value="Yes" required> Có 

                        <input <?php if($featured=="No"){echo "checked";} ?> type="radio" name="featured" value="No" required> Không 
                    </td>
                </tr>

                <tr>
                    <td>Hoạt Động: </td>
                    <td>
                        <input <?php if($active=="Yes"){echo "checked";} ?> type="radio" name="active" value="Yes" required> Có 

                        <input <?php if($active=="No"){echo "checked";} ?> type="radio" name="active" value="No" required> Không 
                    </td>
                </tr>

                <tr>
                    <td>
                        <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Cập Nhật Danh Mục" class="button-8" role="button">
                    </td>
                </tr>

            </table>

        </form>

        <?php 
        
            if(isset($_POST['submit']))
            {
                //echo "Clicked";
                //1. Lấy tất cả giá trị từ form
                $id = $_POST['id'];
                $title = $_POST['title'];
                $current_image = $_POST['current_image'];
                $featured = $_POST['featured'];
                $active = $_POST['active'];

                //2. Cập nhật hình ảnh mới nếu có chọn
                //Kiểm tra xem có chọn hình ảnh hay không
                if(isset($_FILES['image']['name']))
                {
                    //Lấy thông tin hình ảnh
                    $image_name = $_FILES['image']['name'];

                    //Kiểm tra xem hình ảnh có tồn tại không
                    if($image_name != "")
                    {
                        //Có hình ảnh

                        //A. Tải lên hình ảnh mới

                        //Tự động đổi tên hình ảnh
                        //Lấy phần mở rộng của hình ảnh (jpg, png, gif, v.v.)
                        $ext = end(explode('.', $image_name));

                        //Đổi tên hình ảnh
                        $image_name = "Food_Category_".rand(000, 999).'.'.$ext; // e.g. Food_Category_834.jpg
                        

                        $source_path = $_FILES['image']['tmp_name'];

                        $destination_path = "../images/category/".$image_name;

                        //Cuối cùng tải lên hình ảnh
                        $upload = move_uploaded_file($source_path, $destination_path);

                        //Kiểm tra xem hình ảnh có tải lên thành công không
                        //Và nếu hình ảnh không tải lên được thì sẽ dừng và chuyển hướng với thông báo lỗi
                        if($upload==false)
                        {
                            //Hiển thị thông báo
                            $_SESSION['upload'] = "<div class='error'>Không thể tải hình ảnh lên. </div>";
                            //Chuyển hướng về trang quản lý danh mục
                            header('location:'.SITEURL.'manage-category.php');
                            //Dừng quá trình
                            die();
                        }

                        //B. Xóa hình ảnh hiện tại nếu có
                        if($current_image!="")
                        {
                            $remove_path = "../images/category/".$current_image;

                            $remove = unlink($remove_path);

                            //Kiểm tra xem hình ảnh có bị xóa không
                            //Nếu không xóa được hình ảnh thì hiển thị thông báo và dừng quá trình
                            if($remove==false)
                            {
                                //Không thể xóa hình ảnh
                                $_SESSION['failed-remove'] = "<div class='error'>Không thể xóa hình ảnh hiện tại.</div>";
                                header('location:'.SITEURL.'manage-category.php');
                                die();//Dừng quá trình
                            }
                        }
                        

                    }
                    else
                    {
                        $image_name = $current_image;
                    }
                }
                else
                {
                    $image_name = $current_image;
                }

                //3. Cập nhật vào cơ sở dữ liệu
                $sql2 = "UPDATE tbl_category SET 
                    title = '$title',
                    image_name = '$image_name',
                    featured = '$featured',
                    active = '$active' 
                    WHERE id=$id
                ";

                //Thực thi truy vấn
                $res2 = mysqli_query($conn, $sql2);

                //4. Chuyển hướng về quản lý danh mục với thông báo
                //Kiểm tra xem truy vấn có thành công không
                if($res2==true)
                {
                    //Danh mục đã được cập nhật
                    $_SESSION['update'] = "<div class='success'>Danh Mục Đã Được Cập Nhật Thành Công.</div>";
                    header('location:'.SITEURL.'manage-category.php');
                }
                else
                {
                    //Cập nhật danh mục thất bại
                    $_SESSION['update'] = "<div class='error'>Cập Nhật Danh Mục Thất Bại.</div>";
                    header('location:'.SITEURL.'manage-category.php');
                }

            }
        
        ?>
       

        <!-- Cập Nhật Danh Mục Form Kết Thúc -->

        



		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script-admin.js"></script>
</body>
</html>
