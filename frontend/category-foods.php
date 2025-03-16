<?php include('header.php'); ?>


       


        <!-- Menu Start -->
        
        <?php 
        //Check whether category id is passed or not
        if(isset($_GET['category_id']))
        {
            //Category id is set and get the id
            $category_id = $_GET['category_id'];
            //Get the category title based on category ID
            $sql = "SELECT title FROM tbl_category WHERE id=$category_id";
            $res = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($res);
            $category_title = $row['title']; 

        }
        else
        {
            //Category id not passed
            //Redirect to homepage
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
            //Item Available
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
                                    <button type="submit" name="Add_To_Cart" class="btn btn-primary btn-sm">Add To Cart</button>
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
            //Item Not Available
        }
    
        
        ?>

                    </div>
        </div>



                        
        <!-- Menu End   -->
        

<?php include('footer.php'); ?>