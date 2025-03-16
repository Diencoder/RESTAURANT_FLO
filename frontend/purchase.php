<?php include('config/constants.php'); ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <link rel="stylesheet" href="css/eipay.css">
    <link rel="icon" type="image/png" href="../images/logo.png">
</head>
<body>

<?php 
date_default_timezone_set('Asia/Dhaka');

// Kiểm tra xem tổng tiền sau giảm giá đã có trong session chưa
if (isset($_SESSION['total_after_discount'])) {
    // Sử dụng tổng tiền sau giảm giá
    $total_amount = $_SESSION['total_after_discount'];
} else {
    // Nếu không có giảm giá, tính tổng tiền giỏ hàng bình thường
    $total_amount = 0;
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        foreach ($_SESSION['cart'] as $key => $value) {
            $item_total = $value['Price'] * $value['Quantity'];
            $total_amount += $item_total;
        }
    }
}

// Kiểm tra xem có mã giảm giá nào áp dụng nữa không
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['apply_discount'])) {
    $promo_code = $_POST['promo_code'];

    // Dùng prepared statement để tránh SQL Injection
    $sql = "SELECT * FROM tbl_promotions WHERE promo_code = ? AND status = 'Active' AND valid_from <= NOW() AND valid_to >= NOW()";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $promo_code);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Mã giảm giá hợp lệ
        $row = mysqli_fetch_assoc($result);
        $discount_amount = 0;

        if ($row['discount_percent'] > 0) {
            // Giảm giá theo phần trăm
            $discount_amount = ($total_amount * $row['discount_percent']) / 100;
        } else if ($row['discount_amount'] > 0) {
            // Giảm giá theo số tiền
            $discount_amount = $row['discount_amount'];
        }

        // Tính tổng tiền sau giảm giá
        $total_after_discount = $total_amount - $discount_amount;

        // Lưu vào session để hiển thị lại trên trang giỏ hàng
        $_SESSION['discount_amount'] = $discount_amount;
        $_SESSION['total_after_discount'] = $total_after_discount;
    } else {
        echo "<script>alert('Mã giảm giá không hợp lệ hoặc đã hết hạn');</script>";
    }
    mysqli_stmt_close($stmt);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['purchase'])) {
    // Lấy thông tin từ form
    $cur_random_value = mysqli_real_escape_string($conn, $_POST['tran_id']);
    $amount = $total_amount; // Dùng giá trị đã tính tổng (đã có giảm giá nếu có)
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $cus_name = mysqli_real_escape_string($conn, $_POST['cus_name']);
    $cus_email = mysqli_real_escape_string($conn, $_POST['cus_email']);
    $cus_add1 = mysqli_real_escape_string($conn, $_POST['cus_add1']);
    $cus_city = mysqli_real_escape_string($conn, $_POST['cus_city']);
    $cus_phone = mysqli_real_escape_string($conn, $_POST['cus_phone']);
    $order_date = date("Y-m-d h:i:sa");

    // Insert vào bảng aamarpay
    $query1 = "INSERT INTO `aamarpay`(`cus_name`, `amount`, `status`, `transaction_id`, `card_type`) 
               VALUES ('$cus_name', '$amount', 'Pending', '$cur_random_value', 'Card')";
    
    if (mysqli_query($conn, $query1)) {
        // Insert vào order_manager sau khi có transaction_id trong aamarpay
        $query2 = "INSERT INTO `order_manager`(`username`, `cus_name`, `cus_email`, `cus_add1`, `cus_city`, `cus_phone`, `payment_status`, `order_date`, `total_amount`, `transaction_id`, `order_status`) 
                   VALUES ('$username', '$cus_name', '$cus_email', '$cus_add1', '$cus_city', '$cus_phone', 'Pending', '$order_date', '$amount', '$cur_random_value', 'Pending')";
        
        if (mysqli_query($conn, $query2)) {
            $Order_Id = mysqli_insert_id($conn); 
            $query3 = "INSERT INTO `online_orders_new`(`order_id`, `Item_Name`, `Price`, `Quantity`) VALUES (?,?,?,?)";
            $stmt = mysqli_prepare($conn, $query3);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "isii", $Order_Id, $Item_Name, $Price, $Quantity);

                // Lặp qua các sản phẩm trong giỏ hàng và insert vào online_orders_new
                foreach ($_SESSION['cart'] as $key => $values) {
                    $Item_Name = isset($values['Item_Name']) ? $values['Item_Name'] : 'N/A';
                    $Price = isset($values['Price']) ? $values['Price'] : 0;
                    $Quantity = isset($values['Quantity']) ? $values['Quantity'] : 0;

                    // Nếu có giảm giá, áp dụng giảm giá vào giá sản phẩm
                    if (isset($_SESSION['discount_amount'])) {
                        $Price = $Price - ($_SESSION['discount_amount'] / count($_SESSION['cart']));
                    }

                    mysqli_stmt_execute($stmt);

                    // Cập nhật số lượng sản phẩm trong kho
                    $update_quantity_query = "UPDATE `tbl_food` SET stock = stock - $Quantity WHERE title = '$Item_Name'";
                    mysqli_query($conn, $update_quantity_query);
                }

                // Xóa giỏ hàng sau khi thanh toán thành công
                unset($_SESSION['cart']);
                unset($_SESSION['discount_amount']);
unset($_SESSION['total_after_discount']);
                // Chuyển hướng đến trang thanh toán của Aamarpay
                ?>
                <form action="https://sandbox.aamarpay.com/index.php" class="inputs" method="POST" name="form1" id="form1">
                    <input type="hidden" name="store_id" value="aamarpay">
                    <input type="hidden" name="signature_key" value="28c78bb1f45112f5d40b956fe104645a">
                    <input type="hidden" name="tran_id" value="<?php echo $cur_random_value; ?>">
                    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                    <input type="hidden" name="currency" value="BDT">
                    <input type="hidden" name="cus_name" value="<?php echo $cus_name; ?>">
                    <input type="hidden" name="cus_email" value="<?php echo $cus_email; ?>">
                    <input type="hidden" name="cus_add1" value="<?php echo $cus_add1; ?>">
                    <input type="hidden" name="cus_add2" value="Dhaka">
                    <input type="hidden" name="cus_city" value="<?php echo $cus_city; ?>">
                    <input type="hidden" name="cus_state" value="Dhaka">
                    <input type="hidden" name="cus_postcode" value="1206">
                    <input type="hidden" name="cus_country" value="Bangladesh">
                    <input type="hidden" name="cus_phone" value="<?php echo $cus_phone; ?>">
                    <input type="hidden" name="cus_fax" value="010000000">
                    <input type="hidden" name="desc" value="Products Name Payment">
                    <input type="hidden" name="success_url" value="<?php echo SITEURL; ?>success-onlpmt.php">
                    <input type="hidden" name="fail_url" value="<?php echo SITEURL; ?>fail-onlpmt.php">
                    <input type="hidden" name="cancel_url" value="<?php echo SITEURL; ?>cancel-onlpmt.php">

                    <button type="submit" name="submit" value="Pay Now"></button>
                </form>
            
                <?php
            } else {
                echo "<script>alert('SQL Query Prepare Error'); window.location.href='mycart.php';</script>";
            }
        } else {
            echo "<script>alert('SQL Error'); window.location.href='mycart.php';</script>";
        }
    } else {
        echo "<script>alert('Error inserting into aamarpay'); window.location.href='mycart.php';</script>";
    }
}
?>

<script>            
    document.addEventListener("DOMContentLoaded", function(event) {
        document.createElement('form').submit.call(document.getElementById('form1'));
    });         
</script>

</body>
</html>
