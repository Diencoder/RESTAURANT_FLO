<?php include('config/constants.php'); ?> 

<?php 
date_default_timezone_set('Asia/Dhaka');
if (!isset($_SESSION['user'])) {
    $_SESSION['no-login-message'] = "<div class='error'>Vui lòng đăng nhập để truy cập vào Bảng điều khiển Quản trị</div>";
    header('location:'.SITEURL.'login.php');
}

if (isset($_SESSION['user'])) {
    $username = $_SESSION['user'];
    $fetch_user = "SELECT * FROM tbl_users WHERE username = '$username'";
    $res_fetch_user = mysqli_query($conn, $fetch_user);
    while ($rows = mysqli_fetch_assoc($res_fetch_user)) {
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

    <!-- Profile Section Start -->
    <section class="profile">
        <div class="container">
            <div class="profile-wrapper">
                <!-- Sidebar -->
                <aside class="profile-sidebar">
                    <img src="../images/avatar.png" alt="Avatar" class="profile-avatar">
                    <h2 class="profile-name"><?php echo $name; ?></h2>
                    <ul class="profile-menu">
                        <li class="profile-menu-item">
                            <a href="update-account.php">
                                <i class="fa fa-edit"></i> Chỉnh sửa hồ sơ
                            </a>
                        </li>
                        <li class="profile-menu-item">
                            <a href="view-orders.php">
                                <i class="fa fa-box"></i> Xem Đơn hàng
                            </a>
                        </li>
                        <li class="profile-menu-item">
                            <a href="update-password.php">
                                <i class="fa fa-lock"></i> Thay đổi mật khẩu
                            </a>
                        </li>
                        <li class="profile-menu-item">
                            <a href="view_table.php">
                                <i class="fa fa-table"></i> Xem Bàn đã đặt
                            </a>
                        </li>
                    </ul>
                </aside>
                <!-- Profile Info -->
                <div class="profile-info">
                    <h2 class="profile-info-title">Tài khoản của tôi</h2>
                    <div class="profile-details">
                        <div class="profile-detail-item">
                            <input type="text" value="Họ và tên: <?php echo $name; ?>" readonly>
                        </div>
                        <div class="profile-detail-item">
                            <input type="text" value="Email: <?php echo $email; ?>" readonly>
                        </div>
                        <div class="profile-detail-item">
                            <input type="text" value="Địa chỉ: <?php echo $add1; ?>" readonly>
                        </div>
                        <div class="profile-detail-item">
                            <input type="text" value="Thành phố: <?php echo $city; ?>" readonly>
                        </div>
                        <div class="profile-detail-item">
                            <input type="text" value="Số điện thoại: <?php echo $phone; ?>" readonly>
                        </div>
                        <div class="profile-detail-item">
                            <input type="text" value="Tên người dùng: <?php echo $username; ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Profile Section End -->

    <?php include('footer.php'); ?>

    <!-- JavaScript -->
    <script>
        const navbarToggle = document.querySelector('.navbar-toggle');
        const navbarMenu = document.querySelector('.navbar-menu');
        navbarToggle.addEventListener('click', () => {
            navbarMenu.classList.toggle('active');
        });

        const dropdownToggles = document.querySelectorAll('.navbar-dropdown-toggle');
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const dropdownMenu = toggle.nextElementSibling;
                dropdownMenu.classList.toggle('active');
            });
        });

        window.addEventListener('load', () => {
            document.getElementById('spinner').style.display = 'none';
        });
    </script>
</body>
</html>