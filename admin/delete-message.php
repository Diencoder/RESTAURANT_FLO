<?php 

//Including the constant file

include('../frontend/config/constants.php');
//include('login-check.php');

// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Nếu không phải admin hoặc chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: ' . SITEURL . 'login.php');
    exit;
}


$id = $_GET['id'];
$sql = "DELETE FROM message WHERE id=$id";

$res = mysqli_query($conn, $sql);


if($res == true){
    
   $_SESSION['delete'] = "<div class='success'>Message Deleted Successfully</div>";

    header('location:'.SITEURL.'messages.php');
}
else{

    $_SESSION['delete'] = "<div class='error'>Failed to Delete Message</div>";
    header('location:'.SITEURL.'messages.php');
}


?>