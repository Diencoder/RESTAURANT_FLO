<?php
// Bắt đầu session và kết nối cơ sở dữ liệu
session_start();
include('../frontend/config/constants.php');

// Kiểm tra quyền truy cập của người dùng
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ' . SITEURL . 'login.php');
    exit;
}

// Xử lý thêm mã giảm giá
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_promotion'])) {
    $promo_code = mysqli_real_escape_string($conn, $_POST['promo_code']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $discount_percent = mysqli_real_escape_string($conn, $_POST['discount_percent']);
    $discount_amount = mysqli_real_escape_string($conn, $_POST['discount_amount']);
    $valid_from = mysqli_real_escape_string($conn, $_POST['valid_from']);
    $valid_to = mysqli_real_escape_string($conn, $_POST['valid_to']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Kiểm tra xem mã giảm giá đã tồn tại chưa
    $check_promo = "SELECT * FROM tbl_promotions WHERE promo_code='$promo_code'";
    $res_check = mysqli_query($conn, $check_promo);

    if (mysqli_num_rows($res_check) > 0) {
        $_SESSION['add_error'] = "<div class='alert alert-danger'>Mã giảm giá đã tồn tại. Vui lòng thử lại với mã khác.</div>";
    } else {
        // Thêm mã giảm giá mới
        $insert_promotion = "INSERT INTO tbl_promotions (promo_code, description, discount_percent, discount_amount, valid_from, valid_to, status) 
                             VALUES ('$promo_code', '$description', '$discount_percent', '$discount_amount', '$valid_from', '$valid_to', '$status')";

        if (mysqli_query($conn, $insert_promotion)) {
            $_SESSION['add'] = "<div class='alert alert-success'>Mã giảm giá đã được thêm thành công!</div>";
        } else {
            $_SESSION['add_error'] = "<div class='alert alert-danger'>Có lỗi xảy ra khi thêm mã giảm giá. Vui lòng thử lại sau.</div>";
        }

        header("Location: manage-promotions.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-admin.css">
    <link rel="icon" type="image/png" href="../images/logo.png">
    <title>Thêm Mã Giảm Giá</title>
</head>
<body>

    <section id="content">
        <main class="container mt-5">
            <div class="head-title">
                <h1 class="mb-4">Thêm Mã Giảm Giá</h1>
            </div>

            <div class="add-promotion-form">
                <!-- Thông báo thành công và lỗi -->
                <?php
                if (isset($_SESSION['add'])) {
                    echo $_SESSION['add'];
                    unset($_SESSION['add']);
                }
                if (isset($_SESSION['add_error'])) {
                    echo $_SESSION['add_error'];
                    unset($_SESSION['add_error']);
                }
                ?>

                <!-- Form Thêm Mã Giảm Giá -->
                <form method="POST" action="add-promotion.php">
                    <div class="mb-3">
                        <label for="promo_code" class="form-label">Mã Giảm Giá:</label>
                        <input type="text" name="promo_code" id="promo_code" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô Tả:</label>
                        <textarea name="description" id="description" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="discount_percent" class="form-label">Giảm Giá Theo %:</label>
                        <input type="number" name="discount_percent" id="discount_percent" class="form-control" step="0.01">
                    </div>

                    <div class="mb-3">
                        <label for="discount_amount" class="form-label">Giảm Giá Theo Số Tiền:</label>
                        <input type="number" name="discount_amount" id="discount_amount" class="form-control" step="0.01">
                    </div>

                    <div class="mb-3">
                        <label for="valid_from" class="form-label">Thời Gian Bắt Đầu:</label>
                        <input type="datetime-local" name="valid_from" id="valid_from" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="valid_to" class="form-label">Thời Gian Kết Thúc:</label>
                        <input type="datetime-local" name="valid_to" id="valid_to" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng Thái:</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="Active">Active</option>
                            <option value="Expired">Expired</option>
                            <option value="Disabled">Disabled</option>
                        </select>
                    </div>

                    <button type="submit" name="add_promotion" class="btn btn-success">Thêm Mã Giảm Giá</button>
                    <a href="manage-promotions.php" class="btn btn-secondary ms-3">Quay lại</a>
                </form>
            </div>
        </main>
    </section>

</body>
</html>
