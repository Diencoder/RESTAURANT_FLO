<?php 
// Bắt đầu session
session_start();

// Bao gồm tệp constants.php
include('../frontend/config/constants.php');

// Xóa tất cả các biến trong session
session_unset();

// Hủy session
session_destroy();

// Chuyển hướng về trang đăng nhập trong thư mục frontend
header('Location: http://localhost/flo_restaurant/admin/login.php');
exit;
?>
