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
    <link rel="icon" type="image/png" href="../images/logo.png">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/header.css" rel="stylesheet">

</head>
<body>
    <div class="container">
        <!-- Spinner Start -->
        <div id="spinner" class="spinner show">
            <div class="spinner-border">
                <span class="sr-only">Đang tải...</span>
            </div>
        </div>
        <!-- Spinner End -->

     <!-- Navbar Start -->
<nav class="navbar">
    <a href="<?php echo SITEURL; ?>" class="navbar-brand">
        <img src="../images/logo.png" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" onclick="toggleMenu()">
        <span class="fa fa-bars"></span>
    </button>
    <div class="navbar-menu" id="navbarMenu">
        <div class="navbar-nav">
            <a href="index.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF'], '.php') == 'index') ? 'active' : ''; ?>">Trang chủ</a>
            <a href="about.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF'], '.php') == 'about') ? 'active' : ''; ?>">Giới thiệu</a>
            <a href="categories.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF'], '.php') == 'categories') ? 'active' : ''; ?>">Danh mục</a>
            <a href="menu.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF'], '.php') == 'menu') ? 'active' : ''; ?>">Thực đơn</a>
            <a href="reservation_page.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF'], '.php') == 'reservation_page') ? 'active' : ''; ?>">Đặt bàn</a>
            <a href="contact.php" class="nav-item <?php echo (basename($_SERVER['PHP_SELF'], '.php') == 'contact') ? 'active' : ''; ?>">Liên hệ</a>
        </div>

        <?php
        if (isset($_SESSION['user'])) {
            $username = $_SESSION['user'];
        ?>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link" onclick="toggleDropdown(event)"><?php echo $username; ?></a>
                <div class="dropdown-menu">
                    <a href="myaccount.php" class="dropdown-item">Tài khoản của tôi</a>
                    <a href="logout.php" class="dropdown-item">Đăng xuất</a>
                </div>
            </div>
        <?php
        } else {
        ?>
            <a href="login.php" class="nav-item">Đăng nhập</a>
        <?php
        }
        ?>

        <?php
        $count = 0;
        if (isset($_SESSION['cart'])) {
            $count = count($_SESSION['cart']);
        }
        ?>
        <a href="mycart.php" class="btn-cart"><i class="fas fa-shopping-cart"></i> Giỏ hàng <?php echo $count; ?></a>
    </div>
</nav>
<!-- Navbar End -->


        <!-- Hero Section Start -->
        <div class="hero-header">
            <div class="hero-content">
                <h1 class="hero-title">
                    <?php
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
                        default:
                            echo "Trang chủ";
                    }
                    ?>
                </h1>
                <nav class="breadcrumb">
                    <ol>
                        <li><a href="index.php">Trang chủ</a></li>
                        <?php
                        switch ($page_name) {
                            case 'about.php':
                                echo '<li class="active">Giới thiệu</li>';
                                break;
                            case 'categories.php':
                                echo '<li class="active">Danh mục</li>';
                                break;
                            case 'menu.php':
                                echo '<li class="active">Thực đơn</li>';
                                break;
                            case 'contact.php':
                                echo '<li class="active">Liên hệ</li>';
                                break;
                            case 'reservation_page.php':
                                echo '<li class="active">Đặt bàn</li>';
                                break;
                            case 'team.php':
                                
                        }
                        ?>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Hero Section End -->
    </div>

    <!-- JavaScript cho toggle menu và dropdown -->
    <script>
        function toggleMenu() {
            document.getElementById('navbarMenu').classList.toggle('active');
        }

        function toggleDropdown(event) {
            event.preventDefault();
            const dropdownMenu = event.target.nextElementSibling;
            dropdownMenu.classList.toggle('show');
        }

        // Ẩn dropdown khi click ra ngoài
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('.dropdown-menu');
            dropdowns.forEach(dropdown => {
                if (!dropdown.parentElement.contains(event.target)) {
                    dropdown.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>