<?php 
ob_start(); // Bắt đầu bộ đệm đầu ra (output buffering)

include('config/constants.php'); 

date_default_timezone_set('Asia/Dhaka');

if(!isset($_SESSION['user'])) { // Nếu session của người dùng chưa được thiết lập
    // Người dùng chưa đăng nhập
    // Chuyển hướng về trang đăng nhập với thông báo

    $_SESSION['no-login-message'] = "<div class='error'>Vui lòng đăng nhập để truy cập vào Bảng điều khiển Quản trị viên</div>";
    header('location:'.SITEURL.'login.php');
}

if(isset($_SESSION['user'])) {
    $username = $_SESSION['user'];

    $fetch_user = "SELECT * FROM tbl_users WHERE username = '$username'";

    $res_fetch_user = mysqli_query($conn, $fetch_user);

    while($rows=mysqli_fetch_assoc($res_fetch_user)) {
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

<!-- Bao bọc form trong một container Bootstrap -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Card bọc form để tạo giao diện đẹp hơn -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title text-center mb-4">Đổi Mật Khẩu</h4>

                    <!-- Form đổi mật khẩu -->
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Mật khẩu mới</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                        </div>

                        <!-- Ẩn tên người dùng trong form -->
                        <input type="hidden" name="username" value="<?php echo $username; ?>">

                        <!-- Nút submit -->
                        <div class="d-grid gap-2">
                            <input type="submit" name="submit" value="Đổi Mật Khẩu" class="btn btn-primary btn-lg">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Kiểm tra xem nút Submit đã được nhấn chưa
if(isset($_POST['submit'])) {

   // 1. Lấy dữ liệu từ form
   $username = $_POST['username'];
   $current_password = md5($_POST['current_password']);  // Mã hóa mật khẩu hiện tại bằng MD5
   $new_password = md5($_POST['new_password']);          // Mã hóa mật khẩu mới bằng MD5
   $confirm_password = md5($_POST['confirm_password']);  // Mã hóa xác nhận mật khẩu mới

   // 2. Kiểm tra mật khẩu hiện tại có đúng không
   $update_password = "SELECT * FROM tbl_users WHERE username='$username' AND password='$current_password'";

   $res_update_password = mysqli_query($conn, $update_password);  // Thực thi câu lệnh kiểm tra mật khẩu

   if($res_update_password == true) {
       $count = mysqli_num_rows($res_update_password);  // Kiểm tra xem có người dùng nào khớp với thông tin không

       if($count == 1) {  // Nếu mật khẩu hiện tại đúng
           
           if($new_password == $confirm_password) {  // Kiểm tra mật khẩu mới và xác nhận mật khẩu có trùng khớp
               
               // 3. Cập nhật mật khẩu mới vào cơ sở dữ liệu
               $sql2_update_password = "UPDATE tbl_users SET password = '$new_password' WHERE username='$username'";
               $res2_update_password = mysqli_query($conn, $sql2_update_password);  // Thực thi câu lệnh cập nhật mật khẩu

               if($res2_update_password == true) {  
                   // Nếu cập nhật thành công
                   $_SESSION['change-pwd'] = "<div class='success'>Đổi mật khẩu thành công.</div>";
                   header('location:' . SITEURL . 'myaccount.php');  // Chuyển hướng về trang tài khoản người dùng
               } else {
                   // Nếu không thành công
                   $_SESSION['pwd-not-match'] = "<div class='error'>Không thể đổi mật khẩu. Thử lại sau.</div>";
                   header('location:' . SITEURL . 'myaccount.php');
               }

           } else {
               // Nếu mật khẩu mới và xác nhận mật khẩu không khớp
               $_SESSION['pwd-not-match'] = "<div class='error'>Mật khẩu không khớp. Vui lòng thử lại.</div>";
               header('location:' . SITEURL . 'myaccount.php');
           }

       } else {
           // Nếu không tìm thấy người dùng hoặc mật khẩu cũ không đúng
           $_SESSION['user-not-found'] = "<div class='error'>Không tìm thấy người dùng</div>";
           header('location:' . SITEURL . 'myaccount.php');
       }
   }
}
?>

<!-- Categories Start -->
<div class="container">
    <div class="row">
       
    </div>
</div>

<!-- Categories End -->

<?php include('footer.php'); ?>

<?php ob_end_flush(); // Kết thúc bộ đệm đầu ra ?>
