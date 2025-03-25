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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <!-- Vòng quay khi tải trang -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Đang tải...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar & Hero Start -->
        <!-- Thanh điều hướng & Phần giới thiệu -->
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
                        <!-- Các mục menu -->
                        <a href="index.php" class="nav-item nav-link active">Trang Chủ</a>
                        <a href="about.php" class="nav-item nav-link">Giới Thiệu</a>
                        <a href="categories.php" class="nav-item nav-link">Danh Mục</a>
                        <a href="reservation_page.php" class="nav-item nav-link">Đặt Bàn</a>
                        <a href="menu.php" class="nav-item nav-link">Thực Đơn</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Các Trang</a>
                            <div class="dropdown-menu m-0">
                                <a href="team.php" class="dropdown-item">Đội Ngũ</a>
                                <a href="testimonial.php" class="dropdown-item">Lời Khách Hàng</a>
                            </div>
                        </div>
                        <a href="contact.php" class="nav-item nav-link">Liên Hệ</a>
                        <?php
                        // Kiểm tra nếu người dùng đã đăng nhập
                        if(isset($_SESSION['user'])) {
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
                        } else {
                            // Nếu chưa đăng nhập, hiển thị nút Login
                        ?>
                            <a href="login.php" class="nav-item nav-link">Đăng Nhập</a>
                        <?php
                        }
                        ?>
                    </div>
                    <?php
                        // Kiểm tra số lượng sản phẩm trong giỏ hàng
                        $count=0;
                        if(isset($_SESSION['cart'])) {
                            $count=count($_SESSION['cart']);
                        }
                    ?>
                    <!-- Hiển thị giỏ hàng -->
                    <a href="mycart.php" class="btn btn-primary py-2 px-4"><i class="fas fa-shopping-cart"></i><span> Giỏ Hàng <?php echo $count; ?></span></a>
                </div>
            </nav>

            <div class="container-xxl py-5 bg-dark hero-header mb-5">
                <div class="container my-5 py-5">
                    <div class="row align-items-center g-5">
                        <div class="col-lg-6 text-center text-lg-start">
                            <!-- Phần tiêu đề giới thiệu -->
                            <h1 class="display-3 text-white animated slideInLeft">Tận Hưởng Bữa Ăn Ngon Miệng Của Chúng Tôi</h1>
                            <p class="text-white animated slideInLeft mb-4 pb-2"></p>
                            <a href="<?php echo SITEURL; ?>categories.php" class="btn btn-primary py-sm-3 px-sm-5 me-3 animated slideInLeft">Khám Phá Danh Mục</a>
                        </div>
                        <div class="col-lg-6 text-center text-lg-end overflow-hidden">
                            <!-- Hình ảnh minh họa cho phần giới thiệu -->
                            <img class="img-fluid" src="../images/hero.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->

<!-- About Start -->
<!-- Phần Giới Thiệu Bắt Đầu -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="row g-3">
                    <!-- Hình ảnh giới thiệu 1 -->
                    <div class="col-6 text-start">
                        <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.1s" src="../images/about-1.jpg">
                    </div>
                    <!-- Hình ảnh giới thiệu 2 -->
                    <div class="col-6 text-start">
                        <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.3s" src="../images/about-2.jpg" style="margin-top: 25%;">
                    </div>
                    <!-- Hình ảnh giới thiệu 3 -->
                    <div class="col-6 text-end">
                        <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.5s" src="../images/about-3.jpg">
                    </div>
                    <!-- Hình ảnh giới thiệu 4 -->
                    <div class="col-6 text-end">
                        <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.7s" src="../images/about-4.jpg">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- Tiêu đề phần giới thiệu -->
                <h5 class="section-title ff-secondary text-start text-primary fw-normal">Giới Thiệu Về Chúng Tôi</h5>
                <h1 class="mb-4">Chào Mừng Đến Với <i class="fa fa-utensils text-primary me-2"></i>FLO_RESTAURANT</h1>
                <p class="mb-4">FLO_RESTAURANT bắt đầu hành trình của mình từ năm 2017 và đã trở thành một trong những nhà hàng nổi bật trong thành phố. Ban đầu phục vụ thức ăn nhanh tại Dhaka, 
                    chúng tôi chỉ sử dụng nguyên liệu chất lượng cao để chuẩn bị món ăn cho khách hàng quý giá. Chất lượng không bao giờ bị đánh đổi.
                </p>
                <p class="mb-4">Chúng tôi phục vụ khách hàng với những món ăn chất lượng cao và mang đến trải nghiệm ẩm thực đẳng cấp khi thưởng thức. Mục tiêu của chúng tôi là trở thành một trong những nhà hàng được đánh giá cao nhất quốc gia, phục vụ những thực khách sành ăn.</p>
                <div class="row g-4 mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                            <!-- Số năm kinh nghiệm -->
                            <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up">8</h1>
                            <div class="ps-4">
                                <p class="mb-0">Năm</p>
                                <h6 class="text-uppercase mb-0">Kinh Nghiệm</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center border-start border-5 border-primary px-3">
                            <!-- Số lượng đầu bếp nổi tiếng -->
                            <h1 class="flex-shrink-0 display-5 text-primary mb-0" data-toggle="counter-up">10</h1>
                            <div class="ps-4">
                                <p class="mb-0">Đầu Bếp</p>
                                <h6 class="text-uppercase mb-0">Nổi Tiếng</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Nút Đọc Thêm -->
                <a class="btn btn-primary py-3 px-5 mt-2" href="">Đọc Thêm</a>
            </div>
        </div>
    </div>
</div>
<!-- About End -->
<!-- Phần Giới Thiệu Kết Thúc -->

<!-- Featured Product Start -->
<!-- Phần Sản Phẩm Nổi Bật Bắt Đầu -->
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="text-center">
            <!-- Tiêu đề phần sản phẩm nổi bật -->
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Sản Phẩm Nổi Bật</h5>
            <div class="row">
                <?php
                // Truy vấn sản phẩm nổi bật từ cơ sở dữ liệu
                $sql = "SELECT * FROM tbl_food WHERE featured='Yes' AND active='Yes'";
                $res = mysqli_query($conn, $sql);
                $count = mysqli_num_rows($res);

                // Kiểm tra nếu có sản phẩm nổi bật
                if($count>0)
                {
                    while($row=mysqli_fetch_assoc($res))
                    {
                        // Lấy thông tin sản phẩm
                        $id = $row['id'];
                        $title = $row['title'];
                        $image_name = $row['image_name'];
                        $price = $row['price'];

                        ?>
                <!-- Hiển thị từng sản phẩm -->
                <div class="col-lg-3">
                    <div class="card">
                        <!-- Hình ảnh sản phẩm -->
                        <img src="<?php echo SITEURL; ?>../images/food/<?php echo $image_name; ?>" class="card-img-top" alt="...">
                        <div class="card-body text-center">
                            <form action="manage-cart.php" method="POST">
                                <!-- Tên sản phẩm -->
                                <h5 class="card-title"><?php echo $title; ?></h5>
                                <!-- Giá sản phẩm -->
                                <p class="card-text"><?php echo $price; ?></p>
                                <!-- Nút thêm vào giỏ hàng -->
                                <button type="submit" name="Add_To_Cart" class="btn btn-primary btn-sm">Thêm Vào Giỏ Hàng</button>
                                <!-- Các giá trị ẩn cho sản phẩm -->
                                <input type="hidden" name="Item_Name" value="<?php echo $title; ?>">
                                <input type="hidden" name="Price" value="<?php echo $price; ?>">
                            </form>
                        </div>
                    </div>
                </div>

                <?php
                    }
                }
                else
                {
                    // Không có sản phẩm nổi bật
                    echo "Không tìm thấy sản phẩm nổi bật";
                }
                ?>

            </div>
        </div>
    </div>
</div>
<!-- Featured Product End -->
<!-- Phần Sản Phẩm Nổi Bật Kết Thúc -->


        <!-- Testimonial Start -->
<!-- Phần Đánh Giá Bắt Đầu -->
<div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
        <div class="text-center">
            <!-- Tiêu đề phần đánh giá -->
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Đánh Giá</h5>
            <h1 class="mb-5">Khách Hàng Nói Gì Về Chúng Tôi!!!</h1>
        </div>
        <div class="owl-carousel testimonial-carousel">
            <!-- Đánh giá 1 -->
            <div class="testimonial-item bg-transparent border rounded p-4">
                <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                <p>Trước hết, tôi rất thích thiết kế nội thất của họ. Dịch vụ của họ rất tuyệt vời và ấn tượng. Tôi cũng rất thích món ăn của họ.</p>
                <div class="d-flex align-items-center">
                    <img class="img-fluid flex-shrink-0 rounded-circle" src="../images/avatar1.jpeg" style="width: 50px; height: 50px;">
                    <div class="ps-3">
                        <h5 class="mb-1">Võ Hoài Nam</h5>
                        <small>Sinh Viên</small>
                    </div>
                </div>
            </div>
            <!-- Đánh giá 2 -->
            <div class="testimonial-item bg-transparent border rounded p-4">
                <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                <p>Tôi khá ấn tượng với ý tưởng độc đáo của họ. Xin gửi lời khen ngợi đến FLO_RESTAURANT và toàn bộ đội ngũ của họ.</p>
                <div class="d-flex align-items-center">
                    <img class="img-fluid flex-shrink-0 rounded-circle" src="../images/avatar1.jpeg" style="width: 50px; height: 50px;">
                    <div class="ps-3">
                        <h5 class="mb-1">Phan Văn Điền</h5>
                        <small>Sinh Viên</small>
                    </div>
                </div>
            </div>
            <!-- Đánh giá 3 -->
            <div class="testimonial-item bg-transparent border rounded p-4">
                <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                <p>Môi trường rất tuyệt vời và họ cung cấp thức ăn ngon và bổ dưỡng. Tôi rất thích.</p>
                <div class="d-flex align-items-center">
                    <img class="img-fluid flex-shrink-0 rounded-circle" src="../images/avatar1.jpeg" style="width: 50px; height: 50px;">
                    <div class="ps-3">
                        <h5 class="mb-1">Nguyễn Ngọc Anh</h5>
                        <small>Sinh Viên</small>
                    </div>
                </div>
            </div>
            <!-- Đánh giá 4 -->
            <div class="testimonial-item bg-transparent border rounded p-4">
                <i class="fa fa-quote-left fa-2x text-primary mb-3"></i>
                <p>WOW!! Ý tưởng độc đáo của FLO_RESTAURANT thật tuyệt vời. Chất lượng món ăn rất tốt, tiếp tục phát huy nhé.</p>
                <div class="d-flex align-items-center">
                    <img class="img-fluid flex-shrink-0 rounded-circle" src="../images/avatar1.jpeg" style="width: 50px; height: 50px;">
                    <div class="ps-3">
                        <h5 class="mb-1">Đào Anh Kiệt</h5>
                        <small>Sinh Viên</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Testimonial End -->
<!-- Phần Đánh Giá Kết Thúc -->

        

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
                    <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
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
