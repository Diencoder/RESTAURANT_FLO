<?php 
include('config/constants.php');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <link rel="stylesheet" href="css/eipay.css">
    <link rel="icon" 
      type="image/png" 
      href="../images/logo.png">
</head>
<body>

<div class="container">
  <div class="brand-logo"></div>
    <div class="brand-title">FLO_RESTAURANT</div>
  
<?php 
      if(isset($_SESSION['success']))
        {
          echo $_SESSION['success']; // Hiển thị thông báo thành công
          unset($_SESSION['success']); // Xóa thông báo sau khi hiển thị
         }
         //unset($_SESSION['cart']); // Xóa giỏ hàng nếu cần thiết
?>
  <form action="<?php echo SITEURL; ?>index.php">
    <button>Trang Chủ</button> <!-- Nút quay lại trang chủ -->
  </form>
      </div>
    </div>
  </div>

</body>
</html>
