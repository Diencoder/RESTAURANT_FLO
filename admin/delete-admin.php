<?php 
// Gọi file cấu hình hằng số
include('../frontend/config/constants.php');

// Bắt đầu session
session_start();

// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ' . SITEURL . 'login.php');
    exit;
}

// Lấy ID người dùng từ URL
$id = $_GET['id'];

// Câu lệnh xóa người dùng
$sql = "DELETE FROM tbl_users WHERE id = $id";

// Thực thi câu lệnh
$res = mysqli_query($conn, $sql);

// Kiểm tra kết quả
if ($res == true) {
    $_SESSION['delete'] = "<div class='success'>Xóa người dùng thành công.</div>";
    header('location:' . SITEURL . 'manage-admin.php');
} else {
    $_SESSION['delete'] = "<div class='error'>Xóa người dùng thất bại.</div>";
    header('location:' . SITEURL . 'manage-admin.php');
}
?>
