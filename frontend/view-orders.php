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
    <link rel="icon" 
      type="image/png" 
      href="../images/logo.png">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="css/order-details.css">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Đang tải...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar & Hero Start -->
        <div class="container-xxl position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
                <a href="<?php echo SITEURL; ?>" class="navbar-brand p-0">
                    
                    <img src="../images/logo.png" alt="Logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0 pe-4">
                        <a href="index.php" class="nav-item nav-link">Trang Chủ</a>
                        <a href="about.php" class="nav-item nav-link">Giới Thiệu</a>
                        <a href="categories.php" class="nav-item nav-link">Danh Mục</a>
                        <a href="menu.php" class="nav-item nav-link">Thực Đơn</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Trang</a>
                            <div class="dropdown-menu m-0">
                             
                                <a href="team.php" class="dropdown-item">Nhóm Của Chúng Tôi</a>
                                <a href="testimonial.php" class="dropdown-item">Lời Chứng Thực</a>
                            </div>
                        </div>
                        <a href="contact.php " class="nav-item nav-link">Liên Hệ</a>
                    </div>

                    <?php
                        if(isset($_SESSION['user']))
	                    {
                            $username = $_SESSION['user'];
                            
                            ?>
                            <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><?php echo $username; ?></a>
                            <div class="dropdown-menu m-0">
                           <a href="myaccount.php" class="dropdown-item">Tài Khoản Của Tôi</a>
                            <a href="logout.php" class="dropdown-item">Đăng Xuất</a>
                        </div>
                        </div>
                            <?php
	                    }
                        else
                        {
                            ?>
                            <a href="login.php" class="nav-item nav-link">Đăng Nhập</a>
                            <?php
                            
                        }
                        ?>
                     <?php
                        $count=0;
                        if(isset($_SESSION['cart']))
                        {
                            $count=count($_SESSION['cart']);
                        }
                    
                    ?>
                    <a href="mycart.php" class="btn btn-primary py-2 px-4"><i class="fas fa-shopping-cart"></i><span> Giỏ Hàng <?php echo $count; ?></span></a>
                </div>
            </nav>

            <div class="container-xxl py-5 bg-dark hero-header mb-1">
                <div class="container text-center my-2 pt-4 pb-1">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">Đơn Hàng</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center text-uppercase">
                            <li class="breadcrumb-item"><a href="index.php">Trang Chủ</a></li>
                            <li class="breadcrumb-item"><a href="myaccount.php">Tài Khoản Của Tôi</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Đơn Hàng</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
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
        <table class="">
            <thead>
                <tr>
                    <th>Mã Đơn Hàng</th>
                    <th>Tình Trạng Thanh Toán</th>
                    <th>Tình Trạng Đơn Hàng</th>
                    <th>Tổng Tiền</th>
                    <th>Đặt Hàng</th>
                </tr>
            </thead>
            <tbody>

            <?php
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
        $final_total -= $order_fetch['discount']; // Áp dụng giảm giá cho đơn hàng này
    }

    echo "
    <tr>
        <td>{$order_id}</td>
        <td>";

    // Thêm lớp màu Bootstrap cho trạng thái thanh toán
    if ($payment_status == "successful") {
        echo "<span class='badge bg-success'>{$payment_status}</span>";
    } elseif ($payment_status == "pending") {
        echo "<span class='badge bg-warning'>{$payment_status}</span>";
    } else {
        echo "<span class='badge bg-danger'>{$payment_status}</span>";
    }

    echo "</td><td>";

    // Thêm lớp màu Bootstrap cho trạng thái đơn hàng
    if ($order_status == "completed") {
        echo "<span class='badge bg-success'>{$order_status}</span>";
    } elseif ($order_status == "pending") {
        echo "<span class='badge bg-warning'>{$order_status}</span>";
    } else {
        echo "<span class='badge bg-danger'>{$order_status}</span>";
    }

    echo "</td>
        <td class='total-price'>{$final_total} VND</td>
        <td>";

    // Lấy thông tin chi tiết đơn hàng
    $order_details_query = "SELECT * FROM `online_orders_new` WHERE order_id = '{$order_fetch['order_id']}'";
    $order_details_result = mysqli_query($conn, $order_details_query);

    if (!$order_details_result) {
        die('Error in query: ' . mysqli_error($conn)); 
    }

    echo "<table class='table table-hover'>
            <thead>
                <tr>
                    <th>Tên Sản Phẩm</th>
                    <th>Giá</th>
                    <th>Số Lượng</th>
                </tr>
            </thead>
            <tbody>";

    if (mysqli_num_rows($order_details_result) > 0) {
        while ($order_details = mysqli_fetch_assoc($order_details_result)) {
            echo "
            <tr>
                <td>{$order_details['item_name']}</td>
                <td>{$order_details['price']} VND</td>
                <td>{$order_details['quantity']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>Không có sản phẩm nào cho đơn hàng này.</td></tr>";
    }

    echo "</tbody></table></td></tr>";
}
?>




            </tbody>
        </table>
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
        

       <!-- Footer Start -->
<div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Công Ty</h4>
                <!-- Các liên kết về thông tin công ty -->
                <a class="btn btn-link" href="">Giới Thiệu</a>
                <a class="btn btn-link" href="">Liên Hệ</a>
                <a class="btn btn-link" href="">Đặt Bàn</a>
                <a class="btn btn-link" href="">Chính Sách Bảo Mật</a>
                <a class="btn btn-link" href="">Điều Khoản & Điều Kiện</a>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Liên Hệ</h4>
                <!-- Thông tin liên hệ -->
                <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Việt Nam, Bình Định</p>
                <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>113114115</p>
                <p class="mb-2"><i class="fa fa-envelope me-3"></i>FLO@gmail.com</p>
                <div class="d-flex pt-2">
                    <!-- Các biểu tượng mạng xã hội -->
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-outline-light btn-social" href="https://www.facebook.com/xi.van.184"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Giờ Mở Cửa</h4>
                <!-- Thông tin về giờ mở cửa -->
                <h5 class="text-light fw-normal">Thứ Hai - Thứ Bảy</h5>
                <p>09AM - 10PM</p>
                <h5 class="text-light fw-normal">Chủ Nhật</h5>
                <p>7AM - 12PM</p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4 class="section-title ff-secondary text-start text-primary fw-normal mb-4">Bản Tin</h4>
                <!-- Phần đăng ký nhận bản tin -->
                <p>Đăng ký để nhận thông tin và khuyến mãi mới nhất từ chúng tôi.</p>
                <div class="position-relative mx-auto" style="max-width: 400px;">
                    <input class="form-control border-primary w-100 py-3 ps-4 pe-5" type="text" placeholder="Email của bạn">
                    <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">Đăng Ký</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; <a class="border-bottom" href="#">FLO_RESTAURANT</a>, Bảo Lưu Tất Cả Quyền. 
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-menu">
                        <!-- Liên kết về các trang khác -->
                        <a href="">Trang Chủ</a>
                        <a href="">Cookies</a>
                        <a href="">Trợ Giúp</a>
                        <a href="">Câu Hỏi Thường Gặp</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- Back to Top -->
<!-- Nút quay lại đầu trang -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>

<!-- Thư viện JavaScript -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/counterup/counterup.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="lib/tempusdominus/js/moment.min.js"></script>
<script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
<script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- Mã JavaScript của Template -->
<script src="js/main.js"></script>
</body>

</html>