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

<?php 
    //Kiểm tra xem id đã được thiết lập hay chưa
    if(isset($_GET['id']))
    {
        //Lấy tất cả các chi tiết
        $id = $_GET['id'];

        //SQL Query để Lấy thông tin Món ăn đã chọn
        $sql2 = "SELECT * FROM tbl_food WHERE id=$id";
        //thực thi Query
        $res2 = mysqli_query($conn, $sql2);

        //Lấy giá trị từ query đã thực thi
        $row2 = mysqli_fetch_assoc($res2);

        //Lấy các giá trị của món ăn đã chọn
        $title = $row2['title'];
        $description = $row2['description'];
        $price = $row2['price'];
        $current_image = $row2['image_name'];
        $current_category = $row2['category_id'];
        $featured = $row2['featured'];
        $stock = $row2['stock'];
        $active = $row2['active'];

    }
    else
    {
        //Chuyển hướng về trang Quản lý Món ăn
        header('location:'.SITEURL.'manage-food.php');
    }
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
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Cập nhật Món ăn</h1>
					<ul class="breadcrumb">
						<li>
							<a href="index.php">Bảng điều khiển</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="" href="manage-food.php">Thực đơn</a>
						</li>
						<li>
							<a class="active" href="manage-admin.php">Cập nhật</a>
						</li>
					</ul>
				</div>
				
			</div>
            <div class="table-data">
			<div class="order">
			<div class="head">	

			<form action="" method="POST" enctype="multipart/form-data">
        
        <table class="">


            <tr>
                <td>Tiêu đề</td>
                <td>
                    <input type="text" name="title" value="<?php echo $title; ?>" id="ip2">
                </td>
            </tr>

            <tr>
                <td>Mô tả</td>
                <td>
                    <textarea name="description" cols="30" rows="5"><?php echo $description; ?></textarea id="ip2">
                </td>
            </tr>

            <tr>
                <td>Giá</td>
                <td>
                    <input type="number" name="price" value="<?php echo $price; ?>" id="ip2">
                </td>
            </tr>

            <tr>
                <td>Ảnh hiện tại</td>
                <td>
                    <?php 
                        if($current_image == "")
                        {
                            //Ảnh không có sẵn
                            echo "<div class='error'>Không có ảnh.</div>";
                        }
                        else
                        {
                            //Ảnh có sẵn
                            ?>
                            <img src="<?php echo SITEURL; ?>../images/food/<?php echo $current_image; ?>" width="150px">
                            <?php
                        }
                    ?>
                </td>
            </tr>

            <tr>
                <td>Chọn ảnh mới</td>
                <td>
                    <input type="file" name="image">
                </td>
            </tr>

            <tr>
                <td>Danh mục</td>
                <td>
                    <select name="category">

                        <?php 
                            //Truy vấn để lấy danh mục hoạt động
                            $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                            //Thực thi truy vấn
                            $res = mysqli_query($conn, $sql);
                            //Đếm số dòng
                            $count = mysqli_num_rows($res);

                            //Kiểm tra xem có danh mục hay không
                            if($count>0)
                            {
                                //Có danh mục
                                while($row=mysqli_fetch_assoc($res))
                                {
                                    $category_title = $row['title'];
                                    $category_id = $row['id'];
                                    
                                    //echo "<option value='$category_id'>$category_title</option>";
                                    ?>
                                    <option <?php if($current_category==$category_id){echo "selected";} ?> value="<?php echo $category_id; ?>"><?php echo $category_title; ?></option>
                                    <?php
                                }
                            }
                            else
                            {
                                //Không có danh mục
                                echo "<option value='0'>Không có danh mục.</option>";
                            }

                        ?>

                    </select>
                </td>
            </tr>

            <tr>
                <td>Ưu tiên</td>
                <td>
                    <input <?php if($featured=="Yes") {echo "checked";} ?> type="radio" name="featured" value="Yes"> Có 
                    <input <?php if($featured=="No") {echo "checked";} ?> type="radio" name="featured" value="No"> Không 
                </td>
            </tr>

            <tr>
                <td>Số lượng</td>
                <td>
                    <input type="number" name="stock" value="<?php echo $stock; ?>" id="ip2">
                </td>
            </tr>

            <tr>
                <td>Hoạt động</td>
                <td>
                    <input <?php if($active=="Yes") {echo "checked";} ?> type="radio" name="active" value="Yes"> Có 
                    <input <?php if($active=="No") {echo "checked";} ?> type="radio" name="active" value="No"> Không 
                </td>
            </tr>

            <tr>
                <td>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">

                    <input type="submit" name="submit" value="Cập nhật món ăn" class="button-8" role="button">
                </td>
            </tr>
        
        </table>
        
        </form>

        <?php 
        
            if(isset($_POST['submit']))
            {
                //Lấy tất cả các chi tiết từ form
                $id = $_POST['id'];
                $title = $_POST['title'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $current_image = $_POST['current_image'];
                $category = $_POST['category'];

                $featured = $_POST['featured'];
                $stock = $_POST['stock'];
                $active = $_POST['active'];

                //Kiểm tra việc tải lên ảnh
                if(isset($_FILES['image']['name']))
                {
                    $image_name = $_FILES['image']['name']; 

                    if($image_name!="")
                    {
                        //Ảnh có sẵn
                        $ext = end(explode('.', $image_name)); 

                        $image_name = "Food-Name-".rand(0000, 9999).'.'.$ext;

                        $src_path = $_FILES['image']['tmp_name']; 
                        $dest_path = "../images/food/".$image_name; 

                        //Tải ảnh lên
                        $upload = move_uploaded_file($src_path, $dest_path);

                        if($upload==false)
                        {
                            $_SESSION['upload'] = "<div class='error text-center'>Không thể tải ảnh lên mới.</div>";
                            header('location:'.SITEURL.'manage-food.php');
                            die();
                        }

                        //Xóa ảnh cũ nếu có
                        if($current_image!="")
                        {
                            $remove_path = "../images/food/".$current_image;

                            $remove = unlink($remove_path);

                            if($remove==false)
                            {
                                $_SESSION['remove-failed'] = "<div class='error text-center'>Không thể xóa ảnh cũ.</div>";
                                header('location:'.SITEURL.'manage-food.php');
                                die();
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

                //Cập nhật Món ăn trong Cơ sở dữ liệu
                $sql3 = "UPDATE tbl_food SET 
                    title = '$title',
                    description = '$description',
                    price = $price,
                    image_name = '$image_name',
                    category_id = '$category',
                    featured = '$featured',
                    stock = '$stock',
                    active = '$active'
                    WHERE id=$id
                ";

                $res3 = mysqli_query($conn, $sql3);

                if($res3==true)
                {
                    $_SESSION['update'] = "<div class='success text-center'>Cập nhật món ăn thành công.</div>";
                    header('location:'.SITEURL.'manage-food.php');
                }
                else
                {
                    $_SESSION['update'] = "<div class='error text-center'>Không thể cập nhật món ăn.</div>";
                    header('location:'.SITEURL.'manage-food.php');
                }

                
            }
        
        ?>

			


	
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	

	<script src="script-admin.js"></script>
</body>
</html>
