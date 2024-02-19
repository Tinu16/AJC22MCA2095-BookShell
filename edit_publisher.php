<?php
session_start();
include("../dbcon.php");
include("../includes/header.php");
include("../includes/admin_sidebar.php");
include("../includes/topbar.php");

if (isset($_POST['edit_publisher_btn']) && isset($_POST['edit_publisher_id'])) 
    {
        $publisher_id = $_POST['edit_publisher_id'];

        $query = "SELECT * 
                FROM tbl_publisher
                WHERE publisher_id = $publisher_id";

        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) 
            {
                $row = mysqli_fetch_assoc($result);
                $publisher_id = $row['publisher_id'];
                $publisher_name = $row['publisher_name'];
            } 
        else
            {
                die("Publisher not found or an error occurred.");
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
                                <h1 class="h4 text-gray-900 mb-4">Update Publisher</h1>
                                <?php
                                    include("../message.php");
                                ?>
                                <form class="user" method="POST" action="../code.php">
                                <input type="hidden" name="publisher_id" value="<?php echo $publisher_id; ?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" value="<?php echo $publisher_name; ?>"
                                            id="new_publisher_name" name="new_publisher_name" placeholder="Publisher" >
                                            <small id="publisher_error" class="publisher_error"></small>
                                    </div>
                                   
                                    <button type="submit" name="publisher_update_btn" class="btn-primary">Update</button>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    
