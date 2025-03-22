<?php
session_start();  // Khởi tạo session

include('config/constants.php');  // Bao gồm file kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'Người dùng chưa đăng nhập']);  // Trả về lỗi dưới dạng JSON
    exit;
}

$user = $_SESSION['user'];  // Lấy tên người dùng từ session

// Lấy các món ăn khách hàng đã đặt nhiều lần
$sql_user_favorites = "
    SELECT f.id, f.title, f.price, f.image_name, COUNT(*) AS total_orders 
    FROM tbl_order_history oh
    JOIN tbl_food f ON oh.food_id = f.id
    WHERE oh.user_id = (SELECT id FROM tbl_users WHERE username = ?)
    GROUP BY f.id, f.title, f.price, f.image_name
    ORDER BY total_orders DESC
    LIMIT 5
";

$stmt = $conn->prepare($sql_user_favorites);
$stmt->bind_param("s", $user);  // Sử dụng tên người dùng trong query
$stmt->execute();
$result = $stmt->get_result();
$user_favorites = $result->fetch_all(MYSQLI_ASSOC);

// Nếu khách hàng có ít hơn 5 món, lấy thêm món từ toàn hệ thống
$remaining_count = 5 - count($user_favorites);
$unique_suggestions = $user_favorites; // Lưu các món ăn yêu thích của khách hàng

if ($remaining_count > 0) {
    // Lấy các món ăn phổ biến từ hệ thống nếu cần thêm
    $sql_popular_foods = "
        SELECT f.id, f.title, f.price, f.image_name, COUNT(*) AS total_orders 
        FROM tbl_order_history oh
        JOIN tbl_food f ON oh.food_id = f.id
        GROUP BY f.id, f.title, f.price, f.image_name
        ORDER BY total_orders DESC
        LIMIT ?
    ";

    // Sử dụng tham số để lấy đủ số món ăn còn thiếu
    $stmt = $conn->prepare($sql_popular_foods);
    $stmt->bind_param("i", $remaining_count);
    $stmt->execute();
    $result = $stmt->get_result();
    $popular_foods = $result->fetch_all(MYSQLI_ASSOC);

    // Thêm các món ăn phổ biến vào danh sách gợi ý
    $unique_suggestions = array_merge($unique_suggestions, $popular_foods);
}

$merged_foods = array_merge($user_favorites, $popular_foods);
$unique_suggestions = [];
$food_ids = [];

foreach ($merged_foods as $food) {
    if (!in_array($food['id'], $food_ids)) {
        $food_ids[] = $food['id'];
        $unique_suggestions[] = $food;
    }
}

// Trả về kết quả dưới dạng JSON
echo json_encode($unique_suggestions);
?>
