<?php
ob_start(); // Bắt đầu buffer đầu ra
include('config/constants.php'); 

date_default_timezone_set('Asia/Dhaka');
if(!isset($_SESSION['user'])) {
    // Người dùng chưa đăng nhập
    // Chuyển hướng đến trang đăng nhập với thông báo
    $_SESSION['no-login-message'] = "<div class='error'>Vui lòng đăng nhập để truy cập vào Bảng điều khiển quản trị</div>";
    header('location:'.SITEURL.'login.php');
    exit;
}

if(isset($_SESSION['user'])) {
    $username = $_SESSION['user'];
    $fetch_user = "SELECT * FROM tbl_users WHERE username = '$username'";
    $res_fetch_user = mysqli_query($conn, $fetch_user);

    while($rows = mysqli_fetch_assoc($res_fetch_user)) {
        $id = $rows['id'];
        $name = $rows['name'];
        $email = $rows['email'];
        $add1 = $rows['add1'];
        $city = $rows['city'];
        $phone = $rows['phone'];
        $username = $rows['username'];
        $password = $rows['password'];
    }
}


include('header.php');
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Card chứa form -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-center mb-4">Cập nhật thông tin cá nhân</h4>

                    <!-- Form cập nhật thông tin -->
                    <form action="" method="POST">
                        <!-- Tên -->
                        <div class="mb-3">
                            <label for="cus_name" class="form-label">Tên</label>
                            <input type="text" name="cus_name" value="<?php echo htmlspecialchars($name); ?>" id="cus_name" class="form-control" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="cus_email" class="form-label">Email</label>
                            <input type="email" name="cus_email" value="<?php echo htmlspecialchars($email); ?>" id="cus_email" class="form-control" required>
                        </div>

                        <!-- Địa chỉ -->
                        <div class="mb-3">
                            <label for="cus_add1" class="form-label">Địa chỉ</label>
                            <textarea name="cus_add1" id="cus_add1" class="form-control" rows="5" required><?php echo htmlspecialchars($add1); ?></textarea>
                        </div>

                        <!-- Thành phố -->
                        <div class="mb-3">
                            <label for="cus_city" class="form-label">Thành phố</label>
                            <input type="text" name="cus_city" value="<?php echo htmlspecialchars($city); ?>" id="cus_city" class="form-control" required>
                        </div>

                        <!-- Số điện thoại -->
                        <div class="mb-3">
                            <label for="cus_phone" class="form-label">Số điện thoại</label>
                            <input type="text" name="cus_phone" value="<?php echo htmlspecialchars($phone); ?>" id="cus_phone" class="form-control" required>
                        </div>

                        <!-- Hidden username field -->
                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">

                        <!-- Nút submit -->
                        <div class="d-grid gap-2">
                            <input type="submit" name="submit" value="Cập nhật" class="btn btn-primary btn-lg">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $cus_name = $_POST['cus_name'];
    $cus_email = $_POST['cus_email'];
    $cus_add1 = $_POST['cus_add1'];
    $cus_city = $_POST['cus_city'];
    $cus_phone = $_POST['cus_phone'];

    $update_account = "UPDATE tbl_users SET
        name = '$cus_name',
        email = '$cus_email',
        add1 = '$cus_add1',
        city = '$cus_city',
        phone = '$cus_phone'
        WHERE username='$username'";

    $res_update_account = mysqli_query($conn, $update_account);
    if($res_update_account == true) {
        $_SESSION['update'] = "<div class='success'>Cập nhật tài khoản thành công</div>";
        header('location:'.SITEURL.'myaccount.php');
        exit;
    } else {
        $_SESSION['update'] = "<div class='error'>Cập nhật tài khoản thất bại</div>";
        header('location:'.SITEURL.'myaccount.php');
        exit;
    }
}

ob_end_flush(); // Kết thúc buffer sau khi mọi thao tác hoàn tất
?>
<head>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/update_accout.css">


</head>
<?php include('footer.php'); ?>
