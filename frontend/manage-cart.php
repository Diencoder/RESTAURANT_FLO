<?php include('config/constants.php'); ?> 
<?php

// Kiểm tra nếu có yêu cầu gửi từ phương thức POST
if($_SERVER["REQUEST_METHOD"]=="POST")
{
    // Kiểm tra nếu nút "Thêm vào giỏ hàng" được nhấn
    if(isset($_POST['Add_To_Cart']))
    {
        // Kiểm tra xem giỏ hàng có tồn tại không
        if(isset($_SESSION['cart']))
        {
            // Lấy tất cả tên món ăn có trong giỏ hàng
            $myitems = array_column($_SESSION['cart'],'Item_Name');
            
            // Kiểm tra xem món ăn đã có trong giỏ hàng chưa
            if(in_array($_POST['Item_Name'], $myitems))
            {
                // Nếu món ăn đã có trong giỏ hàng, hiển thị thông báo
                echo "<script>
                alert('Món ăn đã có trong giỏ hàng'); 
                window.location.href='menu.php';
                </script>"; 
                 
            }
            else
            {
                // Nếu món ăn chưa có trong giỏ hàng, thêm vào giỏ hàng
                $count = count($_SESSION['cart']); 
                $_SESSION['cart'][$count] = array('Item_Name'=>$_POST['Item_Name'],'Price'=>$_POST['Price'],'Id'=>$_POST['Id'],'Quantity'=>1);
                
                // Hiển thị thông báo đã thêm vào giỏ hàng
                echo "<script>
                alert('Đã thêm vào giỏ hàng'); 
                window.location.href='menu.php';
                </script>";
                
            }

        }
        else
        {
            // Nếu giỏ hàng chưa tồn tại, tạo giỏ hàng mới và thêm món vào giỏ
            $_SESSION['cart'][0]=array('Item_Name'=>$_POST['Item_Name'],'Price'=>$_POST['Price'],'Id'=>$_POST['Id'],'Quantity'=>1);
            
            // Hiển thị thông báo đã thêm vào giỏ hàng
            echo "<script>
                alert('Đã thêm vào giỏ hàng'); 
                window.location.href='menu.php';
                </script>";
                
        }

    }

    // Kiểm tra nếu nút "Xóa món khỏi giỏ hàng" được nhấn
    if(isset($_POST['Remove_Item']))
    {
        // Duyệt qua các món trong giỏ hàng để tìm món cần xóa
        foreach($_SESSION['cart'] as $key => $value)
        {
            // Nếu tên món ăn trong giỏ hàng trùng với tên món cần xóa
            if($value['Item_Name']==$_POST['Item_Name'])
            {
                // Xóa món khỏi giỏ hàng
                unset($_SESSION['cart'][$key]);
                
                // Lấy lại chỉ số giỏ hàng để tránh bị sai lệch chỉ số
                $_SESSION['cart']=array_values($_SESSION['cart']);
                
                // Hiển thị thông báo đã xóa món khỏi giỏ hàng
                echo "<script>
                alert('Đã xóa món khỏi giỏ hàng');
                window.location.href='mycart.php';
                </script>";
            }
        }
    }

    // Kiểm tra nếu nút "Cập nhật số lượng món ăn" được nhấn
    if(isset($_POST['Mod_Quantity']))
    {
        // Duyệt qua các món trong giỏ hàng để tìm món cần cập nhật số lượng
        foreach($_SESSION['cart'] as $key => $value)
        {
            // Nếu tên món ăn trong giỏ hàng trùng với tên món cần cập nhật
            if($value['Item_Name']==$_POST['Item_Name'])
            {
                // Cập nhật số lượng món ăn trong giỏ hàng
                $_SESSION['cart'][$key]['Quantity']=$_POST['Mod_Quantity'];
                
                // Hiển thị thông báo đã cập nhật giỏ hàng
                echo "<script>
                alert('Giỏ hàng đã được cập nhật');
                window.location.href='mycart.php';
                </script>";
            }
        } 
    }
}

?>
