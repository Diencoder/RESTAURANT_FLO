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

  <?php  // Thực hiện việc lưu thông tin thanh toán vào cơ sở dữ liệu

        $sql = "INSERT INTO aamarpay SET
        cus_name='$customer_name',
        amount='$amount',
        status = '$status',
        pay_time = '$pay_time',
        transaction_id = '$tran_id',
        card_type = '$card_type'";

    $res = mysqli_query($conn, $sql) or die(mysqli_error());
    
    if($res == true)
    { 
      $_SESSION['success'] =  "Kính gửi " .$customer_name. ", Thanh toán của bạn trị giá BDT " .$amount. " đã thành công."."<br/> Mã giao dịch: ".$tran_id;
      header('location:'.SITEURL.'payment-redir.php');
    }
    else
    {
      $_SESSION['fail'] = "Thanh toán của bạn trị giá BDT " .$amount. " không thành công."."<br/> Mã giao dịch: ".$tran_id ."Vui lòng thử lại";
      header('location:'.SITEURL.'index.php'); 
    }
  ?>

</div>

</body>
</html>
