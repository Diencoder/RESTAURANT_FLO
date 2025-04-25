<?php include('../frontend/config/constants.php');
	  //include('login-check.php');
      // Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Nếu không phải admin hoặc chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: ' . SITEURL . 'login.php');
    exit;
}
?>

<?php

//1. Get the ID of Order to be deleted
$id = $_GET['id'];
//2.Create SQL Query to delete admin

$sql = "DELETE FROM tbl_eipay WHERE id=$id";

//Execute the query

$res = mysqli_query($conn, $sql);

//Check whether the query executed succesfully or not

if($res == true){
    //Query executed successfully and admin deleted
    //echo "Order Deleted";
    //Create Session varibale to display message 

    $_SESSION['delete'] = "<div class='success'>Order Deleted Successfully</div>";

    //Redirecting to Admin Panel Page

    header('location:'.SITEURL.'manage-ei-order.php');
}
else{
    //Failed to delete admin
    //echo "Failed to Delete Admin";

    $_SESSION['delete'] = "<div class='error'>Failed to Delete Order</div>";
    header('location:'.SITEURL.'manage-ei-order.php');
}

//3. Redirect to manage admin page with message(Succuess/error)



?>