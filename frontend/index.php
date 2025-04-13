<?php include('header.php'); ?>

<head>
    <link href="css/style.css" rel="stylesheet">
    <link href="css/categories.css" rel="stylesheet">
    <link href="css/reservation_styles.css" rel="stylesheet">
    <link href="css/about.css" rel="stylesheet">
    <link href="css/menu.css" rel="stylesheet">
    <link href="css/testimonials.css" rel="stylesheet">
</head>

<<!-- About Start -->
<section class="about">
    <div class="container">
        <div class="about-content">
            <div class="about-images">
                <div class="image-group">
                    <img class="image zoom-in" data-wow-delay="0.1s" src="../images/about-1.jpg" alt="About 1">
                    <img class="image zoom-in" data-wow-delay="0.3s" src="../images/about-2.jpg" alt="About 2" style="margin-top: 25%;">
                </div>
                <div class="image-group">
                    <img class="image zoom-in" data-wow-delay="0.5s" src="../images/about-3.jpg" alt="About 3">
                    <img class="image zoom-in" data-wow-delay="0.7s" src="../images/about-4.jpg" alt="About 4">
                </div>
            </div>
            <div class="about-text">
                <h5 class="section-title">Giới Thiệu Về Chúng Tôi</h5>
                <h1>Chào Mừng Đến Với <i class="fa fa-utensils"></i> FLO_RESTAURANT</h1>
                <p>FLO_RESTAURANT bắt đầu hành trình của mình từ năm 2017 và đã trở thành một trong những nhà hàng nổi bật trong thành phố. Ban đầu phục vụ thức ăn nhanh tại Dhaka, chúng tôi chỉ sử dụng nguyên liệu chất lượng cao để chuẩn bị món ăn cho khách hàng quý giá. Chất lượng không bao giờ bị đánh đổi.</p>
                <p>Chúng tôi phục vụ khách hàng với những món ăn chất lượng cao và mang đến trải nghiệm ẩm thực đẳng cấp khi thưởng thức. Mục tiêu của chúng tôi là trở thành một trong những nhà hàng được đánh giá cao nhất quốc gia, phục vụ những thực khách sành ăn.</p>
                <div class="stats">
                    <div class="stat">
                        <h1 class="stat-number" data-count="8">8</h1>
                        <div>
                            <p>Năm</p>
                            <h6>Kinh Nghiệm</h6>
                        </div>
                    </div>
                    <div class="stat">
                        <h1 class="stat-number" data-count="10">10</h1>
                        <div>
                            <p>Đầu Bếp</p>
                            <h6>Nổi Tiếng</h6>
                        </div>
                    </div>
                </div>
                <a class="button" href="">Đọc Thêm</a>
            </div>
        </div>
    </div>
</section>
<!-- About End -->

<!-- Menu Start -->
<section class="menu">
    <div class="container">
        <!-- Gợi Ý Món Ăn -->
        <h3 class="menu-title">Gợi Ý Món Ăn Cho Bạn</h3>
        <div id="suggested-menu" class="menu-list"></div>

        <!-- Món ăn trong menu -->
        <h3 class="menu-title">Menu</h3>
        <div class="menu-list">
            <?php
            // Hiển thị các món ăn từ bảng tbl_food
            $sql = "SELECT * FROM tbl_food WHERE active='Yes' LIMIT 4";
            $res = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($res);

            if ($count > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    // Lấy thông tin món ăn
                    $id = $row['id'];
                    $title = $row['title'];
                    $price = $row['price'];
                    $image_name = $row['image_name'];
            ?>
            <div class="menu-item">
                <img src="<?php echo SITEURL; ?>../images/food/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="menu-image">
                <div class="menu-content">
                    <h5 class="menu-item-title"><?php echo $title; ?></h5>
                    <p class="menu-item-price"><?php echo $price; ?> VND</p>
                    <form action="manage-cart.php" method="POST">
                        <button type="submit" name="Add_To_Cart" class="menu-button">Thêm vào giỏ hàng</button>
                        <input type="hidden" name="Item_Name" value="<?php echo $title; ?>">
                        <input type="hidden" name="Price" value="<?php echo $price; ?>">
                        <input type="hidden" name="Id" value="<?php echo $id; ?>">
                    </form>
                </div>
            </div>
            <?php
                }
            } else {
                echo '<p class="menu-empty">Không tìm thấy món ăn nào</p>';
            }
            ?>
        </div>
    </div>
</section>
<!-- Menu End -->
<!-- Featured Product End -->
<!-- Phần Sản Phẩm Nổi Bật Kết Thúc -->


       <!-- Testimonials Start -->
<section class="testimonials">
    <div class="container">
        <div class="testimonials-header">
            <h5 class="testimonials-sub-title">Đánh Giá</h5>
            <h1 class="testimonials-main-title">Khách Hàng Nói Gì Về Chúng Tôi!!!</h1>
        </div>
        <div class="testimonials-list">
            <!-- Đánh giá 1 -->
            <div class="testimonial-item">
                <i class="fa fa-quote-left testimonials-quote"></i>
                <p class="testimonial-text">Trước hết, tôi rất thích thiết kế nội thất của họ. Dịch vụ của họ rất tuyệt vời và ấn tượng. Tôi cũng rất thích món ăn của họ.</p>
                <div class="testimonial-author">
                    <img src="../images/avatar1.jpeg" alt="Võ Hoài Nam" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h5 class="testimonial-name">Võ Hoài Nam</h5>
                        <p class="testimonial-role">Sinh Viên</p>
                    </div>
                </div>
            </div>
            <!-- Đánh giá 2 -->
            <div class="testimonial-item">
                <i class="fa fa-quote-left testimonials-quote"></i>
                <p class="testimonial-text">Tôi khá ấn tượng với ý tưởng độc đáo của họ. Xin gửi lời khen ngợi đến Robo Cafe và toàn bộ đội ngũ của họ.</p>
                <div class="testimonial-author">
                    <img src="../images/avatar1.jpeg" alt="Phan Văn Điền" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h5 class="testimonial-name">Phan Văn Điền</h5>
                        <p class="testimonial-role">Sinh Viên</p>
                    </div>
                </div>
            </div>
            <!-- Đánh giá 3 -->
            <div class="testimonial-item">
                <i class="fa fa-quote-left testimonials-quote"></i>
                <p class="testimonial-text">Môi trường rất tuyệt vời và họ cung cấp thức ăn ngon và bổ dưỡng. Tôi rất thích.</p>
                <div class="testimonial-author">
                    <img src="../images/avatar1.jpeg" alt="Nguyễn Ngọc Anh" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h5 class="testimonial-name">Nguyễn Ngọc Anh</h5>
                        <p class="testimonial-role">Sinh Viên</p>
                    </div>
                </div>
            </div>
            <!-- Đánh giá 4 -->
            <div class="testimonial-item">
                <i class="fa fa-quote-left testimonials-quote"></i>
                <p class="testimonial-text">WOW!! Ý tưởng độc đáo của FLO_RESTAURANT thật tuyệt vời. Chất lượng món ăn rất tốt, tiếp tục phát huy nhé.</p>
                <div class="testimonial-author">
                    <img src="../images/avatar1.jpeg" alt="Đào Anh Kiệt" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h5 class="testimonial-name">Đào Anh Kiệt</h5>
                        <p class="testimonial-role">Sinh Viên</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Testimonials End -->

        

<?php include('footer.php'); ?>