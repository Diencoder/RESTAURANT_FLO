<?php 
// Xác thực - Kiểm tra quyền truy cập
// Kiểm tra xem người dùng đã đăng nhập hay chưa
if(!isset($_SESSION['user'])) // Nếu phiên người dùng chưa được thiết lập
{
    // Người dùng chưa đăng nhập
    // Chuyển hướng tới trang đăng nhập và hiển thị thông báo

    $_SESSION['no-login-message'] = "<div class='error text-center'>Vui lòng đăng nhập để truy cập vào Bảng điều khiển Admin</div>";
    header('location:'.SITEURL.'login.php');
}
?>
