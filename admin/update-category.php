<?php
ob_start(); // Bắt đầu output buffering

include('../frontend/config/constants.php');
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
                    WHERE order_status='Pending' OR order_status='Processing' ";

$res_online_order_notif = mysqli_query($conn, $online_order_notif);
$row_online_order_notif = mysqli_num_rows($res_online_order_notif);

$stock_notif = "SELECT stock FROM tbl_food
                WHERE stock < 50";

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
                <h1>Cập Nhật Danh Mục</h1>
                <ul class="breadcrumb">
                    <li><a href="index.php">Bảng Điều Khiển</a></li>
                    <li><i class='bx bx-chevron-right' ></i></li>
                    <li><a href="manage-category.php">Quản Lý Danh Mục</a></li>
                    <li><a class="active" href="update-category.php">Cập Nhật Danh Mục</a></li>
                </ul>
            </div>
        </div>

        <br/>

        <!-- Cập Nhật Danh Mục Form Bắt Đầu -->

        <?php 
        // Kiểm tra xem ID có được thiết lập hay không
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            // Truy vấn lấy dữ liệu danh mục
            $sql = "SELECT * FROM tbl_category WHERE id=$id";
            $res = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($res);

            if($count == 1) {
                $row = mysqli_fetch_assoc($res);
                $title = $row['title'];
                $current_image = $row['image_name'];
                $featured = $row['featured'];
                $active = $row['active'];
            } else {
                $_SESSION['no-category-found'] = "<div class='error'>Không tìm thấy danh mục.</div>";
                header('location:' . SITEURL . 'manage-category.php');
            }
        } else {
            header('location:' . SITEURL . 'manage-category.php');
        }

        ?>
        <div class="table-data">
            <div class="order">
                <div class="head">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <table>
                            <tr>
                                <td>Tiêu Đề: </td>
                                <td><input type="text" name="title" value="<?php echo $title; ?>" required></td>
                            </tr>
                            <tr>
                                <td>Hình Ảnh Hiện Tại: </td>
                                <td>
                                    <?php 
                                    if($current_image != "") {
                                        echo "<img src='" . SITEURL . "../images/category/$current_image' width='150px'>";
                                    } else {
                                        echo "<div class='error'>Chưa thêm hình ảnh.</div>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Hình Ảnh Mới: </td>
                                <td><input type="file" name="image"></td>
                            </tr>
                            <tr>
                                <td>Sản phẩm nổi bật: </td>
                                <td>
                                    <input type="radio" name="featured" value="Yes" <?php echo ($featured == "Yes") ? "checked" : ""; ?> required> Có 
                                    <input type="radio" name="featured" value="No" <?php echo ($featured == "No") ? "checked" : ""; ?> required> Không 
                                </td>
                            </tr>
                            <tr>
                                <td>Hoạt Động: </td>
                                <td>
                                    <input type="radio" name="active" value="Yes" <?php echo ($active == "Yes") ? "checked" : ""; ?> required> Có 
                                    <input type="radio" name="active" value="No" <?php echo ($active == "No") ? "checked" : ""; ?> required> Không 
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="submit" name="submit" value="Cập Nhật Danh Mục" class="button-8" role="button">                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </div>

        <!-- Xử lý cập nhật danh mục -->
        <?php 
        if(isset($_POST['submit'])) {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $current_image = $_POST['current_image'];
            $featured = $_POST['featured'];
            $active = $_POST['active'];

            // Xử lý hình ảnh mới
            if(isset($_FILES['image']['name'])) {
                $image_name = $_FILES['image']['name'];

                if($image_name != "") {
                    // Tự động đổi tên hình ảnh
                    $ext = end(explode('.', $image_name));
                    $image_name = "Food_Category_" . rand(000, 999) . '.' . $ext;

                    $source_path = $_FILES['image']['tmp_name'];
                    $destination_path = "../images/category/" . $image_name;

                    $upload = move_uploaded_file($source_path, $destination_path);

                    if($upload == false) {
                        $_SESSION['upload'] = "<div class='error'>Không thể tải hình ảnh lên. </div>";
                        header('location:' . SITEURL . 'manage-category.php');
                        die();
                    }

                    // Xóa hình ảnh cũ
                    if($current_image != "") {
                        $remove_path = "../images/category/" . $current_image;
                        $remove = unlink($remove_path);

                        if($remove == false) {
                            $_SESSION['failed-remove'] = "<div class='error'>Không thể xóa hình ảnh hiện tại.</div>";
                            header('location:' . SITEURL . 'manage-category.php');
                            die();
                        }
                    }
                } else {
                    $image_name = $current_image;
                }
            } else {
                $image_name = $current_image;
            }

            // Cập nhật vào cơ sở dữ liệu
            $sql2 = "UPDATE tbl_category SET 
                        title = '$title',
                        image_name = '$image_name',
                        featured = '$featured',
                        active = '$active' 
                        WHERE id=$id";

            $res2 = mysqli_query($conn, $sql2);

            if($res2 == true) {
                $_SESSION['update'] = "<div class='success'>Danh Mục Đã Được Cập Nhật Thành Công.</div>";
                header('location:' . SITEURL . 'manage-category.php');
            } else {
                $_SESSION['update'] = "<div class='error'>Cập Nhật Danh Mục Thất Bại.</div>";
                header('location:' . SITEURL . 'manage-category.php');
            }
        }
        ?>
    </main>
</section>

<script src="script-admin.js"></script>
</body>
</html>

<?php
ob_end_flush(); // Xả bộ đệm và gửi dữ liệu ra trình duyệt
?>
