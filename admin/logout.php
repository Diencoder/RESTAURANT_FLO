<?php 
// Bắt đầu session
session_start();

// Bao gồm tệp constants.php
include('../frontend/config/constants.php');

// Xóa tất cả các biến trong session
session_unset();

// Hủy session
session_destroy();

// Chuyển hướng về trang đăng nhập
header('Location: ' . SITEURL . 'login.php');
exit;
?>
