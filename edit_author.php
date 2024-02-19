<?php
session_start();
include("../dbcon.php");
include("../includes/header.php");
include("../includes/admin_sidebar.php");
include("../includes/topbar.php");

if (isset($_POST['edit_author_btn']) && isset($_POST['edit_author_id'])) 
    {
        $author_id = $_POST['edit_author_id'];

        $query = "SELECT * 
                FROM tbl_author
                WHERE author_id = $author_id";

        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) 
            {
                $row = mysqli_fetch_assoc($result);
                $author_id = $row['author_id'];
                $author_name = $row['author_name'];
                $author_link = $row['author_link'];
            } 
        else
            {
                die("Author not found or an error occurred.");
            }
    } 
?>

<div class="row justify-content-center">
    <div class="col-xl-5 col-lg-12 col-md-10">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                    <div class="col-lg-11">
                        <div class="p-5">
                            <div class="text-center">  
                                <h1 class="h4 text-gray-900 mb-4">Update Author</h1>
                                <?php
                                    include("../message.php");
                                ?>
                                <form class="user" method="POST" action="../code.php">
                                <input type="hidden" name="author_id" value="<?php echo $author_id; ?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" value="<?php echo $author_name; ?>"
                                            id="new_author_name" name="new_author_name" placeholder="Author" >
                                            <small id="auth_error" class="auth_error"></small>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" value="<?php echo $author_link; ?>"
                                            id="new_author_link" name="new_author_link" placeholder="Author Link" >
                                            <small id="author_link_error" class="author_link_error"></small>
                                    </div>
                                    <button type="submit" name="author_update_btn" class="btn-primary">Update</button>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    
