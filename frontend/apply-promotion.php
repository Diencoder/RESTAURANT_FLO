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

        echo "Tổng tiền sau giảm giá: " . $total_amount;
    } else {
        echo "Mã giảm giá không hợp lệ hoặc đã hết hạn!";
    }
}
?>

<form action="apply-promotion.php" method="POST">
    Mã giảm giá: <input type="text" name="promo_code" required><br>
    Tổng tiền: <input type="number" name="total_amount" required><br>
    <input type="submit" value="Áp dụng">
</form>
