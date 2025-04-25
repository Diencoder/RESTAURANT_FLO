<?php
include('config/constants.php'); // Kết nối với cơ sở dữ liệu

// Truy vấn lấy tất cả các chương trình khuyến mãi
$sql = "SELECT * FROM tbl_promotions";
$result = mysqli_query($conn, $sql);

// Kiểm tra kết quả
if (mysqli_num_rows($result) > 0) {
    // Hiển thị các mã giảm giá
    echo "<table border='1'>";
    echo "<tr><th>Mã Giảm Giá</th><th>Mô Tả</th><th>Trạng Thái</th><th>Ngày Bắt Đầu</th><th>Ngày Kết Thúc</th><th>Thao Tác</th></tr>";
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>" . $row['promo_code'] . "</td>
                <td>" . $row['description'] . "</td>
                <td>" . $row['status'] . "</td>
                <td>" . $row['valid_from'] . "</td>
                <td>" . $row['valid_to'] . "</td>
                <td>
                    <a href='update-promotion-status.php?promo_code=" . $row['promo_code'] . "&status=Expired'>Đổi trạng thái hết hạn</a> | 
                    <a href='update-promotion-status.php?promo_code=" . $row['promo_code'] . "&status=Disabled'>Vô hiệu hóa</a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "Không có mã giảm giá nào!";
}
?>
