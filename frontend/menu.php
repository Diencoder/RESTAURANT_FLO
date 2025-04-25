<?php include('header.php'); ?>

<!-- Menu Start -->
<div class="container">
    <div class="row">
        <!-- Gợi Ý Món Ăn -->
        <h3>Gợi Ý Món Ăn Cho Bạn</h3>
        <div id="suggested-menu" class="row"></div>

        <script>
        fetch('suggest_menu.php')  // Gọi API để lấy gợi ý món ăn
            .then(response => response.json())
            .then(data => {
                let menuDiv = document.getElementById("suggested-menu");
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
                        <div class="col-lg-3">
                            <div class="card">
                                <img src="../images/food/${item.image_name}" class="card-img-top" alt="${item.title}">
                                <div class="card-body text-center">
                                    <h5 class="card-title">${item.title}</h5>
                                    <p class="card-text">${item.price} VND</p>
                                    <form action="manage-cart.php" method="POST">
                                        <button type="submit" name="Add_To_Cart" class="btn btn-primary btn-sm">Thêm vào giỏ hàng</button>
                                        <input type="hidden" name="Item_Name" value="${item.title}">
                                        <input type="hidden" name="Price" value="${item.price}">
                                        <input type="hidden" name="Id" value="${item.id}">
                                    </form>
                                </div>
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

        <!-- Món ăn trong menu -->
        <h3>Menu</h3>
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
                <div class="col-lg-3">
<div class="card">
                        <img src="<?php echo SITEURL; ?>../images/food/<?php echo $image_name; ?>" class="card-img-top" alt="...">
                        <div class="card-body text-center">
                            <form action="manage-cart.php" method="POST">
                                <h5 class="card-title"><?php echo $title; ?></h5>
                                <p class="card-text"><?php echo $price; ?></p>
                                <button type="submit" name="Add_To_Cart" class="btn btn-primary btn-sm">Thêm vào giỏ hàng</button>
                                <input type="hidden" name="Item_Name" value="<?php echo $title; ?>">
                                <input type="hidden" name="Price" value="<?php echo $price; ?>">
                                <input type="hidden" name="Id" value="<?php echo $id; ?>">
                            </form>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "Categories not found";
        }
        ?>
    </div>
</div>

<!-- Menu Ends -->

<?php include('footer.php'); ?>