<?php 
include('config/constants.php');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán Ăn Tại Chỗ</title>
    <link rel="stylesheet" href="css/eipay.css">
    <link rel="icon" 
      type="image/png" 
      href="../images/logo_ntx.png">
</head>
<body>

<div class="container">
  <div class="brand-logo"></div>
  <div class="brand-title">FLO_RESTAURANT</div>
  
<?php 
    if(isset($_SESSION['success']))
    {
        echo $_SESSION['success']; // Hiển thị thông báo thành công
        unset($_SESSION['success']); // Xóa thông báo thành công sau khi hiển thị
}
?>

<form action="<?php echo SITEURL; ?>" method="POST">
  <button type="submit" name="submit" class="btn btn-primary btn-lg">Trang Chủ</button> <!-- Nút quay lại trang chủ -->
</form>

</div>

</body>
</html>
