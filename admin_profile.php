<?php 
    session_start();
    include("../includes/header.php");
 
?>

<body class="bg-gradient-primary">

    <div class="container">
    
        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-5 d-none d-lg-block bg-profile" style="background-image: url('img/noprofil.jpg'); background-repeat: no-repeat; background-position: center ">
                            <div style="margin-top:510px; margin-left:70px;">
                            <input onchange="display_image(event)" class="photo" type="file" name="profile" id="formFile">
                            </div>
                        </div>
                            <div class="col-lg-6">
                                <div class="p-5">

                                    <div class="text-center">
                                    
                                        <h1 class="h4 text-gray-900 mb-4">Your Profile</h1>
                                        <?php
                                             include("../message.php");
                                        ?>  
                                    </div>
                                    <form class="user" action="code.php" method="POST">
                                        <div class="form-group">
                                            <input type="name" name="name" class="form-control form-control-user"
                                                id="name" placeholder="Enter Your Name">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control form-control-user"
                                                id="email" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address...">
                                        </div>
                                        <div class="form-group">
                                            <input type="phone" name="phone" class="form-control form-control-user"
                                                id="phone" placeholder="Phone">
                                        </div>
                                        <div class="form-group">
                                            <input type="address1" name="address1" class="form-control form-control-user"
                                                id="ddress1" placeholder="Address Line 1">
                                        </div>
                                        <div class="form-group">
                                            <input type="address2" name="address2" class="form-control form-control-user"
                                                id="address1" placeholder="Address Line 2">
                                        </div>
                                        <div class="form-group">
                                            <input type="pincode" name="pincode" class="form-control form-control-user"
                                                id="pincode" placeholder="Pincode">
                                        </div>
                                        <div class="form-group">
                                            <input type="state" name="state" class="form-control form-control-user"
                                                id="state" placeholder="State">
                                        </div>
                                        <div class="form-group">
                                            <input type="country" name="country" class="form-control form-control-user"
                                                id="state" placeholder="Country">
                                        </div>
                                        
                                        <div class="col-sm-13">
                                        <input type="submit" name="login_btn" class="btn btn-success btn-user btn-block" value="Edit">
                                        </div>
                                    </form>
                                       
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
<script>

</script>
    <?php 
    include("../includes/script.php");
    
?>    