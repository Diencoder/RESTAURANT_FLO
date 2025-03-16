<?php
include('config/constants.php'); // Bao gồm constants.php để kết nối cơ sở dữ liệu

// Kiểm tra xem session đã được khởi tạo chưa
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Chỉ khởi tạo session nếu chưa có
}

// Lấy thông tin người dùng từ session
$current_user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL; // Lấy user_id từ session
$current_user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Khách vãng lai'; 
$current_user_phone = isset($_SESSION['user_phone']) ? $_SESSION['user_phone'] : '0123456789'; 
$current_user_email = isset($_SESSION['email']) ? $_SESSION['email'] : ''; // Lấy email từ session

// Biến thông báo
$message = "";
$message_type = "success"; // Mặc định là thành công

// Đặt bàn
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reserve'])) {
    $table_id = $_POST['table_id'];
    $area = isset($_POST['area']) ? $_POST['area'] : 'Tầng 1';  // Đảm bảo area không phải NULL
    $customer_name = $current_user_name; // Lấy tên khách hàng từ session
    $customer_phone = $current_user_phone; // Lấy số điện thoại từ session
    $customer_email = $current_user_email; // Lấy email từ session

    // Lưu thông tin khách hàng và đặt bàn vào bảng tbl_reservations
    $sql_reserve = "INSERT INTO tbl_reservations (user_id, table_number, area, reservation_time, status, customer_name, customer_phone, customer_email)
                    VALUES (?, ?, ?, NOW(), 'Confirmed', ?, ?, ?)";
    $stmt_reserve = $conn->prepare($sql_reserve);
    $stmt_reserve->bind_param("iissss", $current_user_id, $table_id, $area, $customer_name, $customer_phone, $customer_email);
    
    if ($stmt_reserve->execute()) {
        // Cập nhật trạng thái bàn thành 'Reserved'
        $sql_update_table = "UPDATE tbl_tables SET status = 'Reserved' WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update_table);
        $stmt_update->bind_param("i", $table_id);
        $stmt_update->execute();

        // Lưu thông báo thành công trong session
        $_SESSION['message'] = "Đặt bàn thành công! Chúng tôi đã xác nhận đặt bàn của bạn.";
        $_SESSION['message_type'] = "success"; // Thông báo thành công

        // Redirect to the reservation page or another page
        header('Location: reservation_page.php');
        exit;
    } else {
        $_SESSION['message'] = "Có lỗi xảy ra khi đặt bàn. Vui lòng thử lại!";
        $_SESSION['message_type'] = "error"; // Thông báo lỗi
        header('Location: reservation_page.php');
        exit;
    }
}

// Hủy đặt bàn
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel'])) {
    $table_id = $_POST['table_id'];

    // Xóa thông tin đặt bàn trong bảng tbl_reservations
    $sql_cancel = "DELETE FROM tbl_reservations WHERE table_number = ? AND status = 'Confirmed'";
    $stmt_cancel = $conn->prepare($sql_cancel);
    $stmt_cancel->bind_param("i", $table_id);
    $stmt_cancel->execute();

    // Cập nhật trạng thái bàn về 'Available'
    $sql_update_table = "UPDATE tbl_tables SET status = 'Available' WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update_table);
    $stmt_update->bind_param("i", $table_id);
    $stmt_update->execute();

    $_SESSION['message'] = "Hủy đặt bàn thành công!";
    $_SESSION['message_type'] = "success"; // Thông báo thành công

    // Redirect to the reservation page or another page
    header('Location: reservation_page.php');
    exit;
}
?>

<!-- Hiển thị thông báo chỉ một lần -->
<?php if (isset($_SESSION['message'])) { ?>
    <div class="notification <?php echo $_SESSION['message_type'] == 'success' ? '' : 'error'; ?>">
        <p><?php echo $_SESSION['message']; ?></p>
        <form action="reservation_page.php">
            <button type="submit">Quay lại</button>
        </form>
    </div>
    <?php 
        // Clear the session message after displaying it
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    ?>
<?php } ?>
