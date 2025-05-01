<?php 

// Kiểm tra xem session đã được khởi tạo chưa
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Chỉ khởi tạo session nếu chưa có
}

// Đảm bảo rằng hằng số chỉ được định nghĩa một lần
if (!defined('SITEURL')) {
    define('SITEURL', '');  // Thêm URL của trang web của bạn nếu cần
}

if (!defined('LOCALHOST')) {
    define('LOCALHOST', 'localhost');
}

if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'root');
}

if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', '');
}

if (!defined('DB_NAME')) {
    define('DB_NAME', 'RESTAURANT_FLO');
}

// Kết nối cơ sở dữ liệu
$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) or die(mysqli_error($conn));

// Chọn cơ sở dữ liệu
$db_select = mysqli_select_db($conn, DB_NAME) or die(mysqli_error($conn)); // Chọn cơ sở dữ liệu

?>