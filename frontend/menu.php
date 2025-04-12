<?php include('header.php'); ?>
<head>
    <link href="css/style.css" rel="stylesheet">
    <link href="css/menu.css" rel="stylesheet">

</head>

<!-- Menu Start -->
<section class="menu">
    <div class="container">
        <!-- Gợi Ý Món Ăn -->
        <h3 class="menu-title">Gợi Ý Món Ăn Cho Bạn</h3>
        <div id="suggested-menu" class="menu-list"></div>

        <!-- Món ăn trong menu -->
        <h3 class="menu-title">Menu</h3>
        <div class="menu-list">
            <?php
            // Hiển thị các món ăn từ bảng tbl_food
            $sql = "SELECT * FROM tbl_food WHERE active='Yes'";
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
            ?>
        </div>
    </div>
</section>
<!-- Menu End -->

<script>
fetch('suggest_menu.php')  // Gọi API để lấy gợi ý món ăn
    .then(response => response.json())
    .then(data => {
        let menuDiv = document.getElement4Id("suggested-menu");
        menuDiv.innerHTML = "";  // Xóa dữ liệu cũ

        if (data.error) {
            menuDiv.innerHTML = `<p>${data.error}</p>`;
            return;
        }

        if (data.length === 0) {
            menuDiv.innerHTML = `<p>Không có món gợi ý nào.</p>`;
            return;
        }

        // Hiển thị danh sách món ăn gợi ý
        data.forEach(item => {
            menuDiv.innerHTML += `
                <div class="menu-item">
                    <img src="../images/food/${item.image_name}" alt="${item.title}" class="menu-image">
                    <div class="menu-content">
                        <h5 class="menu-item-title">${item.title}</h5>
                        <p class="menu-item-price">${item.price} VND</p>
                        <form action="manage-cart.php" method="POST">
                            <button type="submit" name="Add_To_Cart" class="menu-button">Thêm vào giỏ hàng</button>
                            <input type="hidden" name="Item_Name" value="${item.title}">
                            <input type="hidden" name="Price" value="${item.price}">
                            <input type="hidden" name="Id" value="${item.id}">
                        </form>
                    </div>
                </div>
            `;
        });
    })
    .catch(error => {
        console.error("Lỗi khi lấy dữ liệu:", error);
        document.getElementById("suggested-menu").innerHTML = "<p>Lỗi khi tải gợi ý món ăn.</p>";
    });
</script>

<?php include('footer.php'); ?>