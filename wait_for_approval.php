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
                    <p>Thank you for registering with us.</p>
                    <p>It will take atmost 3 days to make your account active</p>
                    <p>Thank you for your cooperation.</p>
                    <p>Best regards,<br>Bookshell</p>  
            </div> 
        </div>
    </div>
<?php
    //include("../includes/footer.php");
?>