<?php
include('config/constants.php'); // Kết nối với cơ sở dữ liệu

// Kiểm tra nếu người dùng gửi mã giảm giá
if (isset($_POST['promo_code'])) {
    $promo_code = mysqli_real_escape_string($conn, $_POST['promo_code']);

    // Truy vấn mã giảm giá từ database
    $sql = "SELECT * FROM tbl_promotions WHERE promo_code = '$promo_code' AND status = 'Active' AND valid_from <= NOW() AND valid_to >= NOW()";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $discount_percent = $row['discount_percent'];
        $discount_amount = $row['discount_amount'];

        // Tính toán giảm giá
        if ($discount_percent > 0) {
            $total_amount = $_POST['total_amount'] - ($_POST['total_amount'] * $discount_percent / 100);
        } else if ($discount_amount > 0) {
            $total_amount = $_POST['total_amount'] - $discount_amount;
        }

        // Lưu tổng tiền sau giảm giá vào session
        $_SESSION['total_after_discount'] = $total_amount;

        echo json_encode(['discount' => $discount_amount, 'total_after_discount' => $total_amount]);
    } else {
        echo json_encode(['discount' => 0, 'total_after_discount' => $_POST['total_amount']]);
    }
}
?>
