<!-- Including the constant file -->
<?php 
include('../frontend/config/constants.php');

?>



<!DOCTYPE html>
<html lang="en">
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

    <title>Login</title>
</head>
<body>

        <?php 
            if(isset($_SESSION['login']))
            {
                echo $_SESSION['login'];
                unset($_SESSION['login']);
            }
           
        ?>

        <!-- Login Form -->
<div class="container">
<div class="brand-logo"></div>
<div class="brand-title">Admin Panel</div>

<form action="" class="inputs" method="POST" name="form1">
    <label>Username</label>
    <input type="text" placeholder="" name="username" required>
    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit" name="submit" value="login">Login</button>
    
</form>
</div>
</body>
</html>

<!--Check whether the submit button is clicked or not -->

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
                header('Location: ' . SITEURL . 'http://localhost/robo-cafe-rms/admin/');
            } else {
                // Nếu là người dùng bình thường, chuyển hướng đến trang khách hàng
                header('Location: ' . SITEURL . 'index.php');
            }
            exit;
        } else {
            // Nếu đăng nhập không thành công (sai tài khoản hoặc mật khẩu)
            echo "<script>
                    alert('Wrong Username or Password'); 
                    window.location.href='login.php'; // Quay lại trang đăng nhập
                  </script>";
        }
    }
?> 
