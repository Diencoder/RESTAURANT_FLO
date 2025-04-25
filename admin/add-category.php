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

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Thêm Danh Mục</h1>
					<ul class="breadcrumb">
						<li>
							<a href="index.php">Bảng Điều Khiển</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="" href="manage-category.php">Quản Lý Danh Mục</a>
						</li>
						<li>
							<a class="active" href="add-category.php.php">Thêm Danh Mục</a>
						</li>
					</ul>
				</div>
				
			</div>
		<?php
			if(isset($_SESSION['add']))
        {
            echo $_SESSION['add'];
            unset($_SESSION['add']);
        }
        if(isset($_SESSION['upload']))
        {
            echo $_SESSION['upload'];
            unset($_SESSION['upload']);
        }
        
        ?>
        <br/> 

        <!-- Add Category Form Start-->
        <div class="table-data">
			<div class="order">
			<div class="head">

        <form action="" method="POST" enctype="multipart/form-data">
            <table class="rtable">
                <tr>
                    <td>Tiêu Đề</td>
                    <td>
                        <input type="text" name="title" id="ip2" required>
                    </td>
                </tr>

                <tr>
                    <td>Chọn Hình Ảnh</td>
                    <td>
                        <input type="file" name="image" required>
                    </td>
                </tr>

                <tr>
                    <td>Sản phẩm nổi bật</td>
                    <td>
                        <input type="radio" name="featured" value="Yes" required> Có
                        <input type="radio" name="featured" value="No" required> Không
                    </td>
                </tr>
                <tr>
                    <td>Hoạt Động</td>
                    <td>
                        <input type="radio" name="active" value="Yes" required> Có
                        <input type="radio" name="active" value="No" required> Không

                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Thêm Danh Mục" class="button-8" role="button">  
                    </td>
                </tr>
            </table>
        </form>
    </div>
    </div>
    </div>

        <!-- Saving to database -->

        <?php 
        //Kiểm tra xem nút submit có được nhấn không
        if(isset($_POST['submit']))
        {
            //echo "Clicked";
            //1. Lấy giá trị từ form
            $title = $_POST['title'];

            //Đối với các nút radio, chúng ta cần kiểm tra xem nút có được chọn không
            if(isset($_POST['featured']))
            {
                //Lấy giá trị từ form
                $featured = $_POST['featured'];
            }
            else
            {
                //Gán giá trị mặc định
                $featured = "No";
            }

            if(isset($_POST['active']))
            {
                $active = $_POST['active'];
            }
            else
            {
                $active = "No";
            }

            //Kiểm tra xem có chọn hình ảnh không và gán tên hình ảnh tương ứng
            // print_r($_FILES['image']); //echo không hiển thị mảng, vì vậy sử dụng print_r để hiển thị mảng

            // die(); //Dừng mã
            if(isset($_FILES['image']['name']))
            {
            //Tải lên hình ảnh
            //Để tải hình ảnh lên, chúng ta cần tên hình ảnh, đường dẫn nguồn và đường dẫn đích
            $image_name = $_FILES['image']['name'];

            //Tải lên hình ảnh chỉ khi đã chọn hình ảnh
            if($image_name != "")
              {

                    //Đổi tên tự động cho hình ảnh
                    //Lấy phần mở rộng của hình ảnh

                    $ext = @end(explode('.',$image_name));
                     //Đổi tên hình ảnh

                    $image_name = "Food_Category_".rand(000, 99999).'.'.$ext;

                    $source_path = $_FILES['image']['tmp_name'];
                    $destination_path = "../images/category/".$image_name;

                    //Tải hình ảnh lên
                    $upload = move_uploaded_file($source_path, $destination_path);

                    //Kiểm tra xem hình ảnh có tải lên thành công không
                    //Nếu hình ảnh không tải lên được, chúng ta sẽ dừng quá trình và chuyển hướng với thông báo lỗi
                    if($upload == false)
                    {
                    //Hiển thị thông báo
                    $_SESSION['upload'] = "<div class='error text-center'>Không thể tải hình ảnh lên</div>";
                    //Chuyển hướng về trang thêm danh mục
                    header('location:'.SITEURL.'add-category.php');
                    //Dừng quá trình
                    die();
                    }
               }
            }
            else
            {
                //Không tải lên hình ảnh và gán giá trị image_name là rỗng
                $image_name="";
            }

            //2. Tạo câu lệnh SQL để thêm danh mục vào cơ sở dữ liệu
            $sql = "INSERT INTO tbl_category SET
                    title = '$title',
                    image_name = '$image_name',
                    featured = '$featured',
                    active = '$active'
            
            ";

            //3. Thực thi câu lệnh SQL và lưu vào cơ sở dữ liệu
            $res = mysqli_query($conn, $sql);

            //Kiểm tra xem câu lệnh có thực thi thành công không
            if($res == true)
            {
                //Câu lệnh thực thi thành công và danh mục đã được thêm
                $_SESSION['add'] = "<div class='success text-center'>Danh Mục Được Thêm Thành Công</div>";
                //Chuyển hướng về trang quản lý danh mục
                header('location:'.SITEURL.'manage-category.php');
            }
            else
            {
                //Thêm danh mục thất bại
                $_SESSION['add'] = "<div class='error text-center'>Không Thể Thêm Danh Mục</div>";
                //Chuyển hướng về trang thêm danh mục
                header('location:'.SITEURL.'add-category.php');
            }

        }
        
        
        
        ?>


        <!-- Add Category Form End -->

        

    </div>
</div>




		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script-admin.js"></script>
</body>
</html>
