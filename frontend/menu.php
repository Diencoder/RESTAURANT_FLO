<?php include('header.php'); ?>

<head>
    <link href="css/style.css" rel="stylesheet">
    <link href="css/menu.css" rel="stylesheet">
    <style>
        /* Phân trang */
        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination-button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            margin: 0 5px;
            border-radius: 5px;
        }

        .pagination-button:hover {
            background-color: #0056b3;
        }

        .pagination-number {
            display: inline-block;
            padding: 10px;
            margin: 0 5px;
            cursor: pointer;
        }

        .pagination-number.active {
            background-color: #007bff;
            color: white;
        }

        /* Thêm kiểu cho các món ăn */
        .menu-item {
            margin-bottom: 20px;
        }

        .menu-title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .menu-image {
            width: 100%;
            max-width: 200px;
            height: auto;
        }

        .menu-content {
            padding: 10px;
        }

        .menu-item-title {
            font-size: 18px;
            font-weight: bold;
        }

        .menu-item-price {
            font-size: 16px;
            color: #555;
        }

        .menu-button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        .menu-button:hover {
            background-color: #218838;
        }
    </style>
</head>

<!-- Menu Start -->
<section class="menu">
    <div class="container">

        <!-- Món ăn trong menu -->
        <h3 class="menu-title">Menu</h3>
        <div class="menu-list">
            <?php
            // Số món ăn hiển thị trên mỗi trang
            $items_per_page = 8;

            // Lấy trang hiện tại từ URL, nếu không có thì mặc định là trang 1
            if (isset($_GET['page'])) {
                $current_page = $_GET['page'];
            } else {
                $current_page = 1;
            }

            // Tính toán offset để lấy dữ liệu từ đúng trang
            $offset = ($current_page - 1) * $items_per_page;

            // Lấy các món ăn cho trang hiện tại
            $sql = "SELECT * FROM tbl_food WHERE active='Yes' LIMIT $offset, $items_per_page";
            $res = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($res);

            if ($count > 0) {
                while ($row = mysqli_fetch_assoc($res)) {
                    // Lấy thông tin món ăn
                    $id = $row['id'];
                    $title = $row['title'];
                    $price = $row['price'];
                    $image_name = $row['image_name'];
            ?>
                    <div class="menu-item">
                        <img src="<?php echo SITEURL; ?>../images/food/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="menu-image">
                        <div class="menu-content">
                            <h5 class="menu-item-title"><?php echo $title; ?></h5>
                            <p class="menu-item-price"><?php echo $price; ?> VND</p>
                            <form action="manage-cart.php" method="POST">
                                <button type="submit" name="Add_To_Cart" class="menu-button">Thêm vào giỏ hàng</button>
                                <input type="hidden" name="Item_Name" value="<?php echo $title; ?>">
                                <input type="hidden" name="Price" value="<?php echo $price; ?>">
                                <input type="hidden" name="Id" value="<?php echo $id; ?>">
                            </form>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<p class="menu-empty">Không tìm thấy món ăn nào</p>';
            }

            // Tính tổng số món ăn để tính số trang
            $sql_total = "SELECT COUNT(*) AS total FROM tbl_food WHERE active='Yes'";
            $res_total = mysqli_query($conn, $sql_total);
            $row_total = mysqli_fetch_assoc($res_total);
            $total_items = $row_total['total'];
            $total_pages = ceil($total_items / $items_per_page);
            ?>

        </div>

        <!-- Phân trang -->
        <div class="pagination">
            <!-- Nút quay về trang đầu -->
            <?php if ($current_page > 1) : ?>
                <a href="?page=1" class="pagination-button">Đầu trang</a>
            <?php endif; ?>

            <!-- Nút trang trước -->
            <?php if ($current_page > 1) : ?>
                <a href="?page=<?php echo $current_page - 1; ?>" class="pagination-button">Trang trước</a>
            <?php endif; ?>

            <!-- Hiển thị số trang -->
            <?php
            // Số trang sẽ hiển thị tối đa 5 trang
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);

            for ($i = $start_page; $i <= $end_page; $i++) :
            ?>
                <span class="pagination-number <?php if ($i == $current_page) echo 'active'; ?>" onclick="window.location.href='?page=<?php echo $i; ?>'">
                    <?php echo $i; ?>
                </span>
            <?php endfor; ?>

            <!-- Nút trang sau -->
            <?php if ($current_page < $total_pages) : ?>
                <a href="?page=<?php echo $current_page + 1; ?>" class="pagination-button">Trang sau</a>
            <?php endif; ?>

            <!-- Nút quay về trang cuối -->
            <?php if ($current_page < $total_pages) : ?>
                <a href="?page=<?php echo $total_pages; ?>" class="pagination-button">Cuối trang</a>
            <?php endif; ?>
        </div>

    </div>
</section>
<!-- Menu End -->

<?php include('footer.php'); ?>
