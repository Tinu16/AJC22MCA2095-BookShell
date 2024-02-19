<?php 
session_start();
    include("../dbcon.php");
    include("../includes/header.php");
    include("../includes/admin_sidebar.php");
    include("../includes/topbar.php");
    include("../message.php");


if (isset($_POST['activate_author_btn'])) {
    $author_id = $_POST['status_id'];
    $query = "UPDATE tbl_author SET author_status = 1 WHERE author_id = $author_id";
    mysqli_query($con, $query);
}

if (isset($_POST['deactivate_author_btn'])) {
    $author_id = $_POST['status_id'];
    $query = "UPDATE tbl_author SET author_status = 0 WHERE author_id = $author_id";
    mysqli_query($con, $query);
   
}


?>
  <div class="container-fluid">

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h3 class="m-0 font-weight-bold text-primary">Author</h3>
        <form action="author.php">
        <button type="submit"  class="close" margin-top="1" >
            <div class="btn btn-secondary">
                 Add Author Details
            </div>
        </button>
        </form>
    </div></div>
    
    <div class="card-body">
        <div class="table-responsive">
        <?php
            $query = "SELECT * FROM tbl_author";
            $query_run = mysqli_query($con, $query);
            $row_number = 1;
        ?>
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>SI No.</th>
                        <th>Author</th>
                        <th>Link </th>
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
                            <td><?php  echo $row['author_name']; ?></td>
                            <td><?php  echo $row['author_link']; ?></td>
                            <td>
                                <form action="edit_author.php" method="post">
                                    <input type="hidden" name="edit_author_id" value="<?php echo $row['author_id']; ?>">
                                    <button type="submit" name="edit_author_btn" class="btn btn-primary"> EDIT</button>
                                </form>
                            </td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="status_id" value="<?php echo $row['author_id']; ?>">
                                    <?php if ($row['author_status'] == 1) { // Assuming 'author_status' is the column in your database
                                        // Author is active
                                        ?>
                                        <button type="submit" name="deactivate_author_btn" class="btn btn-success">Active</button>
                                    <?php } else {
                                        // Author is inactive
                                        ?>
                                        <button type="submit" name="activate_author_btn" class="btn btn-warning">Inactive</button>
                                    <?php } ?>
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
    