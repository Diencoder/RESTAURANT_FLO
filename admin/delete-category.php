<?php 
    include('../frontend/config/constants.php');
    //include('login-check.php');
// Kiểm tra nếu người dùng chưa đăng nhập hoặc không phải admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // Nếu không phải admin hoặc chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: ' . SITEURL . 'login.php');
    exit;
}
    if(isset($_GET['id']) AND isset($_GET['image_name']))
    {
        $id = $_GET['id'];
        $image_name = $_GET['image_name'];

        if($image_name != "")
        {
            $path = "../images/category/".$image_name;
            $remove = unlink($path);

            if($remove==false)
            {
              $_SESSION['remove'] = "<div class='error'>Failed to Remove Category Image.</div>";
                header('location:'.SITEURL.'manage-category.php');
                die();
            }
        }

    $sql = "DELETE FROM tbl_category WHERE id=$id";
    $res = mysqli_query($conn, $sql);
        if($res==true)
        {
          $_SESSION['delete'] = "<div class='success'>Danh mục đã được xóa thành công.</div>";
            header('location:'.SITEURL.'manage-category.php');
        }
        else
        {
          $_SESSION['delete'] = "<div class='error'>Xóa danh mục thất bại.</div>";
            header('location:'.SITEURL.'manage-category.php');
        }
    }
    else
    {
        header('location:'.SITEURL.'manage-category.php');
    }
?>