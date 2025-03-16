<?php include('config/constants.php'); ?>

<?php

if($_SERVER['REQUEST_METHOD']=="POST"){

    // $paystatus=$_POST['pay_status'];
    // $amount=$_POST['amount'];
    
    // Bạn có thể lấy tất cả các tham số từ yêu cầu POST
   // print_r($_POST);

    //mer_txnid , amount_original, pay_status, pay_time
  

    
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
  <?php
    $tran_id = $_POST['mer_txnid'];
    $amount = $_POST['amount_original'];
    $status = $_POST['pay_status'];
    $pay_time = $_POST['pay_time'];
    $cus_name = $_POST['cus_name'];
    $cus_email = $_POST['cus_email'];
    $cus_phone = $_POST['cus_phone'];
    

    ?>

<div class="container">
  <div class="brand-logo"></div>
  <div class="brand-title">Robo Cafe</div>
  
  <form action="" class="inputs" method="POST" name="form1">
    <p>Xin chào, <?php echo $cus_name; ?>.</p>
    <p>Thanh toán của bạn trị giá <?php echo $amount; ?> đã thành công.</p>
    <p>Mã giao dịch: <?php echo $tran_id; ?> </p>
    <p>Thời gian thanh toán: <?php echo $pay_time; ?></p>    

    
  </form>
  <?php 


        $sql = "INSERT INTO tbl_order SET
        total='$amount',
        transaction_id = '$tran_id',
        
        order_date = '$pay_time',
        status = '$status',
        customer_name = '$cus_name',
        customer_contact = '$cus_phone',
        customer_email = '$cus_email'
    ";
    $res = mysqli_query($conn, $sql) or die(mysqli_error());
    
    if($res == true)
    { 
      $_SESSION['success'] = "Xin chào, " .$cus_name. ". Thanh toán của bạn trị giá BDT " .$amount. " đã thành công."."<br/> Mã giao dịch: ".$tran_id. ". Thời gian thanh toán: ".$pay_time;
      header('location:'.SITEURL.'payment-redir-front.php');
    }
    else
    {
      $_SESSION['fail'] = "Thanh toán của bạn trị giá BDT " .$amount. " không thành công."."<br/> Mã giao dịch: ".$tran_id ."Vui lòng thử lại";
      header('location:'.SITEURL); 
    }
    
  
  ?>
  <button class="btn-secondary">Trang Chủ</button>
</div>

</body>
</html>
