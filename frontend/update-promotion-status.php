<?php
include('config/constants.php'); // Kết nối với database

if (isset($_GET['promo_code'])) {
    $promo_code = mysqli_real_escape_string($conn, $_GET['promo_code']);
    $new_status = mysqli_real_escape_string($conn, $_GET['status']); // 'Expired', 'Disabled'

    // Cập nhật trạng thái mã giảm giá
    $sql = "UPDATE tbl_promotions SET status = '$new_status' WHERE promo_code = '$promo_code'";

    if (mysqli_query($conn, $sql)) {
        echo "Trạng thái mã giảm giá đã được cập nhật!";
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>
