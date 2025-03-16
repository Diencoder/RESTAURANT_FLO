<?php include('header.php'); ?>

<!-- Danh mục sản phẩm Start -->
<div class="container">
    <div class="row">
        
        <?php
        // Truy vấn để lấy danh mục sản phẩm với trạng thái 'Yes' (hoạt động)
        $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
        $res = mysqli_query($conn, $sql); // Thực hiện truy vấn
        $count = mysqli_num_rows($res); // Kiểm tra số lượng kết quả trả về

        // Kiểm tra nếu có danh mục sản phẩm
        if($count > 0)
        {
            // Lặp qua từng danh mục sản phẩm
            while($row = mysqli_fetch_assoc($res))
            {
                // Lấy thông tin của mỗi danh mục
                $id = $row['id'];
                $title = $row['title'];
                $image_name = $row['image_name'];
        ?>
        
        <div class="col-lg-3">
            <div class="card">
                <!-- Hiển thị hình ảnh của danh mục -->
                <img src="<?php echo SITEURL; ?>../images/category/<?php echo $image_name; ?>" class="card-img-top" alt="...">
                <div class="card-body text-center">
                    <!-- Tiêu đề danh mục -->
                    <h5 class="card-title"><?php echo $title; ?></h5>
                    <!-- Nút để người dùng có thể xem các món ăn trong danh mục -->
                    <a href="<?php echo SITEURL; ?>category-foods.php?category_id=<?php echo $id; ?>">
                        <button class="btn btn-primary btn-sm">Khám Phá </button>
                    </a>
                </div>
            </div>
        </div>

        <?php
            }
        }
        else
        {
            // Nếu không có danh mục sản phẩm
            echo "Không tìm thấy danh mục nào";
        }
        ?>
        
    </div>
</div>

<!-- Danh mục sản phẩm End -->

<?php include('footer.php'); ?>
