<?php include('header.php'); ?>

<head>
    <link href="css/style.css" rel="stylesheet">
    <link href="css/categories.css" rel="stylesheet">
    <link href="css/reservation_styles.css" rel="stylesheet">
   <
</head>
<!-- Danh mục sản phẩm Start -->
<section class="category">
    
    <div class="container">
        <div class="category-list">
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
            <div class="category-item">
                <img src="<?php echo SITEURL; ?>../images/category/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="category-image">
                <div class="category-content">
                    <h5 class="category-title"><?php echo $title; ?></h5>
                    <a href="<?php echo SITEURL; ?>category-foods.php?category_id=<?php echo $id; ?>" class="category-button">Khám Phá</a>
                </div>
            </div>
            <?php
                }
            }
            else
            {
                // Nếu không có danh mục sản phẩm
                echo '<p class="category-empty">Không tìm thấy danh mục nào</p>';
            }
            ?>
        </div>
    </div>
</section>
<!-- Danh mục sản phẩm End -->

<?php include('footer.php'); ?>