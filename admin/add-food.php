<?php
ob_start(); // Bắt đầu output buffering để tránh lỗi header

include('../frontend/config/constants.php');
// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Nếu không phải admin hoặc chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: ' . SITEURL . 'login.php');
    exit;
}

$ei_order_notif = "SELECT order_status from tbl_eipay WHERE order_status='Pending' OR order_status='Processing'";
$res_ei_order_notif = mysqli_query($conn, $ei_order_notif);
$row_ei_order_notif = mysqli_num_rows($res_ei_order_notif);

$online_order_notif = "SELECT order_status from order_manager WHERE order_status='Pending' OR order_status='Processing'";
$res_online_order_notif = mysqli_query($conn, $online_order_notif);
$row_online_order_notif = mysqli_num_rows($res_online_order_notif);

$stock_notif = "SELECT stock FROM tbl_food WHERE stock<50";
$res_stock_notif = mysqli_query($conn, $stock_notif);
$row_stock_notif = mysqli_num_rows($res_stock_notif);

$message_notif = "SELECT message_status FROM message WHERE message_status = 'unread'";
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
    <nav>
        <i class='bx bx-menu' ></i>
        <a href="#" class="nav-link"></a>
        <form action="#">
            <div class="form-input">
                <input type="search" placeholder="Tìm kiếm...">
                <button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
            </div>
        </form>
        <div class="fetch_message">
            <div class="action_message notfi_message">
                <a href="messages.php"><i class='bx bxs-envelope' ></i></a>
                <?php if($row_message_notif>0) { ?>
                    <span class="num"><?php echo $row_message_notif; ?></span>
                <?php } ?>
            </div>
        </div>
        <div class="notification">
            <div class="action notif">
                <i class='bx bxs-bell' onclick= "menuToggle();"></i>
                <div class="notif_menu">
                    <ul>
                        <?php if($row_stock_notif > 0) { ?>
                            <li><a href="inventory.php"><?php echo $row_stock_notif ?>&nbsp;Mặt hàng sắp hết</a></li>
                        <?php } ?>
                        <?php if($row_ei_order_notif > 0) { ?>
                            <li><a href="manage-online-order.php"><?php echo $row_online_order_notif ?>&nbsp;Đơn hàng online mới</a></li>
                        <?php } ?>
                        <?php if($row_online_order_notif > 0) { ?>
                            <li><a href="manage-ei-order.php"><?php echo $row_ei_order_notif ?>&nbsp;Đơn hàng Eat In mới</a></li>
                        <?php } ?>
                    </ul>
                </div>
                <?php 
                if($row_stock_notif>0 || $row_online_order_notif>0 || $row_ei_order_notif>0)
                {
                    $total_notif = $row_online_order_notif + $row_ei_order_notif + $row_stock_notif;
                    echo "<span class='num'>$total_notif</span>";
                }
                ?>
            </div>
        </div>
    </nav>

    <main>
        <div class="head-title">
            <div class="left">
                <h1>Thêm Thực Đơn</h1>
                <ul class="breadcrumb">
                    <li><a href="index.php">Bảng Điều Khiển</a></li>
                    <li><i class='bx bx-chevron-right' ></i></li>
                    <li><a class="active" href="manage-food.php">Thêm Thực Đơn</a></li>
                </ul>
            </div>
        </div>
        <br>

        <?php 
        if(isset($_SESSION['upload'])) {
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
                                <td><input type="text" name="title" id="ip2" required></td>
                            </tr>
                            <tr>
                                <td>Mô Tả</td>
                                <td><textarea name="description" cols="24" rows="5" required></textarea></td>
                            </tr>
                            <tr>
                                <td>Giá</td>
                                <td><input type="number" name="price" id="ip2" required></td>
                            </tr>
                            <tr>
                                <td>Chọn Hình Ảnh</td>
                                <td><input type="file" name="image" required></td>
                            </tr>
                            <tr>
                                <td>Danh Mục</td>
                                <td>
                                    <select name="category" required>
                                        <?php 
                                        // Query danh mục
                                        $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                                        $res = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($res) > 0) {
                                            while($row = mysqli_fetch_assoc($res)) {
                                                echo "<option value='" . $row['id'] . "'>" . $row['title'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value='0'>Không tìm thấy danh mục</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Featured</td>
                                <td>
                                    <input type="radio" name="featured" value="Yes" required> Có 
                                    <input type="radio" name="featured" value="No" required> Không
                                </td>
                            </tr>
                            <tr>
                                <td>Kho Hàng</td>
                                <td><input type="number" name="stock" id="ip2" required></td>
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
                                    <input type="submit" name="submit" value="Thêm Thực Đơn" class="button-8" role="button">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>

        <?php 
        if(isset($_POST['submit'])) {
            // Lấy dữ liệu từ form
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category = $_POST['category'];

            $featured = isset($_POST['featured']) ? $_POST['featured'] : "No";
            $active = isset($_POST['active']) ? $_POST['active'] : "No";

            // Kiểm tra và tải hình ảnh
            $image_name = "";
            if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
                $image_name = "Food-Name-" . rand(0000, 9999) . "." . end(explode('.', $_FILES['image']['name']));
                $src = $_FILES['image']['tmp_name'];
                $dst = "../images/food/" . $image_name;
                $upload = move_uploaded_file($src, $dst);
                if($upload == false) {
                    $_SESSION['upload'] = "<div class='error text-center'>Không thể tải hình ảnh lên</div>";
                    header('location:' . SITEURL . 'add-food.php');
                    die();
                }
            }

            // Chèn dữ liệu vào CSDL
            $sql2 = "INSERT INTO tbl_food SET
                    title = '$title',
                    description = '$description',
                    price = $price,
                    image_name = '$image_name',
                    category_id = $category,
                    featured = '$featured',
                    active = '$active'";

            $res2 = mysqli_query($conn, $sql2);
            if($res2 == true) {
                $_SESSION['add'] = "<div class='success text-center'>Thực Đơn Được Thêm Thành Công</div>";
                header('location:' . SITEURL . 'manage-food.php');
            } else {
                $_SESSION['add'] = "<div class='error text-center'>Không thể thêm thực đơn</div>";
                header('location:' . SITEURL . 'manage-food.php');
            }
        }
        ?>
    </main>
</section>

<script src="script-admin.js"></script>
</body>
</html>

<?php
ob_end_flush(); // Kết thúc output buffering
?>
