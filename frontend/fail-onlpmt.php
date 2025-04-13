<?php include('config/constants.php'); ?>

<?php

if($_SERVER['REQUEST_METHOD']=="POST"){

    // $paystatus=$_POST['pay_status'];
    // $amount=$_POST['amount'];
    
    // Bạn có thể lấy tất cả các tham số từ yêu cầu POST
    //print_r($_POST);

    //mer_txnid , amount_original, pay_status, pay_time
    
    $tran_id = $_POST['mer_txnid'];
    $amount = $_POST['amount_original'];
    $status = $_POST['pay_status'];
    $pay_time = $_POST['pay_time'];
    $customer_name = $_POST['cus_name'];
    $card_type = $_POST['card_type'];

    
}
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

    // Lưu thông báo thanh toán thất bại vào session và chuyển hướng
    $_SESSION['fail'] = "Thanh toán của bạn trị giá BDT " .$amount. " không thành công"."<br/>Vui lòng thử lại";
    header('location:'.SITEURL.'payment-fail-redir.php'); 

  
  ?>
  
  
  
</div>

</body>
</html>
