<?php
include('config/constants.php'); // Đảm bảo kết nối với database

if (isset($_POST['submit'])) {
    $promo_code = mysqli_real_escape_string($conn, $_POST['promo_code']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $discount_percent = mysqli_real_escape_string($conn, $_POST['discount_percent']);
    $discount_amount = mysqli_real_escape_string($conn, $_POST['discount_amount']);
    $valid_from = mysqli_real_escape_string($conn, $_POST['valid_from']);
    $valid_to = mysqli_real_escape_string($conn, $_POST['valid_to']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Kiểm tra xem mã giảm giá đã tồn tại chưa
    $check_sql = "SELECT * FROM tbl_promotions WHERE promo_code = '$promo_code'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        echo "Mã giảm giá này đã tồn tại!";
    } else {
        // Thêm mã giảm giá vào database
        $sql = "INSERT INTO tbl_promotions (promo_code, description, discount_percent, discount_amount, valid_from, valid_to, status) 
                VALUES ('$promo_code', '$description', '$discount_percent', '$discount_amount', '$valid_from', '$valid_to', '$status')";

        if (mysqli_query($conn, $sql)) {
            echo "Mã giảm giá đã được thêm thành công!";
        } else {
            echo "Lỗi: " . mysqli_error($conn);
        }
    }
}
?>

<form action="add-promotion.php" method="POST">
    Mã giảm giá: <input type="text" name="promo_code" required><br>
    Mô tả: <textarea name="description" required></textarea><br>
    Giảm giá theo %: <input type="number" name="discount_percent" step="0.01"><br>
    Giảm giá theo số tiền: <input type="number" name="discount_amount" step="0.01"><br>
    Từ ngày: <input type="datetime-local" name="valid_from" required><br>
    Đến ngày: <input type="datetime-local" name="valid_to" required><br>
    Trạng thái: 
    <select name="status">
        <option value="Active">Active</option>
        <option value="Expired">Expired</option>
        <option value="Disabled">Disabled</option>
    </select><br>
    <input type="submit" name="submit" value="Thêm mã giảm giá">
</form>
