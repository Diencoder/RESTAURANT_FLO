<?php
// Bắt đầu session và kết nối cơ sở dữ liệu
session_start();
include('../frontend/config/constants.php');

// Kiểm tra quyền truy cập của người dùng
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header('Location: ' . SITEURL . 'login.php');
    exit;
}

// Lấy ID của mã giảm giá cần sửa
if (isset($_GET['id'])) {
    $promo_id = $_GET['id'];
    
    // Lấy thông tin mã giảm giá từ cơ sở dữ liệu
    $sql = "SELECT * FROM tbl_promotions WHERE id='$promo_id'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['edit_error'] = "<div class='alert alert-danger'>Không tìm thấy mã giảm giá này.</div>";
        header('Location: manage-promotions.php');
        exit();
    }
}

// Xử lý sửa mã giảm giá
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_promotion'])) {
    $promo_code = mysqli_real_escape_string($conn, $_POST['promo_code']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $discount_percent = mysqli_real_escape_string($conn, $_POST['discount_percent']);
    $discount_amount = mysqli_real_escape_string($conn, $_POST['discount_amount']);
    $valid_from = mysqli_real_escape_string($conn, $_POST['valid_from']);
    $valid_to = mysqli_real_escape_string($conn, $_POST['valid_to']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Cập nhật thông tin mã giảm giá
    $update_query = "UPDATE tbl_promotions SET promo_code='$promo_code', description='$description', 
                     discount_percent='$discount_percent', discount_amount='$discount_amount', 
                     valid_from='$valid_from', valid_to='$valid_to', status='$status' 
                     WHERE id='$promo_id'";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['edit'] = "<div class='alert alert-success'>Mã giảm giá đã được sửa thành công!</div>";
    } else {
        $_SESSION['edit_error'] = "<div class='alert alert-danger'>Có lỗi xảy ra khi sửa mã giảm giá. Vui lòng thử lại sau.</div>";
    }

    header("Location: manage-promotions.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-admin.css">
    <link rel="icon" type="image/png" href="../images/logo.png">
    <title>Sửa Mã Giảm Giá</title>
</head>
<body>

<section id="content">
    <main class="container mt-5">
        <div class="head-title">
            <h1>Sửa Mã Giảm Giá</h1>
        </div>

        <!-- Thông báo thành công và lỗi -->
        <?php
        if (isset($_SESSION['edit'])) {
            echo $_SESSION['edit'];
            unset($_SESSION['edit']);
        }
        if (isset($_SESSION['edit_error'])) {
            echo $_SESSION['edit_error'];
            unset($_SESSION['edit_error']);
        }
        ?>

        <div class="edit-promotion-form">
            <!-- Form Sửa Mã Giảm Giá -->
            <form method="POST" action="edit-promotion.php?id=<?php echo $promo_id; ?>">
                <div class="mb-3">
                    <label for="promo_code" class="form-label">Mã Giảm Giá:</label>
                    <input type="text" name="promo_code" id="promo_code" class="input-field" value="<?php echo $row['promo_code']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô Tả:</label>
                    <textarea name="description" id="description" class="input-field" required><?php echo $row['description']; ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="discount_percent" class="form-label">Giảm Giá Theo %:</label>
                    <input type="number" name="discount_percent" id="discount_percent" class="input-field" step="0.01" value="<?php echo $row['discount_percent']; ?>">
                </div>

                <div class="mb-3">
                    <label for="discount_amount" class="form-label">Giảm Giá Theo Số Tiền:</label>
                    <input type="number" name="discount_amount" id="discount_amount" class="input-field" step="0.01" value="<?php echo $row['discount_amount']; ?>">
                </div>

                <div class="mb-3">
                    <label for="valid_from" class="form-label">Thời Gian Bắt Đầu:</label>
                    <input type="datetime-local" name="valid_from" id="valid_from" class="input-field" value="<?php echo date('Y-m-d\TH:i', strtotime($row['valid_from'])); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="valid_to" class="form-label">Thời Gian Kết Thúc:</label>
                    <input type="datetime-local" name="valid_to" id="valid_to" class="input-field" value="<?php echo date('Y-m-d\TH:i', strtotime($row['valid_to'])); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Trạng Thái:</label>
                    <select name="status" id="status" class="input-field" required>
                        <option value="Active" <?php echo ($row['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                        <option value="Expired" <?php echo ($row['status'] == 'Expired') ? 'selected' : ''; ?>>Expired</option>
                        <option value="Disabled" <?php echo ($row['status'] == 'Disabled') ? 'selected' : ''; ?>>Disabled</option>
                    </select>
                </div>

                <button type="submit" name="edit_promotion" class="button-5">Sửa Mã Giảm Giá</button>
                <a href="manage-promotions.php" class="button-7">Quay lại</a>
            </form>
        </div>
    </main>
</section>

</body>
</html>
