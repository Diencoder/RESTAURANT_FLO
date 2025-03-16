<?php include('config/constants.php'); ?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>FLO_RESTAURANT</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <!-- Liên kết biểu tượng trang web (favicon) -->
    <link rel="icon" type="image/png" href="../images/logo.png">

    <!-- Google Web Fonts -->
    <!-- Liên kết các font chữ từ Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <!-- Liên kết thư viện icon Font Awesome và Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <!-- Liên kết các thư viện hỗ trợ animation, carousel, và thời gian -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <!-- Liên kết file CSS của Bootstrap để tùy chỉnh giao diện -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <!-- Liên kết file CSS chính của template -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <!-- Hiệu ứng tải trang (spinner) sẽ hiển thị trong quá trình tải trang -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Đang tải...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar & Hero Start -->
        <!-- Phần menu điều hướng (navbar) và phần hero header -->
        <div class="container-xxl position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 px-lg-5 py-3 py-lg-0">
                <!-- Logo của website -->
                <a href="<?php echo SITEURL; ?>" class="navbar-brand p-0">
                    <img src="../images/logo.png" alt="Logo">
                </a>
                <!-- Nút hamburger cho thiết bị di động -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <!-- Menu điều hướng -->
                <div class="collapse navbar-collapse" id="navbarCollapse">
    <div class="navbar-nav ms-auto py-0 pe-4">
        <a href="index.php" class="nav-item nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Trang chủ</a>
        <a href="about.php" class="nav-item nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php') ? 'active' : ''; ?>">Giới thiệu</a>
        <a href="categories.php" class="nav-item nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'categories.php') ? 'active' : ''; ?>">Danh mục</a>
        <a href="menu.php" class="nav-item nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'menu.php') ? 'active' : ''; ?>">Thực đơn</a>
        <a href="reservation_page.php" class="nav-item nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'reservation_page.php') ? 'active' : ''; ?>">Đặt bàn</a>
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Trang</a>
            <div class="dropdown-menu m-0">
                <a href="team.php" class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'team.php') ? 'active' : ''; ?>">Đội ngũ của chúng tôi</a>
                <a href="testimonial.php" class="dropdown-item <?php echo (basename($_SERVER['PHP_SELF']) == 'testimonial.php') ? 'active' : ''; ?>">Lời chứng thực</a>
            </div>
        </div>
        <a href="contact.php" class="nav-item nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>">Liên hệ</a>
    </div>

                    <?php
                        // Kiểm tra nếu người dùng đã đăng nhập
                        if(isset($_SESSION['user']))
	                    {
                            // Hiển thị tên người dùng và các tùy chọn My Account và Logout
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
                            // Nếu chưa đăng nhập, hiển thị tùy chọn Login
                            ?>
                            <a href="login.php" class="nav-item nav-link">Đăng nhập</a>
                            <?php
                        }
                    ?>

                    <?php
                        // Đếm số lượng sản phẩm trong giỏ hàng
                        $count = 0;
                        if (isset($_SESSION['cart'])) {
                            $count = count($_SESSION['cart']);
                        }
                    ?>
                    <!-- Hiển thị giỏ hàng với số lượng sản phẩm -->
                    <a href="mycart.php" class="btn btn-primary py-2 px-4"><i class="fas fa-shopping-cart"></i><span> Giỏ hàng <?php echo $count; ?></span></a>
                </div>
            </nav>

<!-- Hero Section -->
<div class="container-xxl py-5 bg-dark hero-header mb-1">
    <div class="container text-center my-3 pt-1 pb-1">
        <!-- Tiêu đề với hiệu ứng xuất hiện -->
        <h1 class="display-3 text-white mb-3 animated slideInDown">
            <?php
            // Đổi tiêu đề theo từng trang
            $page_name = basename($_SERVER['PHP_SELF']);
            switch ($page_name) {
                case 'about.php':
                    echo "Giới thiệu";
                    break;
                case 'categories.php':
                    echo "Danh mục";
                    break;
                case 'menu.php':
                    echo "Thực đơn";
                    break;
                case 'contact.php':
                    echo "Liên hệ";
                    break;
                case 'reservation_page.php':
                    echo "Đặt bàn";
                    break;
                case 'team.php':
                    echo "Đội ngũ của chúng tôi";
                    break;
                case 'testimonial.php':
                    echo "Lời chứng thực";
                    break;
                default:
                    echo "Trang chủ"; // Mặc định nếu không phải các trang đã chỉ định
            }
            ?>
        </h1>
        
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-uppercase">
                <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                
                <?php
                // Hiển thị breadcrumb tương ứng với từng trang
                switch ($page_name) {
                    case 'about.php':
                        echo '<li class="breadcrumb-item text-white active" aria-current="page">Giới thiệu</li>';
                        break;
                    case 'categories.php':
                        echo '<li class="breadcrumb-item text-white active" aria-current="page">Danh mục</li>';
                        break;
                    case 'menu.php':
                        echo '<li class="breadcrumb-item text-white active" aria-current="page">Thực đơn</li>';
                        break;
                    case 'contact.php':
                        echo '<li class="breadcrumb-item text-white active" aria-current="page">Liên hệ</li>';
                        break;
                    case 'reservation_page.php':
                        echo '<li class="breadcrumb-item text-white active" aria-current="page">Đặt bàn</li>';
                        break;
                    case 'team.php':
                        echo '<li class="breadcrumb-item text-white active" aria-current="page">Đội ngũ của chúng tôi</li>';
                        break;
                    case 'testimonial.php':
                        echo '<li class="breadcrumb-item text-white active" aria-current="page">Lời chứng thực</li>';
                        break;
                    default:
                        // Nếu không phải một trong các trang đã xác định, không hiển thị breadcrumb thêm.
                        break;
                }
                ?>
            </ol>
        </nav>
    </div>
</div>
