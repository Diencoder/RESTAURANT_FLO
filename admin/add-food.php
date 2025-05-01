<?php include('../frontend/config/constants.php');
	  //include('login-check.php');
	  error_reporting(0);
      @ini_set('display_errors', 0);
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
        <li><a href="manage-admin.php"><i class='bx bxs-group'></i><span class="text">Quản Lý Người Dùng</span></a></li>
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


		<main>
			<div class="head-title">
				<div class="left">
					<h1>Thêm Thực Đơn</h1>
					<ul class="breadcrumb">
						<li>
							<a href="index.php">Bảng Điều Khiển</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="manage-admin.php">Thêm Thực Đơn</a>
						</li>
					</ul>
				</div>
				
			</div>
            <br>

			   <?php 

            if(isset($_SESSION['upload']))
            {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }

        ?>

        <?php 
            if(isset($_SESSION['upload']))
            {
                echo $_SESSION['upload'];
                unset($_SESSION['upload']);
            }
        ?>
        <div class="table-data">
			<div class="order">
			<div class="head">

        <form action="" method="POST" enctype="multipart/form-data">
        
            <table class="rtable">

                <tr>
                    <td>Tiêu Đề</td>
                    <td>
                        <input type="text" name="title"id="ip2">
                    </td>
                </tr>

                <tr>
                    <td>Mô Tả</td>
                    <td>
                        <textarea name="description" cols="24" rows="5"></textarea>
                    </td>
                </tr>

                <tr>
                    <td>Giá</td>
                    <td>
                        <input type="number" name="price" id="ip2">
                    </td>
                </tr>

                <tr>
                    <td>Chọn Hình Ảnh</td>
                    <td>
                        <input type="file" name="image">
                    </td>
                </tr>

                <tr>
                    <td>Danh Mục</td>
                    <td>
                        <select name="category">

                            <?php 
                                //Tạo mã PHP để hiển thị các danh mục từ CSDL
                                //1. Tạo SQL để lấy tất cả danh mục hoạt động từ cơ sở dữ liệu
                                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                
                                //Thực thi truy vấn
                                $res = mysqli_query($conn, $sql);

                                //Đếm các dòng để kiểm tra xem chúng ta có danh mục không
                                $count = mysqli_num_rows($res);

                                //Nếu count lớn hơn 0, có danh mục, nếu không có thì không có danh mục
                                if($count>0)
                                {
                                    //Chúng ta có danh mục
                                    while($row=mysqli_fetch_assoc($res))
                                    {
                                        //lấy chi tiết của các danh mục
                                        $id = $row['id'];
                                        $title = $row['title'];

                                        ?>

                                        <option value="<?php echo $id; ?>"><?php echo $title; ?></option>

                                        <?php
                                    }
                                }
                                else
                                {
                                    //Không có danh mục
                                    ?>
                                    <option value="0">Không tìm thấy danh mục</option>
                                    <?php
                                }
                            

                                //2. Hiển thị trong Dropdown
                            ?>

                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Featured</td>
                    <td>
                        <input type="radio" name="featured" value="Yes"> Có 
                        <input type="radio" name="featured" value="No"> Không
                    </td>
                </tr>

                <tr>
                    <td>Kho Hàng</td>
                    <td>
                        <input type="number" name="stock" id="ip2">
                    </td>
                </tr>

                <tr>
                    <td>Hoạt Động</td>
                    <td>
                        <input type="radio" name="active" value="Yes"> Có 
                        <input type="radio" name="active" value="No"> Không
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Thêm Thực Đơn" class="button-8" role="button">
                    </td>
                </tr>

            </table>

        </form>
                            </div>
                            </div>
                            </div>


        <?php 
        
        //Kiểm tra xem nút submit có được nhấn không

        if(isset($_POST['submit']))
        {
            //Thêm thực đơn vào cơ sở dữ liệu
            //echo "Button Clicked";
            //1. Lấy dữ liệu từ form 
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category = $_POST['category'];

            //Kiểm tra xem nút radio "featured" có được chọn không
            if(isset($_POST['featured']))
            {
                $featured = $_POST['featured'];
            }
            else
            {
                $featured = "No"; // Gán giá trị mặc định
            }

            
            //Kiểm tra xem nút radio "active" có được chọn không
            if(isset($_POST['active']))
            {
                $active = $_POST['active'];
            }
            else
            {
                $active = "No"; // Gán giá trị mặc định
            }





            //2. Tải lên hình ảnh nếu được chọn

            //Kiểm tra xem hình ảnh có được chọn không và chỉ tải lên nếu hình ảnh được chọn
            if(isset($_FILES['image']['name']))
            {
                //Lấy chi tiết của hình ảnh được chọn
                $image_name = $_FILES['image']['name'];

                //Kiểm tra xem có hình ảnh (để tải lên) được chọn không

                if($image_name != "")
                {
                    //Hình ảnh (để tải lên) đã được chọn
                    //A. Đổi tên hình ảnh
                    //Lấy phần mở rộng của hình ảnh

                    $ext =end(explode('.', $image_name));

                    //Tạo tên mới cho hình ảnh

                    $image_name = "Food-Name-".rand(0000,9999).".".$ext; 


                    //B. Tải hình ảnh lên
                    //Lấy đường dẫn nguồn và đường dẫn đích
                    //Đường dẫn nguồn là vị trí hiện tại của hình ảnh
                    $src = $_FILES['image']['tmp_name'];

                    //Đường dẫn đích để tải hình ảnh lên

                    $dst = "../images/food/".$image_name;

                    //Cuối cùng tải hình ảnh lên
                    $upload = move_uploaded_file($src, $dst);

                    //Kiểm tra xem hình ảnh có được tải lên không

                    if($upload == false)
                    {
                        //Không thể tải lên hình ảnh
                        //Chuyển hướng đến trang thêm thực đơn với thông báo lỗi
                        $_SESSION['upload'] = "<div class='error text-center'>Không thể tải hình ảnh lên</div>";
                        header('location:'.SITEURL.'add-food.php');
                        //Dừng quá trình
                        die();
                    }
                }

            }
            else
            {
                $image_name = ""; // Gán giá trị mặc định là trống

            }



            //3. Chèn vào cơ sở dữ liệu

            //Tạo câu lệnh SQL
            $sql2 = "INSERT INTO tbl_food SET
            title = '$title',
            description = '$description',
            price = $price,
            image_name = '$image_name',
            category_id = $category,
            featured = '$featured',
            active = '$active'
            
            ";

            //Thực thi câu lệnh

            $res2 = mysqli_query($conn, $sql2);
            //4. Chuyển hướng với thông báo đến trang quản lý thực đơn
            //Kiểm tra xem dữ liệu có được chèn thành công không
            if($res2 == true)
            {
                //Dữ liệu đã được chèn thành công
                $_SESSION['add'] = "<div class='success text-center'>Thực Đơn Được Thêm Thành Công</div>";
                header('location:'.SITEURL.'manage-food.php');
            }
            else
            {
                //Không thể chèn dữ liệu
                $_SESSION['add'] = "<div class='error text-center'>Không thể thêm thực đơn</div>";
                header('location:'.SITEURL.'manage-food.php');
            }

            


        }
        
        
        ?>

