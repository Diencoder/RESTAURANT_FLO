<?php
// Bắt đầu session để kiểm tra người dùng đã đăng nhập chưa
session_start();

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$username = $_SESSION['user'];  // Lấy tên người dùng từ session

// Bao gồm file kết nối cơ sở dữ liệu
include('config/constants.php');

// Kiểm tra nếu có dữ liệu POST từ form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $order_id = $_POST['order_id'];
    $rating = $_POST['rating'];
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);  // Đảm bảo an toàn với SQL

    // Kiểm tra dữ liệu hợp lệ
    if (!empty($rating) && !empty($review_text) && !empty($username)) {
        // Kiểm tra xem user_id có tồn tại trong bảng tbl_users không
        $check_user_query = "SELECT id FROM tbl_users WHERE username = '$username'";
        $user_result = mysqli_query($conn, $check_user_query);
        
        // Kiểm tra nếu người dùng tồn tại
        if (mysqli_num_rows($user_result) > 0) {
            // Lấy user_id từ kết quả truy vấn
            $user_row = mysqli_fetch_assoc($user_result);
            $user_id = $user_row['id'];  // Lấy user_id

            // Lấy food_id từ bảng tbl_food dựa trên item_name trong bảng online_orders_new
            $food_id_query = "SELECT f.id 
                              FROM tbl_food f
                              JOIN online_orders_new o ON f.title = o.item_name
                              WHERE o.order_id = '$order_id' LIMIT 1";
            $food_result = mysqli_query($conn, $food_id_query);

            if (mysqli_num_rows($food_result) > 0) {
                // Lấy food_id từ bảng tbl_food
                $food_row = mysqli_fetch_assoc($food_result);
                $food_id = $food_row['id'];

                // Chuẩn bị câu lệnh SQL để chèn dữ liệu vào bảng reviews
                $sql = "INSERT INTO tbl_reviews (order_id, user_id, food_id, rating, review_text) 
                        VALUES ('$order_id', '$user_id', '$food_id', '$rating', '$review_text')";

                // Thực thi câu lệnh và kiểm tra xem có lỗi gì không
                if (mysqli_query($conn, $sql)) {
                    echo "Đánh giá của bạn đã được gửi thành công!";
                } else {
                    echo "Lỗi: " . $sql . "<br>" . mysqli_error($conn);
                }
            } else {
                echo "Không tìm thấy món ăn tương ứng trong đơn hàng.";
            }
        } else {
            echo "Người dùng không tồn tại trong cơ sở dữ liệu.";
        }
    } else {
        echo "Vui lòng điền đầy đủ thông tin đánh giá!";
    }
}

// Đóng kết nối
$conn->close();
?>
