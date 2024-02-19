<?php
    session_start();
    include("../dbcon.php");
    include("../includes/header.php");
    include("../includes/admin_sidebar.php");
    include("../includes/topbar.php");
?>
<div class="row justify-content-center">
    <div class="col-xl-5 col-lg-12 col-md-10">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                    <div class="col-lg-11">
                        <div class="p-5">
                            <div class="text-center">  
                                <h1 class="h4 text-gray-900 mb-4">Category</h1>
                                <?php
                                    include("../message.php");
                                ?>
                                <form class="user" method="POST" action="../code.php">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user"
                                            id="categories" name="categories" placeholder="Category" onType="category()">
                                            <small id="category_error" class="category_error"></small>
                                    </div>
                                    <div class="col-sm-13">
                                    <input type="submit" name="cat_add" class="btn btn-lg btn-info btn-block" value="ADD">
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function validateForm() 
    {
    const cat = document.getElementById("categories").value;
    }
</script>
        <?php
        require('../includes/footer.php');
        
        ?>
