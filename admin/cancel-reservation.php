<?php
// Bắt đầu session
session_start();
include('../frontend/config/constants.php');

// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ' . SITEURL . 'login.php');
    exit;
}

// Kiểm tra xem có tham số table_number và area không
if (isset($_GET['table_number']) && isset($_GET['area'])) {
    $table_number = $_GET['table_number'];
    $area = $_GET['area'];

    // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
    mysqli_begin_transaction($conn);

    try {
        // Xóa thông tin đặt bàn trong bảng tbl_reservations
        $delete_reservation = "DELETE FROM tbl_reservations 
                               WHERE table_number = '$table_number' AND area = '$area' AND status='Reserved'";

        // Cập nhật trạng thái bàn thành "Available" trong bảng tbl_tables
        $update_table = "UPDATE tbl_tables 
                         SET status='Available' 
                         WHERE table_number = '$table_number' AND area = '$area'";

        // Thực thi các câu lệnh
        $res_delete = mysqli_query($conn, $delete_reservation);
        $res_update = mysqli_query($conn, $update_table);

        // Kiểm tra kết quả
        if ($res_delete && $res_update) {
            // Commit transaction nếu tất cả thành công
            mysqli_commit($conn);

            $_SESSION['cancel'] = "<div class='success'>Hủy đặt bàn thành công!</div>";
            header("Location: manage-table.php");
            exit();
        } else {
            // Nếu có lỗi trong quá trình hủy đặt bàn, rollback transaction
            mysqli_roll_back($conn);

            $_SESSION['cancel'] = "<div class='error'>Lỗi trong quá trình hủy đặt bàn, vui lòng thử lại!</div>";
            header("Location: manage-table.php");
            exit();
        }
    } catch (Exception $e) {
        // Nếu có lỗi bất kỳ, rollback transaction
        mysqli_roll_back($conn);

        $_SESSION['cancel'] = "<div class='error'>Lỗi trong quá trình hủy đặt bàn, vui lòng thử lại!</div>";
        header("Location: manage-table.php");
        exit();
    }
} else {
    // Nếu không có tham số trong URL
    header("Location: manage-table.php");
    exit();
}
?>
