<?php
    session_start();
    include("../dbcon.php");
    include("../includes/header.php");
    include("../includes/admin_sidebar.php");
    include("../includes/topbar.php");
?>


<div class="container">
    <div class="row justify-content-center">
    <div class="col-xl-5 col-lg-12 col-md-10">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                    <div class="col-lg-11">
                        <div class="p-5">
                            <div class="text-center">
                            
                               <?php
                                    include("../message.php");
                               ?>
                          
                                <h1 class="h4 text-gray-900 mb-4">Add Publisher</h1>
                            
                            <form class="user" method="POST" action="../code.php">
                            

        

        <div class="form-group">
                  <input type="text" name="publishername" class="form-control form-control-user"
                  id="authorname" placeholder="Publisher name">
        </div>
        <!--
        <div class="form-group">
                  <input type="text" name="link" class="form-control form-control-user"
                  id="link" placeholder="Link">
        </div>-->
        </div>
        
        <div >
        <div class="text-center">
        <div class="col-sm-13">
            <button type="sumit" name="close_pub" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" name="add_pub_btn" class="btn btn-primary">Save</button>
        </div>
        <hr>
            <button type="sumit" name="add_more_pub" class="btn btn-primary" data-dismiss="modal">Add More..</button>
        <hr>
</div>
      </form>

    </div>
   