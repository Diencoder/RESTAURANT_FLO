<?php
// Kiểm tra xem session đã được khởi tạo chưa
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Chỉ khởi tạo session nếu chưa có
}

// Bao gồm file constants.php để kết nối cơ sở dữ liệu
include('config/constants.php');

// Lấy thông tin người dùng từ session
if (isset($_SESSION['email'])) {
    $current_user_email = $_SESSION['email']; // Lấy email người dùng từ session
} else {
    // Nếu không có người dùng đăng nhập, chuyển hướng về trang login
    echo "Vui lòng đăng nhập để đặt bàn!";
    exit;
}

// Truy vấn để lấy tất cả các bàn
$sql = "SELECT * FROM tbl_tables";
$result = $conn->query($sql);
?>

<!-- Bao gồm phần header.php -->
<?php include('header.php'); ?>

<!-- CSS cho giao diện trang đặt bàn -->
<link rel="stylesheet" href="css/reservation_styles.css">

<!-- Nội dung trang -->
<div class="container">
    <h1 class="page-title">Danh sách bàn</h1>
    <div class="tables">
        <?php
        if ($result->num_rows > 0) {
            // Duyệt qua từng bàn và hiển thị thông tin
            while ($row = $result->fetch_assoc()) {
                $status = $row['status'];
                $status_text = "";
                $status_class = "";
                
                // Đặt trạng thái theo giá trị trong cơ sở dữ liệu
                if ($status == 'Available') {
                    $status_text = 'Trống';
                    $status_class = 'green';
                } elseif ($status == 'Reserved') {
                    $status_text = 'Đã đặt trước';
                    $status_class = 'red';
                } elseif ($status == 'Occupied') {
                    $status_text = 'Đang sử dụng';
                    $status_class = 'orange';
                }

                echo '<div class="table-card">';
                echo '<h3>Bàn ' . $row['table_number'] . '</h3>';
                echo '<p>Sức chứa: ' . $row['capacity'] . ' người</p>';
                echo '<p>Vị trí: ' . $row['area'] . '</p>';
                echo '<p>Trạng thái: <span class="' . $status_class . '">' . $status_text . '</span></p>';

                // Kiểm tra nếu bàn đã được đặt
                if ($status == 'Reserved') {
                    // Kiểm tra xem người dùng đã đặt bàn này chưa
                    $sql_check_reservation = "SELECT * FROM tbl_reservations WHERE table_number = ? AND customer_email = ?";
                    $stmt_check = $conn->prepare($sql_check_reservation);
                    $stmt_check->bind_param("is", $row['table_number'], $current_user_email);
                    $stmt_check->execute();
                    $result_check = $stmt_check->get_result();

                    // Nếu có kết quả, nghĩa là người dùng đã đặt bàn này, không hiển thị nút hủy
                    if ($result_check->num_rows > 0) {
                        // Không hiển thị gì thêm
                    } else {
                        echo '<button disabled>Bàn đã được đặt</button>';
                    }
                } elseif ($status == 'Available') {
                    // Hiển thị nút "Đặt bàn" nếu bàn trống
                    echo '<form method="POST" action="reserve_table.php">';
                    echo '<input type="hidden" name="table_id" value="' . $row['id'] . '">';
                    echo '<input type="hidden" name="customer_email" value="' . $current_user_email . '">'; // Lưu email vào cột customer_email
                    echo '<input type="submit" name="reserve" value="Đặt bàn" class="btn-reserve">';
                    echo '</form>';
                } else {
                    echo '<button disabled>Bàn đang sử dụng</button>';
                }

                echo '</div>';
            }
        } else {
            echo "Không có bàn nào trong hệ thống.";
        }
        ?>
    </div>
</div>

<!-- Bao gồm phần footer.php -->
<?php include('footer.php'); ?>
