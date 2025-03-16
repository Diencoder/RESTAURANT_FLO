<?php include('header.php'); ?>


  <!-- Menu Start -->

<div class="container">
    <div class="row">
        
        <?php
        // Truy vấn để lấy các món ăn với trạng thái 'Yes' (hoạt động)
        $sql = "SELECT * FROM tbl_food WHERE active='Yes'";
        $res = mysqli_query($conn, $sql); // Thực hiện truy vấn
        $count = mysqli_num_rows($res); // Kiểm tra số lượng kết quả trả về

        // Kiểm tra nếu có món ăn
        if($count > 0)
        {
            // Lặp qua từng món ăn
            while($row = mysqli_fetch_assoc($res))
            {
                // Lấy thông tin của từng món ăn
                $id = $row['id'];
                $title = $row['title'];
                $price = $row['price'];
                $image_name = $row['image_name'];
        ?>
        
        <div class="col-lg-3">
            <div class="card">
                <!-- Hiển thị hình ảnh của món ăn -->
                <img src="<?php echo SITEURL; ?>../images/food/<?php echo $image_name; ?>" class="card-img-top" alt="...">
                <div class="card-body text-center">
                    <form action="manage-cart.php" method="POST">
                        <!-- Tiêu đề món ăn -->
                        <h5 class="card-title"><?php echo $title; ?></h5>
                        <!-- Giá món ăn -->
                        <p class="card-text"><?php echo $price; ?> VND</p>
                        <!-- Nút để thêm món ăn vào giỏ hàng -->
                        <button type="submit" name="Add_To_Cart" class="btn btn-primary btn-sm">Thêm Vào Giỏ</button>
                        <!-- Các trường ẩn để lưu thông tin món ăn khi gửi dữ liệu -->
                        <input type="hidden" name="Item_Name" value="<?php echo $title; ?>">
                        <input type="hidden" name="Price" value="<?php echo $price; ?>">
                        <input type="hidden" name="Id" value="<?php echo $id; ?>">
                    </form>
                </div>
            </div>
        </div>

        <?php
            }
        }
        else
        {
            // Nếu không có món ăn
            echo "Không tìm thấy món ăn nào";
        }
        ?>
        
    </div>
</div>

<!-- Menu End -->

        <?php include('footer.php'); ?>