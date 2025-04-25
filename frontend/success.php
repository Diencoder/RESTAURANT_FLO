<?php include('config/constants.php'); ?>

<?php

if($_SERVER['REQUEST_METHOD'] == "POST") {

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
  <?php
    $tran_id = $_POST['mer_txnid'];
    $amount = $_POST['amount_original'];
    $status = $_POST['pay_status'];
    $pay_time = $_POST['pay_time'];
    $table_id = $_POST['cus_name'];
    $order_status = $_POST['cus_phone'];
    ?>

<div class="container">
  <div class="brand-logo"></div>
  <div class="brand-title">Robo Cafe</div>
  
  <form action="" class="inputs" method="POST" name="form1">
    <p>Thanh toán của bạn là <?php echo $amount; ?> đã thành công.</p>
    <p>Mã giao dịch: <?php echo $tran_id; ?>     
  </form>
  <?php 

        $sql = "INSERT INTO tbl_eipay SET
        amount='$amount',
        tran_id = '$tran_id',
        order_date = '$pay_time',
        payment_status = '$status',
        table_id = '$table_id',
        order_status = '$order_status'
    
    ";
    $res = mysqli_query($conn, $sql) or die(mysqli_error());
    
    if($res == true)
    { 
      $_SESSION['success'] = "Thanh toán của bạn là BDT " .$amount. " đã thành công."."<br/> Mã giao dịch: ".$tran_id;
      header('location:'.SITEURL.'payment-redir.php');
    }
    else
    {
      $_SESSION['fail'] = "Thanh toán của bạn là BDT " .$amount. " không thành công."."<br/> Mã giao dịch: ".$tran_id ."Vui lòng thử lại";
      header('location:'.SITEURL.'ei-pay.php'); 
    }
  } 
  ?>
  <button class="btn-secondary">Trang Chủ</button>
</div>

</body>
</html>
