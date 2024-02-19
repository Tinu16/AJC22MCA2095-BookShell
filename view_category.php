<?php 
session_start();
    include("../dbcon.php");
    include("../includes/header.php");
    include("../includes/admin_sidebar.php");
    include("../includes/topbar.php");
    include("../message.php");


if (isset($_POST['activate_subcategory_btn'])) {
    $subcategory_id = $_POST['status_id'];
    $query = "UPDATE tbl_subcategory SET subcategory_status = 1 WHERE subcategory_id = $subcategory_id";
    mysqli_query($con, $query);

}

if (isset($_POST['deactivate_subcategory_btn'])) {
    $subcategory_id = $_POST['status_id'];
    $query = "UPDATE tbl_subcategory SET subcategory_status = 0 WHERE subcategory_id = $subcategory_id";
    mysqli_query($con, $query);
}
?>
  <div class="container-fluid">

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h3 class="m-0 font-weight-bold text-primary">Category</h3>
        <br>
        <form action="subcategory.php">
        <button type="submit"  class="close" margin-top="1" >
            <div class="btn btn-secondary">
                 Add Sub Categories
            </div>
        </button>
        
        </form>
        <form action="category.php" >
        <button type="submit"  class="close"  >
            <div class="btn btn-secondary" margin-right="100">
                 Add Categories
            </div>
        </button>
        </form>
    </div></div>
    
    <div class="card-body">
        <div class="table-responsive">
        <?php
            $query = "SELECT sc.subcategory_id,sc.subcategory_name, c.category_name,sc.subcategory_status
            FROM tbl_subcategory sc
            INNER JOIN tbl_category c ON sc.category_id = c.category_id";
    
            $query_run = mysqli_query($con, $query);
            $row_number = 1;
        ?>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>SI No.</th>
                        <th>Category</th>
                        <th>Parent Category</th>
                        <th>EDIT</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(mysqli_num_rows($query_run) > 0)        
                    {
                        while($row = mysqli_fetch_assoc($query_run))
                        {
                    ?>
                        <tr>
                            <td><?php  echo $row_number; ?></td>
                            <td><?php  echo $row['subcategory_name']; ?></td>
                            <td><?php  echo $row['category_name']; ?></td>
                            <td>
                                <form action="subcategory_edit.php" method="post">
                                    <input type="hidden" name="subedit_id" value="<?php echo $row['subcategory_id']; ?>">
                                    <button type="submit" name="subedit_btn" class="btn btn-primary"> EDIT</button>
                                </form>
                            </td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="status_id" value="<?php echo $row['subcategory_id']; ?>">
                                    <?php if ($row['subcategory_status'] == 1) 
                                    { 
                                        ?>
                                        <button type="submit" name="deactivate_subcategory_btn" class="btn btn-success">Active</button>
                                    <?php 
                                    } 
                                    else 
                                    {
                                        // Subcategory is inactive
                                    ?>
                                        <button type="submit" name="activate_subcategory_btn" class="btn btn-warning">Inactive</button>
                                    <?php 
                                    } 
                                    ?>
                                </form>
                            </td>

                        </tr>
                    <?php
                    $row_number++;
                        } 
                    }
                    else {
                        echo "No Record Found";
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

</div>  

       

        

<?php 
    include("../includes/script.php");
    include("../includes/footer.php");
?>    
    