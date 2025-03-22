<?php include('header.php'); ?>

<!-- Menu Start -->

<?php 
// Kiểm tra xem id danh mục có được truyền vào không
if(isset($_GET['category_id']))
{
    // id danh mục đã được truyền vào và lấy id
    $category_id = $_GET['category_id'];
    // Lấy tiêu đề danh mục dựa trên id danh mục
    $sql = "SELECT title FROM tbl_category WHERE id=$category_id";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    $category_title = $row['title']; 
}
else
{
    // id danh mục chưa được truyền vào
    // Chuyển hướng về trang chủ
    header('location:'.SITEURL);
}
?>
<div class="container">
    <div class="row">

<?php
$sql2 = "SELECT * FROM tbl_food WHERE category_id=$category_id";
$res2 = mysqli_query($conn,$sql2);
$count2 = mysqli_num_rows($res2);

if($count2>0)
{
    // Có món ăn
    while($row2=mysqli_fetch_assoc($res2))
    {
         $id = $row2['id'];
         $title = $row2['title'];
         $price = $row2['price'];
         $description = $row2['description'];
         $image_name = $row2['image_name'];

         ?>

         <div class="col-lg-3">
                    <div class="card">
                        <img src="<?php echo SITEURL; ?>../images/food/<?php echo $image_name; ?>" class="card-img-top" alt="..." >
                        <div class="card-body text-center">
                            <form action="manage-cart.php" method="POST">
                            <h5 class="card-title"><?php echo $title; ?></h5>
                            <p class="card-text"><?php echo $price; ?></p>
                            <button type="submit" name="Add_To_Cart" class="btn btn-primary btn-sm">Thêm vào giỏ hàng</button>
                            <input type="hidden" name="Item_Name" value="<?php echo $title; ?>">
                            <input type="hidden" name="Price" value="<?php echo $price; ?>">
                            </form>
                        </div>
                    </div>
                </div>

         <?php
    }
}
else
{
    // Không có món ăn
}

?>

            </div>
</div>

<!-- Menu End   -->

<?php include('footer.php'); ?>
