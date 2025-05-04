<?php include('config/constants.php'); ?> 

<?php 
date_default_timezone_set('Asia/Dhaka'); // Thiết lập múi giờ cho khu vực Dhaka
if(!isset($_SESSION['user'])) // Kiểm tra nếu người dùng chưa đăng nhập
{
    // Nếu người dùng chưa đăng nhập
    $_SESSION['no-login-message'] = "<div class='error'>Vui lòng đăng nhập để truy cập vào Bảng điều khiển Quản trị</div>";
    header('location:'.SITEURL.'login.php'); // Chuyển hướng đến trang đăng nhập
}

if(isset($_SESSION['user'])) // Kiểm tra nếu người dùng đã đăng nhập
{
    $username = $_SESSION['user']; // Lấy tên người dùng từ session

    // Truy vấn để lấy thông tin người dùng từ cơ sở dữ liệu
    $fetch_user = "SELECT * FROM tbl_users WHERE username = '$username'";

    // Thực thi truy vấn
    $res_fetch_user = mysqli_query($conn, $fetch_user);

    // Lặp qua kết quả truy vấn và lấy thông tin người dùng
    while($rows = mysqli_fetch_assoc($res_fetch_user))
    {
        $id = $rows['id']; // ID người dùng
        $name = $rows['name']; // Tên người dùng
        $email = $rows['email']; // Email người dùng
        $add1 = $rows['add1']; // Địa chỉ 1
        $city = $rows['city']; // Thành phố
        $phone = $rows['phone']; // Số điện thoại
        $username = $rows['username']; // Tên đăng nhập
        $password = $rows['password']; // Mật khẩu
    }
}
?>

<?php
           $payment_status_query = "UPDATE order_manager
                   SET payment_status = 'Thành công'
                   WHERE EXISTS ( SELECT NULL
                   FROM aamarpay
                   WHERE aamarpay.transaction_id = order_manager.transaction_id )";

                   $payment_status_query_result=mysqli_query($conn,$payment_status_query);

                   ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>FLO_RESTAURANT</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logo.png">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/my_account.css" rel="stylesheet">
    <link rel="stylesheet" href="path/to/vieworder.css">

   
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="spinner">
        <div class="spinner-circle"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar & Hero Start -->
    <header class="header">
        <nav class="navbar">
            <div class="container navbar-inner">
                <a href="<?php echo SITEURL; ?>" class="navbar-logo">
                    <img src="../images/logo.png" alt="Logo">
                </a>
                <button class="navbar-toggle">
                    <i class="fa fa-bars"></i>
                </button>
                <div class="navbar-menu">
                    <a href="index.php" class="navbar-item">Trang chủ</a>
                    <a href="about.php" class="navbar-item">Giới thiệu</a>
                    <a href="categories.php" class="navbar-item">Danh mục</a>
                    <a href="menu.php" class="navbar-item">Thực đơn</a>                  
                    <a href="contact.php" class="navbar-item">Liên hệ</a>
                    <?php
                    if (isset($_SESSION['user'])) {
                        $username = $_SESSION['user'];
                    ?>
                        <div class="navbar-dropdown">
                            <a href="#" class="navbar-dropdown-toggle navbar-item"><?php echo $username; ?></a>
                            <div class="navbar-dropdown-menu">
                                <a href="myaccount.php" class="navbar-dropdown-item">Tài khoản của tôi</a>
                                <a href="logout.php" class="navbar-dropdown-item">Đăng xuất</a>
                            </div>
                        </div>
                    <?php
                    } else {
                    ?>
                        <a href="login.php" class="navbar-item">Đăng nhập</a>
                    <?php
                    }
                    ?>
                    <?php
                    $count = 0;
                    if (isset($_SESSION['cart'])) {
                        $count = count($_SESSION['cart']);
                    }
                    ?>
                    <a href="mycart.php" class="navbar-cart">
                        <i class="fas fa-shopping-cart"></i> Giỏ hàng (<?php echo $count; ?>)
                    </a>
                </div>
            </div>
        </nav>
        <div class="hero-header">
            <div class="container">
                <h1 class="hero-title animated slideInDown">Tài khoản của tôi</h1>
                <nav class="breadcrumb">
                    <a href="index.php" class="breadcrumb-item">Trang chủ</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-item active">Tài khoản của tôi</span>
                </nav>
            </div>
        </div>
    </header>
    <!-- Navbar & Hero End -->

        <div class="container bootstrap snippets bootdey">
<div class="row">
  <div class="profile-nav col-md-3">
      <div class="panel">
          <div class="user-heading round">
              <a href="myaccount.php">
                  <img src="../images/avatar.png" alt="">
              </a>
              <h1><?php echo $name; ?></h1>
             
          </div>

          <ul class="nav nav-pills nav-stacked">
          <li class="nav-item mb-2">
                    <a href="update-account.php" class="nav-link d-flex align-items-center p-2 rounded shadow-sm">
                        <i class="fa fa-edit me-2"></i> Chỉnh Sửa Hồ Sơ
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="view-orders.php" class="nav-link d-flex align-items-center p-2 rounded shadow-sm">
                        <i class="fa fa-box me-2"></i> Xem Đơn Hàng
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="update-password.php" class="nav-link d-flex align-items-center p-2 rounded shadow-sm">
                        <i class="fa fa-lock me-2"></i> Đổi Mật Khẩu
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="view_table.php" class="nav-link d-flex align-items-center p-2 rounded shadow-sm">
                        <i class="fa fa-table"></i> Xem Bàn Đặt
                    </a>
                </li>
          </ul>
      </div>
  </div>
  <div class="profile-info col-md-9">
    <div class="panel">
        <div class="table-data">
            <div class="order">
                <div class="head">
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã Đơn Hàng</th>
                            <th>Tình Trạng Thanh Toán</th>
                            <th>Tình Trạng Đơn Hàng</th>
                            <th>Tổng Tiền</th>
                            <th>Thông Tin Sản Phẩm</th>
                            
                        </tr>
                    </thead>
                    <tbody>

                    <?php
// Lấy thông tin đơn hàng của người dùng
$order_query = "SELECT * FROM `order_manager` WHERE username = '$username' ORDER BY order_id DESC";
$order_result = mysqli_query($conn, $order_query);

if (!$order_result) {
    die('Error in query: ' . mysqli_error($conn)); 
}

while ($order_fetch = mysqli_fetch_assoc($order_result)) {
    $order_id = $order_fetch['order_id'];
    $payment_status = $order_fetch['payment_status'];
    $order_status = $order_fetch['order_status'];
    $total_amount = $order_fetch['total_amount'];

    // Lấy thông tin giảm giá (nếu có) cho mỗi đơn hàng riêng biệt
    $final_total = $total_amount; // Bắt đầu với tổng số tiền gốc
    if (isset($order_fetch['discount']) && $order_fetch['discount'] > 0) {
        // Áp dụng giảm giá cho đơn hàng này, nhưng không thay đổi giá của món ăn
        $final_total -= $order_fetch['discount']; // Áp dụng giảm giá cho tổng tiền của đơn hàng
    }

    echo "
    <tr>
        <td>{$order_id}</td>
        <td>";

    // Hiển thị tình trạng thanh toán
    if ($payment_status == "successful") {
        echo "<span class='badge bg-success'>{$payment_status}</span>";
    } elseif ($payment_status == "pending") {
        echo "<span class='badge bg-warning'>{$payment_status}</span>";
    } else {
        echo "<span class='badge bg-danger'>{$payment_status}</span>";
    }

    echo "</td><td>";

    // Hiển thị tình trạng đơn hàng
    if ($order_status == "completed") {
        echo "<span class='badge bg-success'>{$order_status}</span>";
    } elseif ($order_status == "pending") {
        echo "<span class='badge bg-warning'>{$order_status}</span>";
    } else {
        echo "<span class='badge bg-danger'>{$order_status}</span>";
    }

    echo "</td>
        <td class='total-price'>{$final_total} VND</td>";

    // Lấy thông tin chi tiết đơn hàng
    $order_details_query = "SELECT online_orders_new.*, tbl_food.id AS food_id, tbl_food.price AS food_price 
                            FROM `online_orders_new` 
                            JOIN tbl_food ON online_orders_new.item_name = tbl_food.title 
                            WHERE order_id = '{$order_fetch['order_id']}'";
    $order_details_result = mysqli_query($conn, $order_details_query);

    if (!$order_details_result) {
        die('Error in query: ' . mysqli_error($conn)); 
    }

    // Tạo cột thông tin sản phẩm
    echo "<td><table class='table'>
            <thead>
                <tr>
                    <th>ID Sản Phẩm</th>
                    <th>Tên Sản Phẩm</th>
                    <th>Giá</th>
                    <th>Số Lượng</th>
                </tr>
            </thead>
            <tbody>";

    while ($order_details = mysqli_fetch_assoc($order_details_result)) {
        echo "
        <tr>
            <td>{$order_details['food_id']}</td> <!-- Hiển thị ID sản phẩm từ bảng tbl_food -->
            <td>{$order_details['item_name']}</td>
            <td>{$order_details['food_price']} VND</td> <!-- Hiển thị giá của món ăn từ bảng tbl_food -->
            <td>{$order_details['quantity']}</td>
        </tr>";
    }

    echo "</tbody></table></td>"; // Cột sản phẩm đóng lại
}
?>


                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

				
			</div>
      </div>
      <div>
         
      </div>
  </div>
</div>
</div>


        <!-- Categories Start -->
        <div class="container">
            <div class="row">
               
                    
                
              
            </div>
        </div>

 
        <!-- Categories End  -->
        


        <?php include('footer.php'); ?>