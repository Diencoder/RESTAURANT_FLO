<?php 
// Include kết nối CSDL và các cấu hình
include('../frontend/config/constants.php');

// Kiểm tra nếu có tham số table_number và area qua URL để xử lý việc xóa bàn
if (isset($_GET['table_number']) && isset($_GET['area']) && isset($_GET['delete']) && $_GET['delete'] == 'true') {
    $table_number = $_GET['table_number'];
    $area = $_GET['area'];

    // Kiểm tra trạng thái bàn trước khi xóa
    $check_status = "SELECT status FROM tbl_tables WHERE table_number='$table_number' AND area='$area'";
    $res_check = mysqli_query($conn, $check_status);
    $row_check = mysqli_fetch_assoc($res_check);

    if ($row_check['status'] == 'Available') {
        // Xóa bàn trong bảng tbl_tables
        $delete_table = "DELETE FROM tbl_tables WHERE table_number='$table_number' AND area='$area'";
        $res_delete = mysqli_query($conn, $delete_table);

        if ($res_delete) {
            // Cập nhật trạng thái bàn trong bảng tbl_reservations nếu có
            $update_reservation = "UPDATE tbl_reservations SET status='Cancelled' WHERE table_number='$table_number' AND area='$area' AND status='Reserved'";
            mysqli_query($conn, $update_reservation);

            // Thông báo thành công và chuyển hướng về trang quản lý bàn
            $_SESSION['delete'] = "<div class='success'>Bàn đã được xóa thành công.</div>";
            header("Location: manage-table.php");
            exit();
        } else {
            // Nếu có lỗi trong việc xóa bàn
            $_SESSION['delete'] = "<div class='error'>Không thể xóa bàn. Vui lòng thử lại.</div>";
            header("Location: manage-table.php");
            exit();
        }
    } else {
        // Nếu bàn đã được đặt, không cho phép xóa
        $_SESSION['delete'] = "<div class='error'>Không thể xóa bàn đã được đặt.</div>";
        header("Location: manage-table.php");
        exit();
    }
} else {
    // Nếu thiếu tham số, chuyển hướng về trang quản lý bàn
    header("Location: manage-table.php");
    exit();
}
?>