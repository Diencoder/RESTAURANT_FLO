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
$sql = "DELETE FROM tbl_admin WHERE id=$id";

//Execute the query

$res = mysqli_query($conn, $sql);

//Check whether the query executed succesfully or not

if($res == true){
    
   $_SESSION['delete'] = "<div class='success'>Admin Deleted Successfully</div>";

    header('location:'.SITEURL.'manage-admin.php');
}
else{

    $_SESSION['delete'] = "<div class='error'>Failed to Delete Admin</div>";
    header('location:'.SITEURL.'manage-admin.php');
}

//3. Redirect to manage admin page with message(Succuess/error)



?>