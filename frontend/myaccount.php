<?php include('config/constants.php'); ?> 

<?php 
 date_default_timezone_set('Asia/Dhaka');
 if(!isset($_SESSION['user'])) //Nếu session người dùng chưa được thiết lập
{
    //Người dùng chưa đăng nhập
    //Chuyển hướng đến trang đăng nhập và thông báo

    $_SESSION['no-login-message'] = "<div class='error'>Vui lòng đăng nhập để truy cập vào Bảng điều khiển Quản trị</div>";
    header('location:'.SITEURL.'login.php');
}

    if(isset($_SESSION['user']))
    {
       $username = $_SESSION['user'];

       $fetch_user = "SELECT * FROM tbl_users WHERE username = '$username'";

       $res_fetch_user = mysqli_query($conn, $fetch_user);

       while($rows=mysqli_fetch_assoc($res_fetch_user))
       {
           $id = $rows['id'];
           $name = $rows['name'];
           $email = $rows['email'];
           $add1 = $rows['add1'];
           $city = $rows['city'];
           $phone = $rows['phone'];
           $username = $rows['username'];
           $password = $rows['password'];

       }
    }
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
    <link href="css/profile.css" rel="stylesheet">
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
                        <a href="index.php" class="nav-item nav-link">Trang chủ</a>
                        <a href="about.php" class="nav-item nav-link">Giới thiệu</a>
                        <a href="categories.php" class="nav-item nav-link">Danh mục</a>
                        <a href="menu.php" class="nav-item nav-link">Thực đơn</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Các trang</a>
                            <div class="dropdown-menu m-0">
                             
                                <a href="team.php" class="dropdown-item">Đội ngũ</a>
                                <a href="testimonial.php" class="dropdown-item">Lời chứng thực</a>
                            </div>
                        </div>
                        <a href="contact.php" class="nav-item nav-link">Liên hệ</a>
                    </div>

                    <?php
                        if(isset($_SESSION['user']))
	                    {
                            $username = $_SESSION['user'];
                            
                            ?>
                            <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><?php echo $username; ?></a>
                            <div class="dropdown-menu m-0">
                           <a href="myaccount.php" class="dropdown-item">Tài khoản của tôi</a>
                            <a href="logout.php" class="dropdown-item">Đăng xuất</a>
                        </div>
                        </div>
                            <?php
	                    }
                        else
                        {
                            ?>
                            <a href="login.php" class="nav-item nav-link">Đăng nhập</a>
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
                    <a href="mycart.php" class="btn btn-primary py-2 px-4"><i class="fas fa-shopping-cart"></i><span> Giỏ hàng <?php echo $count; ?></span></a>
                </div>
            </nav>
   <div class="container-xxl py-5 bg-dark hero-header mb-1">
                <div class="container text-center my-2 pt-4 pb-1">
                    <h1 class="display-3 text-white mb-3 animated slideInDown">Tài khoản của tôi</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center text-uppercase">
                            <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="Tài khoản của tôi"><a href="myaccount.php">Tài khoản của tôi</a></li>
                            
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->

        <div class="container py-5">
  <div class="row">
    <!-- Sidebar - Điều hướng -->
    <div class="profile-nav col-md-3">
        <div class="panel shadow-sm rounded">
            <div class="user-heading round text-center">
                <a href="myaccount.php">
                    <img src="../images/avatar.png" alt="Avatar" class="img-fluid rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                </a>
                <h1 class="h4"><?php echo $name; ?></h1>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="update-account.php" class="nav-link d-flex align-items-center p-2 rounded shadow-sm">
                        <i class="fa fa-edit me-2"></i> Chỉnh sửa hồ sơ
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="view-orders.php" class="nav-link d-flex align-items-center p-2 rounded shadow-sm">
                        <i class="fa fa-box me-2"></i> Xem Đơn hàng
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="update-password.php" class="nav-link d-flex align-items-center p-2 rounded shadow-sm">
                        <i class="fa fa-lock me-2"></i> Thay đổi mật khẩu
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="view_table.php" class="nav-link d-flex align-items-center p-2 rounded shadow-sm">
                        <i class="fa fa-table"></i> Xem Bàn đã đặt
                    </a>
                </li>
                
            </ul>
        </div>
    </div>

    <!-- Thông tin hồ sơ -->
    <div class="profile-info col-md-9">
        <div class="panel shadow-sm rounded">
            <div class="panel-body bio-graph-info">
                <h1 class="display-5 mb-4 text-primary">Tài khoản của tôi</h1>
                <div class="row">
                    <div class="bio-row mb-3">
                        <p><strong>Họ và tên:</strong> <?php echo $name; ?></p>
                    </div>
                    <div class="bio-row mb-3">
                        <p><strong>Email:</strong> <?php echo $email; ?></p>
                    </div>
                    <div class="bio-row mb-3">
                        <p><strong>Địa chỉ:</strong> <?php echo $add1; ?></p>
                    </div>
                    <div class="bio-row mb-3">
                        <p><strong>Thành phố:</strong> <?php echo $city; ?></p>
                    </div>
                    <div class="bio-row mb-3">
                        <p><strong>Số điện thoại:</strong> <?php echo $phone; ?></p>
                    </div>
                    <div class="bio-row mb-3">
                        <p><strong>Tên người dùng:</strong> <?php echo $username; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>



        <!-- Danh mục Bắt đầu -->
        <div class="container">
            <div class="row">
               
                    
                
              
            </div>
        </div>

 
        <!-- Danh mục Kết thúc  -->
        

        <?php include('footer.php'); ?>
