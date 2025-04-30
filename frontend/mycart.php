<?php
// Bao gồm kết nối cơ sở dữ liệu
include('config/constants.php');

// Khởi tạo biến tổng tiền
$total_amount = 0;

// Kiểm tra xem giỏ hàng có tồn tại không
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    // Duyệt qua từng sản phẩm trong giỏ hàng và tính tổng tiền
    foreach ($_SESSION['cart'] as $key => $value) {
        $item_total = $value['Price'] * $value['Quantity'];  // Tính tổng tiền cho sản phẩm hiện tại
        $total_amount += $item_total;  // Cộng vào tổng tiền
    }
} else {
    $total_amount = 0;  // Nếu giỏ hàng rỗng, tổng tiền là 0
}

// Kiểm tra xem người dùng có áp dụng mã giảm giá không
if (isset($_POST['apply_discount'])) {
    $promo_code = $_POST['promo_code'];

    // Nếu người dùng không nhập mã giảm giá, xóa thông tin giảm giá
    if (empty($promo_code)) {
        unset($_SESSION['discount_amount']);
        unset($_SESSION['total_after_discount']);
    } else {
        // Dùng prepared statement để tránh SQL Injection
        $sql = "SELECT * FROM tbl_promotions WHERE promo_code = ? AND status = 'Active' AND valid_from <= NOW() AND valid_to >= NOW()";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $promo_code); // Bind mã giảm giá vào câu truy vấn
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Kiểm tra mã giảm giá có hợp lệ không
        if (mysqli_num_rows($result) > 0) {
            // Mã giảm giá hợp lệ
            $row = mysqli_fetch_assoc($result);
            $discount_amount = 0;

            // Tính toán giảm giá
            if ($row['discount_percent'] > 0) {
                // Giảm giá theo phần trăm
                $discount_amount = ($total_amount * $row['discount_percent']) / 100;
            } else if ($row['discount_amount'] > 0) {
                // Giảm giá theo số tiền
                $discount_amount = $row['discount_amount'];
            }

            // Cập nhật tổng tiền sau giảm giá
            $total_after_discount = $total_amount - $discount_amount;

            // Lưu giá trị vào session để hiển thị lại trên trang giỏ hàng
            $_SESSION['discount_amount'] = $discount_amount;
            $_SESSION['total_after_discount'] = $total_after_discount;
        } else {
            // Mã giảm giá không hợp lệ
            echo "<script>alert('Mã giảm giá không hợp lệ hoặc đã hết hạn');</script>";
            unset($_SESSION['discount_amount']);
            unset($_SESSION['total_after_discount']);
        }
        // Đóng statement để giải phóng bộ nhớ
        mysqli_stmt_close($stmt);
    }
}



// Sau khi mã giảm giá được áp dụng, không cần phải reset khi thanh toán nữa
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>FLO_RESTAURANT</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logo.png">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&family=Pacifico&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/my_account.css" rel="stylesheet">
 

   
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="spinner">
        <div class="spinner-circle"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar & Hero Start -->
    <header class="header">
        <nav class="navbar">
            <div class="container navbar-inner">
                <a href="<?php echo SITEURL; ?>" class="navbar-logo">
                    <img src="../images/logo.png" alt="Logo">
                </a>
                <button class="navbar-toggle">
                    <i class="fa fa-bars"></i>
                </button>
                <div class="navbar-menu">
                    <a href="index.php" class="navbar-item">Trang chủ</a>
                    <a href="about.php" class="navbar-item">Giới thiệu</a>
                    <a href="categories.php" class="navbar-item">Danh mục</a>
                    <a href="menu.php" class="navbar-item">Thực đơn</a>
                    <div class="navbar-dropdown">
                        <a href="#" class="navbar-dropdown-toggle navbar-item">Các trang</a>
                        <div class="navbar-dropdown-menu">
                            <a href="team.php" class="navbar-dropdown-item">Đội ngũ</a>
                            <a href="testimonial.php" class="navbar-dropdown-item">Lời chứng thực</a>
                        </div>
                    </div>
                    <a href="contact.php" class="navbar-item">Liên hệ</a>
                    <?php
                    if (isset($_SESSION['user'])) {
                        $username = $_SESSION['user'];
                    ?>
                        <div class="navbar-dropdown">
                            <a href="#" class="navbar-dropdown-toggle navbar-item"><?php echo $username; ?></a>
                            <div class="navbar-dropdown-menu">
                                <a href="myaccount.php" class="navbar-dropdown-item">Tài khoản của tôi</a>
                                <a href="logout.php" class="navbar-dropdown-item">Đăng xuất</a>
                            </div>
                        </div>
                    <?php
                    } else {
                    ?>
                        <a href="login.php" class="navbar-item">Đăng nhập</a>
                    <?php
                    }
                    ?>
                    <?php
                    $count = 0;
                    if (isset($_SESSION['cart'])) {
                        $count = count($_SESSION['cart']);
                    }
                    ?>
                    <a href="mycart.php" class="navbar-cart">
                        <i class="fas fa-shopping-cart"></i> Giỏ hàng (<?php echo $count; ?>)
                    </a>
                </div>
            </div>
        </nav>
        <div class="hero-header">
            <div class="container">
                <h1 class="hero-title animated slideInDown">Tài khoản của tôi</h1>
                <nav class="breadcrumb">
                    <a href="index.php" class="breadcrumb-item">Trang chủ</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-item active">Tài khoản của tôi</span>
                </nav>
            </div>
        </div>
    </header>
    <!-- Navbar & Hero End -->


        <div class="container">
    <div class="row">
        <!-- Giỏ hàng -->
        <div class="col-lg-9 table-responsive">
            <table class="table table-bordered table-hover" id="cart_table">
                <thead class="thead-light text-center">
                    <tr>
                        <th scope="col">STT</th>
                        <th scope="col">Tên Món</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Số Lượng</th>
                        <th scope="col">Tổng Cộng</th>
                        <th scope="col">Hành Động</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php 
                    $item_price = 0;
                    $total_amount = 0;

                    if (isset($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $key => $value) {
                            $item_price = $value['Price'] * $value['Quantity'];
                            $total_amount = $total_amount + $item_price;

                            $sn = $key + 1;

                            echo "
                            <tr>
                                <td>$sn</td>
                                <td>$value[Item_Name]</td>
                                <td>$value[Price]<input type='hidden' class='iprice' value='$value[Price]'></td>
                                <td>
                                    <form action='manage-cart.php' method='POST'>
                                        <input class='text-center iquantity form-control' name='Mod_Quantity' onchange='this.form.submit();' type='number' value='$value[Quantity]' min='1' max='20'>
                                        <input type='hidden' name='Item_Name' value='$value[Item_Name]'>
                                    </form>
                                </td>
                                <td class='itotal'>$item_price VND</td>
                                <td>
                                    <form action='manage-cart.php' method='POST'>
                                        <button name='Remove_Item' class='btn btn-danger btn-sm'>XÓA</button>
                                        <input type='hidden' name='Item_Name' value='$value[Item_Name]'>
                                    </form>
                                </td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>  

        <!-- Tổng tiền và mã giảm giá -->
        <div class="col-lg-3">
            <div class="border bg-light rounded p-4">
                <h4 class="text-center">Tổng tiền</h4>
                <h2 class="text-center" id="gtotal"><?php echo $total_amount; ?> VND</h2>
                <br>

                <!-- Form nhập mã giảm giá -->
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="promo_code">Nhập mã giảm giá:</label>
                        <input type="text" name="promo_code" id="promo_code" class="form-control" placeholder="Nhập mã giảm giá">
                        <button type="submit" class="btn btn-secondary mt-2" name="apply_discount">Áp dụng</button>
                    </div>
                </form>

                <!-- Hiển thị số tiền giảm giá -->
                <div class="form-group">
                    <label for="discount_amount">Giảm giá:</label>
                    <input type="text" id="discount_amount" class="form-control" value="<?php echo isset($_SESSION['discount_amount']) ? $_SESSION['discount_amount'] . " VND" : "0 VND"; ?>" readonly>
                </div>

                <!-- Hiển thị tổng tiền sau khi áp dụng mã giảm giá -->
                <div class="form-group">
                    <label for="total_after_discount">Tổng tiền sau giảm giá:</label>
                    <input type="text" id="total_after_discount" class="form-control" value="<?php echo isset($_SESSION['total_after_discount']) ? $_SESSION['total_after_discount'] . " VND" : $total_amount . " VND"; ?>" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
   
                <br>

                <?php
                if (isset($_POST['purchase'])) {
                    // Reset thông tin giảm giá sau khi thanh toán
                    unset($_SESSION['discount_amount']);
                    unset($_SESSION['total_after_discount']);
                    
                    // Tiếp tục với quá trình thanh toán (ví dụ: chuyển đến trang thanh toán Aamarpay)
                    // Chuyển hướng hoặc xử lý thanh toán ở đây
                }
                if(isset($_SESSION['user']))
                {
                    $username = $_SESSION['user'];

                    $fetch_user = "SELECT * FROM tbl_users WHERE username = '$username'";
                    $res_fetch_user = mysqli_query($conn, $fetch_user);
                     while($rows=mysqli_fetch_assoc($res_fetch_user))
                     {
                        $username = $rows['username'];
                        $cus_name = $rows['name'];
                        $cus_email = $rows['email'];
                        $cus_add1 = $rows['add1'];
                        $cus_city = $rows['city'];
                        $cus_phone = $rows['phone'];
                        
                     }

                    if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0)
                    {
                        
                
                ?>
    
                <?php
                error_reporting(0);
                date_default_timezone_set('Asia/Dhaka');
                //Generate Unique Transaction ID
                function rand_string( $length ) {
	            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	

	            $size = strlen( $chars );
	            for( $i = 0; $i < $length; $i++ ) {
		        $str .= $chars[ rand( 0, $size - 1 ) ];
	            }

	            return $str;
                }
                $cur_random_value=rand_string(10);

                ?> 


<form action="purchase.php" method="POST">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Các trường ẩn cho số tiền và mã giao dịch -->
                <input type="hidden" name="amount" value="<?php echo $total_amount; ?>" class="form-control">
                <input type="hidden" name="tran_id" value="ONL-PAY-<?php echo "$cur_random_value"; ?>" class="form-control">
                
                <!-- Phần Địa chỉ giao hàng -->
                <h4 class="text-center mb-4">Địa Chỉ Giao Hàng</h4>

                <div class="form-group">
                    <label for="cus_name">Tên Khách Hàng</label>
                    <input type="text" id="cus_name" value="<?php echo $cus_name; ?>" class="form-control" readonly>
                    <input type="hidden" name="cus_name" value="<?php echo $cus_name; ?>" required>
                </div>

                <div class="form-group">
                    <label for="cus_email">Email</label>
                    <input type="email" id="cus_email" value="<?php echo $cus_email; ?>" class="form-control" readonly>
                    <input type="hidden" name="cus_email" value="<?php echo $cus_email; ?>" required>
                </div>

                <div class="form-group">
                    <label for="cus_add1">Địa Chỉ</label>
                    <input type="text" id="cus_add1" value="<?php echo $cus_add1; ?>" class="form-control" readonly>
                    <input type="hidden" name="cus_add1" value="<?php echo $cus_add1; ?>" required>
                </div>

                <div class="form-group">
                    <label for="cus_city">Thành Phố</label>
                    <input type="text" id="cus_city" value="<?php echo $cus_city; ?>" class="form-control" readonly>
                    <input type="hidden" name="cus_city" value="<?php echo $cus_city; ?>" required>
                </div>

                <div class="form-group">
                    <label for="cus_phone">Số Điện Thoại</label>
                    <input type="tel" id="cus_phone" value="<?php echo $cus_phone; ?>" class="form-control" readonly>
                    <input type="hidden" name="cus_phone" value="<?php echo $cus_phone; ?>" required>
                </div>

                <!-- Trạng thái thanh toán và tên người dùng -->
                <input type="hidden" name="payment_status" value="pending">
                <input type="hidden" name="username" value="<?php echo $username; ?>">

                <!-- Liên kết thay đổi địa chỉ giao hàng -->
                <div class="text-center mt-2">
                    <a href="update-account.php" class="btn btn-link">Thay Đổi Địa Chỉ Giao Hàng</a>
                </div>

                <!-- Chọn phương thức thanh toán -->
                <div class="form-check mt-3">
                    <input class="form-check-input" type="radio" name="pay_mode" value="amrpay" id="flexRadioDefault1" required>
                    <label class="form-check-label" for="flexRadioDefault1">
                        Thanh Toán Với AAMARPAY
                    </label>
                </div>

                <!-- Nút thanh toán -->
                <div class="text-center mt-4">
                    <button class="btn btn-warning btn-lg w-100" name="purchase">Thanh Toán</button>
                </div>
            </div>
        </div>
    </div>
</form>


                <!-- Creating Session Variables --->
                 <?php

                  $_SESSION['amount']=$total_amount;
                 ?>
  
                <?php
                        
                        
                
                    }
                }
                else
                {
                    echo "<div class='container mt-4'>
                            <div class='row'>
                                <div class='col-md-12 text-center'>
                                    <h3>Vui lòng đăng nhập để đặt hàng</h3>
                                    <a href='login.php' class='btn btn-primary'>Đăng nhập</a>
                                </div>
                            </div>
                        </div>";
                }
                ?>
            
          
                </div>
            </div>
        </div>

        </div>

    </div>

    <?php include('footer.php'); ?>

    <script>
        var gt=0;
        var iprice=document.getElementsByClassName('iprice');
        var iquantity=document.getElementsByClassName('iquantity');
        var itotal=document.getElementsByClassName('itotal');
        var igtotal=document.getElementById('gtotal');

        function subTotal()
        {
            gt=0;
            for(i=0;i<iprice.length;i++)
            {
                itotal[i].innerText=(iprice[i].value)*(iquantity[i].value);

                gt=gt+(iprice[i].value)*(iquantity[i].value);
            }
            gtotal.innerText=gt;
        }

        subTotal();


    </script>
</body>

</html>