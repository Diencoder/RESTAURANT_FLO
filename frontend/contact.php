<?php include('header.php'); ?>

<head>
<link rel="stylesheet" href="css/contact.css">

</head>



       <!-- Phần Liên Hệ Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <!-- Tiêu đề phần Liên Hệ -->
            <h5 class="section-title ff-secondary text-center text-primary fw-normal">Liên Hệ Với Chúng Tôi</h5>
            <h1 class="mb-5">Liên Hệ Để Giải Quyết Mọi Thắc Mắc</h1>
        </div>
        <div class="row g-4">
            <div class="col-12">
                <div class="row gy-4">
                    <!-- Phần Thông Tin Liên Hệ Đặt Chỗ -->
                    <div class="col-md-4">
                        <h5 class="section-title ff-secondary fw-normal text-start text-primary">Đặt Chỗ</h5>
                        <p><i class="fa fa-envelope-open text-primary me-2"></i>FLO@gmail.com</p>
                    </div>
                    <!-- Phần Thông Tin Liên Hệ Chung -->
                    <div class="col-md-4">
                        <h5 class="section-title ff-secondary fw-normal text-start text-primary">Chung</h5>
                        <p><i class="fa fa-envelope-open text-primary me-2"></i>FLO@gmail.com</p>
                    </div>
                    <!-- Phần Thông Tin Liên Hệ Kỹ Thuật -->
                    <div class="col-md-4">
                        <h5 class="section-title ff-secondary fw-normal text-start text-primary">Kỹ Thuật</h5>
                        <p><i class="fa fa-envelope-open text-primary me-2"></i>FLO@gmail.com</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 wow fadeIn" data-wow-delay="0.1s">
                <!-- Google Map -->
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d15674.173818186831!2d106.6401792!3d10.846207999999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1svi!2s!4v1741594299456!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
            <div class="col-md-6">
                <div class="wow fadeInUp" data-wow-delay="0.2s">
                    <!-- Mẫu Form Liên Hệ -->
                    <form action="message.php" method="POST">
                        <div class="row g-3">
                            <!-- Nhập Tên -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Tên Của Bạn" required> 
                                    <label for="name">Tên Của Bạn</label>
                                </div>
                            </div>
                            <!-- Nhập Số Điện Thoại -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="number" class="form-control" id="email" name="phone" placeholder="Số Điện Thoại" required>
                                    <label for="email">Số Điện Thoại</label>
                                </div>
                            </div>
                            <!-- Nhập Chủ Đề -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Chủ Đề" required>
                                    <label for="subject">Chủ Đề</label>
                                </div>
                            </div>
                            <!-- Nhập Tin Nhắn -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Để lại tin nhắn ở đây" id="message" name="message" style="height: 150px" required></textarea>
                                    <label for="message">Tin Nhắn</label>
                                </div>
                            </div>
                            <!-- Nút Gửi Tin Nhắn -->
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit" name="submit_message">Gửi Tin Nhắn</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Phần Liên Hệ End -->



        <?php include('footer.php'); ?>