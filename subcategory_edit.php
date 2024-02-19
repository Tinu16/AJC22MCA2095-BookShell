<?php
session_start();
include("../dbcon.php");
include("../includes/header.php");
include("../includes/admin_sidebar.php");
include("../includes/topbar.php");

if (isset($_POST['subedit_btn']) && isset($_POST['subedit_id'])) 
    {
        $subedit_id = $_POST['subedit_id'];

        $query = "SELECT sc.subcategory_id, sc.subcategory_name, c.category_name 
                FROM tbl_subcategory sc
                INNER JOIN tbl_category c ON sc.category_id = c.category_id
                WHERE sc.subcategory_id = $subedit_id";

        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) > 0) 
            {
                $row = mysqli_fetch_assoc($result);
                $subcategory_id = $row['subcategory_id'];
                $subcategory_name = $row['subcategory_name'];
                $category_name = $row['category_name'];
            } 
        else
            {
                // Handle errors or redirect to a different page
                die("Subcategory not found or an error occurred.");
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
                                <h1 class="h4 text-gray-900 mb-4">Sub Category</h1>
                                <?php
                                    include("../message.php");
                                ?>
                                <form class="user" method="POST" action="../code.php">
                                <input type="hidden" name="subedit_id" value="<?php echo $subedit_id; ?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" value="<?php echo $subcategory_name; ?>"
                                            id="categories" name="new_subcategory_name" placeholder="Category" onType="category()">
                                            <small id="category_error" class="category_error"></small>
                                    </div>
                                    <button type="submit" name="subupdate_btn">Update</button>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    
