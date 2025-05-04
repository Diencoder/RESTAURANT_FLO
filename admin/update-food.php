<?php
ob_start(); // Bắt đầu output buffering

include('../frontend/config/constants.php');
error_reporting(0);
@ini_set('display_errors', 0);

// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Nếu không phải admin hoặc chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: ' . SITEURL . 'login.php');
    exit;
}

// Câu lệnh SQL để lấy món ăn từ cơ sở dữ liệu
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql2 = "SELECT * FROM tbl_food WHERE id=$id";
    $res2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($res2);
    
    // Các giá trị từ database
    $title = $row2['title'];
    $description = $row2['description'];
    $price = $row2['price'];
    $current_image = $row2['image_name'];
    $current_category = $row2['category_id'];
    $featured = $row2['featured'];
    $stock = $row2['stock'];
    $active = $row2['active'];
} else {
    // Chuyển hướng nếu không có ID
    header('location:'.SITEURL.'manage-food.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style-admin.css">
    <link rel="icon" type="image/png" href="../images/logo.png">
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
        <li><a href="manage-food.php"><i class='bx bxs-food-menu'></i><span class="text">Thực Đơn</span></a></li>
    </ul>
    <ul class="side-menu">
        <li><a href="logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">Đăng Xuất</span></a></li>
    </ul>
</section>
<!-- SIDEBAR -->

<!-- CONTENT -->
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
    </nav>

    <main>
        <div class="head-title">
            <div class="left">
                <h1>Cập nhật Món ăn</h1>
                <ul class="breadcrumb">
                    <li><a href="index.php">Bảng điều khiển</a></li>
                    <li><i class='bx bx-chevron-right' ></i></li>
                    <li><a class="" href="manage-food.php">Thực đơn</a></li>
                    <li><a class="active" href="manage-admin.php">Cập nhật</a></li>
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
                                <td><input type="text" name="title" value="<?php echo $title; ?>" id="ip2" required></td>
                            </tr>
                            <tr>
                                <td>Mô tả</td>
                                <td><textarea name="description" cols="30" rows="5"><?php echo $description; ?></textarea></td>
                            </tr>
                            <tr>
                                <td>Giá</td>
                                <td><input type="number" name="price" value="<?php echo $price; ?>" id="ip2" required></td>
                            </tr>
                            <tr>
                                <td>Ảnh hiện tại</td>
                                <td>
                                    <?php 
                                    if($current_image == "") {
                                        echo "<div class='error'>Không có ảnh.</div>";
                                    } else {
                                        echo "<img src='".SITEURL."../images/food/$current_image' width='150px'>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Chọn ảnh mới</td>
                                <td><input type="file" name="image"></td>
                            </tr>
                            <tr>
                                <td>Danh mục</td>
                                <td>
                                    <select name="category" required>
                                        <?php 
                                        $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                        $res = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($res) > 0) {
                                            while($row = mysqli_fetch_assoc($res)) {
                                                echo "<option value='".$row['id']."' ".($current_category == $row['id'] ? 'selected' : '').">".$row['title']."</option>";
                                            }
                                        } else {
                                            echo "<option value='0'>Không có danh mục.</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Ưu tiên</td>
                                <td>
                                    <input type="radio" name="featured" value="Yes" <?php echo ($featured == "Yes" ? "checked" : ""); ?>> Có
                                    <input type="radio" name="featured" value="No" <?php echo ($featured == "No" ? "checked" : ""); ?>> Không
                                </td>
                            </tr>
                            <tr>
                                <td>Số lượng</td>
                                <td><input type="number" name="stock" value="<?php echo $stock; ?>" id="ip2" required></td>
                            </tr>
                            <tr>
                                <td>Hoạt động</td>
                                <td>
                                    <input type="radio" name="active" value="Yes" <?php echo ($active == "Yes" ? "checked" : ""); ?>> Có 
                                    <input type="radio" name="active" value="No" <?php echo ($active == "No" ? "checked" : ""); ?>> Không
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
                    if(isset($_POST['submit'])) {
                        $id = $_POST['id'];
                        $title = $_POST['title'];
                        $description = $_POST['description'];
                        $price = $_POST['price'];
                        $current_image = $_POST['current_image'];
                        $category = $_POST['category'];
                        $featured = $_POST['featured'];
                        $stock = $_POST['stock'];
                        $active = $_POST['active'];

                        // Xử lý hình ảnh
                        if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                            $image_name = $_FILES['image']['name']; 
                            $ext = end(explode('.', $image_name)); 
                            $image_name = "Food-Name-".rand(0000, 9999).'.'.$ext; 

                            $src_path = $_FILES['image']['tmp_name']; 
                            $dest_path = "../images/food/".$image_name; 

                            $upload = move_uploaded_file($src_path, $dest_path);
                            if(!$upload) {
                                $_SESSION['upload'] = "<div class='error text-center'>Không thể tải ảnh lên mới.</div>";
                                header('location:'.SITEURL.'manage-food.php');
                                die();
                            }

                            // Xóa ảnh cũ
                            if($current_image != "") {
                                $remove_path = "../images/food/".$current_image;
                                $remove = unlink($remove_path);
                                if(!$remove) {
                                    $_SESSION['remove-failed'] = "<div class='error text-center'>Không thể xóa ảnh cũ.</div>";
                                    header('location:'.SITEURL.'manage-food.php');
                                    die();
                                }
                            }
                        } else {
                            $image_name = $current_image;
                        }

                        // Cập nhật vào CSDL
                        $sql3 = "UPDATE tbl_food SET 
                            title = '$title',
                            description = '$description',
                            price = $price,
                            image_name = '$image_name',
                            category_id = '$category',
                            featured = '$featured',
                            stock = '$stock',
                            active = '$active'
                            WHERE id = $id";
                        
                        $res3 = mysqli_query($conn, $sql3);
                        if($res3) {
                            $_SESSION['update'] = "<div class='success text-center'>Cập nhật món ăn thành công.</div>";
                            header('location:'.SITEURL.'manage-food.php');
                        } else {
                            $_SESSION['update'] = "<div class='error text-center'>Không thể cập nhật món ăn.</div>";
                            header('location:'.SITEURL.'manage-food.php');
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
</section>

<script src="script-admin.js"></script>
</body>
</html>

<?php
ob_end_flush(); // Kết thúc output buffering
?>
