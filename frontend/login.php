<?php 
include('config/constants.php');

// Kiểm tra nếu session chưa được khởi tạo, thì khởi tạo session mới
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // Chỉ khởi tạo session nếu chưa có
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/admin.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login-front.css">
    <link rel="icon" 
      type="image/png" 
      href="../images/logo.png">

    <title>Đăng Nhập</title>
</head>
<body>

        <!-- Form đăng nhập -->
<div class="container">
  <div class="brand-logo"></div>
  <div class="brand-title">Đăng Nhập Người Dùng</div>
  
  <form action="" class="inputs" method="POST" name="form1">
    <label>Tên đăng nhập</label>
    <input type="text" placeholder="" name="username" required>
    <label>Mật khẩu</label>
    <input type="password" name="password" required>
    <br>
   
        Chưa có tài khoản?
        <br>
        <a href="register.php">Tạo tài khoản mới</a>
    
    <button type="submit" name="submit" value="login">Đăng nhập</button>  
  </form>
</div>

</body>
</html>


<?php 
    // Kiểm tra khi người dùng gửi thông tin đăng nhập
    if(isset($_POST['submit'])) {
        // Làm sạch đầu vào để ngăn ngừa SQL Injection
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password'])); // Mã hóa mật khẩu bằng MD5 (khuyến cáo nên dùng bcrypt hoặc các phương thức mã hóa khác)

        // Kiểm tra xem tài khoản có tồn tại trong cơ sở dữ liệu không
        $sql = "SELECT * FROM tbl_users WHERE username='$username' AND password='$password'";
        $res = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($res); // Đếm số dòng trả về

        if($count == 1) {
            // Nếu tìm thấy người dùng trong cơ sở dữ liệu (đăng nhập thành công)
            $row = mysqli_fetch_assoc($res); // Lấy thông tin người dùng từ cơ sở dữ liệu

            // Lưu thông tin người dùng vào session để sử dụng cho các trang khác
            $_SESSION['login'] = "<div class='success'>Login Successful</div>"; // Thông báo đăng nhập thành công
            $_SESSION['user'] = $row['username']; // Lưu tên đăng nhập vào session
            $_SESSION['user_name'] = $row['name']; // Lưu tên người dùng vào session
            $_SESSION['user_phone'] = $row['phone']; // Lưu số điện thoại người dùng vào session
            $_SESSION['email'] = $row['email']; // Lưu email người dùng vào session
            $_SESSION['role'] = $row['role']; // Lưu role (admin hay user) vào session

            // Kiểm tra xem người dùng là admin hay người dùng bình thường
            if ($_SESSION['role'] == 'admin') {
                // Nếu là admin, chuyển hướng đến trang quản trị
                header('Location: ' . SITEURL . 'http://localhost/RESTAURANT_FLO/admin/');
            } else {
                // Nếu là người dùng bình thường, chuyển hướng đến trang khách hàng
                header('Location: ' . SITEURL . 'index.php');
            }
            exit;
        } else {
            // Nếu đăng nhập không thành công (sai tài khoản hoặc mật khẩu)
            echo "<script>
                    alert('Tên đăng nhập hoặc mật khẩu không chính xác'); 
                    window.location.href='login.php'; // Quay lại trang đăng nhập
                  </script>";
        }
    }
?> 
