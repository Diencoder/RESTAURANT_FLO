<?php
include('config/constants.php');

// Bắt đầu session
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Chỉ khởi tạo session nếu chưa có
}

// Kiểm tra nếu người dùng đã đăng nhập
$current_user_email = ''; // Đặt giá trị mặc định cho email
if (isset($_SESSION['email'])) {
    $current_user_email = $_SESSION['email'];  // Lấy email người dùng từ session
} else {
    echo "Vui lòng đăng nhập để xem các bàn đã đặt!";
    exit;
}

// Biến thông báo
$message = "";
$message_type = "success"; // Mặc định là thành công

// Kiểm tra nếu có yêu cầu hủy đặt bàn
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel'])) {
    $table_id = $_POST['table_id']; // Lấy ID bàn từ form

    // Cập nhật trạng thái bàn về "Available"
    $sql_update_table = "UPDATE tbl_tables SET status = 'Available' WHERE table_number = ?";
    $stmt_update = $conn->prepare($sql_update_table);
    $stmt_update->bind_param("i", $table_id);
    $stmt_update->execute();

    // Xóa thông tin đặt bàn trong bảng tbl_reservations
    $sql_cancel_reservation = "DELETE FROM tbl_reservations WHERE table_number = ? AND customer_email = ?";
    $stmt_cancel = $conn->prepare($sql_cancel_reservation);
    $stmt_cancel->bind_param("is", $table_id, $current_user_email);
    $stmt_cancel->execute();

    // Thông báo hủy thành công
    $message = "Hủy đặt bàn thành công!";
    $message_type = "success"; // Thông báo thành công
}

// Truy vấn các bàn đã được đặt bởi người dùng
$sql = "SELECT * FROM tbl_reservations WHERE customer_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_user_email);
$stmt->execute();
$reservations = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reserved Tables</title>
    <style>
        /* CSS styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #444;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            font-size: 14px;
        }

        .action-btn {
            padding: 8px 16px;
            background-color: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }

        .action-btn:hover {
            background-color: #c82333;
        }

        .no-reservations {
            text-align: center;
            font-size: 16px;
            margin-top: 40px;
        }

        .notification {
            background-color: #4CAF50; /* Xanh lá cho thông báo thành công */
            color: white;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }

        .notification.error {
            background-color: #f44336; /* Đỏ cho thông báo lỗi */
        }

        .notification button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #008CBA;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .notification button:hover {
            background-color: #007B9E;
        }
    </style>
</head>
<body>
<h1>Các Bàn Đặt Của Tôi</h1>

<!-- Hiển thị thông báo -->
<?php if ($message != "") { ?>
    <div class="notification <?php echo $message_type == 'success' ? '' : 'error'; ?>">
        <p><?php echo $message; ?></p>
        <form action="reservation_page.php">
            <button type="submit">Quay lại</button>
        </form>
    </div>
<?php } ?>

<?php if ($reservations->num_rows > 0) { ?>
    <table>
        <tr>
            <th>Số Bàn</th>
            <th>Khu Vực</th>
            <th>Thời Gian Đặt</th>
            <th>Trạng Thái</th>
            <th>Thao Tác</th>
        </tr>
        <?php while ($row = $reservations->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['table_number']; ?></td>
                <td><?php echo $row['area']; ?></td>
                <td><?php echo $row['reservation_time']; ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <form method="POST" action="view_table.php">
                        <input type="hidden" name="table_id" value="<?php echo $row['table_number']; ?>">
                        <input type="submit" name="cancel" value="Hủy Đặt Bàn" class="action-btn">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php } else { ?>
    <p class="no-reservations">Bạn không có bất kỳ đặt bàn nào.</p>
<?php } ?>

</body>
</html>
