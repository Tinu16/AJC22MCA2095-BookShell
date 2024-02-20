<?php
session_start();
    include("../dbcon.php");
    include("../includes/header.php");
   // include("../includes/topbar.php");
?>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-3">
                <!-- Nested Row within Card Body -->
                    <p>Thank you for registering with us. To complete the registration process and ensure the security of your account, we kindly ask you to verify your email address.</p>
                    <p>Please  verify your email.</p>
                    <p>Thank you for your cooperation.</p>
                    <p>Best regards,<br>Bookshell</p>  
            </div> 
        </div>
    </div>
<?php
    //include("../includes/footer.php");
?>