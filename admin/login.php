<!-- Bao gồm tệp cấu hình -->
<?php 
include('../frontend/config/constants.php');
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
    <link rel="stylesheet" href="login.css">
    <link rel="icon" 
    type="image/png" 
    href="../images/logo.png">

    <title>Đăng Nhập</title>
</head>
<body>

        <?php 
            // Kiểm tra nếu có thông báo đăng nhập
            if(isset($_SESSION['login']))
            {
                echo $_SESSION['login']; // Hiển thị thông báo
                unset($_SESSION['login']); // Xóa thông báo đăng nhập sau khi đã hiển thị
            }
        ?>

        <!-- Form Đăng Nhập -->
<div class="container">
<div class="brand-logo"></div>
<div class="brand-title">Đăng nhập quản trị</div>

<form action="" class="inputs" method="POST" name="form1">
    <label>Tên Người Dùng</label>
    <input type="text" placeholder="" name="username" required>
    <label>Mật Khẩu</label>
    <input type="password" name="password" required>

    <button type="submit" name="submit" value="login">Đăng Nhập</button>
    
</form>
</div>
</body>
</html>

<!-- Kiểm tra xem người dùng có nhấn nút đăng nhập không -->
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
            $_SESSION['login'] = "<div class='success'>Đăng Nhập Thành Công</div>"; // Thông báo đăng nhập thành công
            $_SESSION['user'] = $row['username']; // Lưu tên đăng nhập vào session
            $_SESSION['user_name'] = $row['name']; // Lưu tên người dùng vào session
            $_SESSION['user_phone'] = $row['phone']; // Lưu số điện thoại người dùng vào session
            $_SESSION['email'] = $row['email']; // Lưu email người dùng vào session
            $_SESSION['role'] = $row['role']; // Lưu vai trò (admin hay user) vào session

            // Kiểm tra xem người dùng là admin hay người dùng bình thường
            if ($_SESSION['role'] == 'admin') {
                // Nếu là admin, chuyển hướng đến trang quản trị
                header('Location: ' . SITEURL . 'http://localhost/flo_restaurant/admin/');
            } else {
                // Nếu là người dùng bình thường, chuyển hướng đến trang khách hàng
                header('Location: ' . SITEURL . 'index.php');
            }
            exit;
        } else {
            // Nếu đăng nhập không thành công (sai tài khoản hoặc mật khẩu)
            echo "<script>
                    alert('Tên Đăng Nhập hoặc Mật Khẩu Sai'); 
                    window.location.href='login.php'; // Quay lại trang đăng nhập
                  </script>";
        }
    }
?>
