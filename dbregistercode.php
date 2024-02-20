<?php
session_start();
include('../dbcon.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require '../vendor/autoload.php';

function sendemail_verify($email, $verify_token)
{
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 3;
    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = 'smtp.gmail.com';
    $mail->Username = 'bookshell45@gmail.com';
    $mail->Password = 'kddg nagj cfvj vpso';

    $mail->SMTPSecure = "tls";
    $mail->Port = 587;

    $mail->setFrom('bookshell45@gmail.com', 'Bookshell');
    $mail->addAddress($email);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Email verification from Bookshell';

    $email_template = "
        <h2>You have registered with Bookshell</h2>
        <h5>Verify your email address to Login with the below given link</h5>
        <br></br>
        <a href='http://localhost/book/seller/../verify_email.php?token=$verify_token'>Click here</a>
     ";

    $mail->Body = $email_template;

    $mail->send();
   
}

if (!empty($_POST['register_btn'])) {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $usertype = mysqli_real_escape_string($conn, $_POST["usertype"]);

    // Check if email already exists
    $checkemail = "SELECT user_email FROM `tbl_user` WHERE user_email='$email' LIMIT 1";
    $checkmail_run = mysqli_query($conn, $checkemail);

    if (mysqli_num_rows($checkmail_run) > 0) {
        $_SESSION["message"] = "Email already registered, please login";
        header("Location: ../login.php");
        exit(0);
    }

    // Hash the user's password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $user_query = "INSERT INTO `tbl_user`(`user_email`, `user_password`, `usertype`) VALUES ('$email','$hashed_password','$usertype')";
    $user_query_run = mysqli_query($conn, $user_query);

    if ($user_query_run) {
        // After inserting into tbl_user
        $user_id = mysqli_insert_id($conn);
        header("Location: db_register2.php?user_id=$user_id&phone=$phone&email=$email");
        exit(0);
    } 
} 




// Check if the form is submitted
if(isset($_POST['dbreg1'])) 
{
    // Retrieve the values from the form
    $user_id = $_POST['user_id'];
    $phone = $_POST['phone'];
    $email=$_POST['email'];
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $aadhar_card_file = $_FILES['aadhar_card_file']['name'];
    $aadhar_card_temp = $_FILES['aadhar_card_file']['tmp_name'];
    move_uploaded_file($aadhar_card_temp, "../uploads/aadhar/".$aadhar_card_file);
    $license_file = $_FILES['license_file']['name'];
    $license_temp = $_FILES['license_file']['tmp_name'];
    move_uploaded_file($license_temp, "../uploads/licence/".$license_file);
    $address_line_1 = mysqli_real_escape_string($conn, $_POST['address_line_1']);
    $address_line_2 = mysqli_real_escape_string($conn, $_POST['address_line_2']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
    $district=$_POST['district'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $latitude=$_POST['latitude'];
    $longitude=$_POST['longitude'];
   
    
    //$district = mysqli_real_escape_string($conn, $_POST['district']);
    // $state =  mysqli_real_escape_string($conn, $_POST['state']);
    // $country = mysqli_real_escape_string($conn, $_POST['country']);
    $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);
    $ifsc_code = mysqli_real_escape_string($conn, $_POST['ifsc_code']);
    $verify_token = md5(rand());

    //Insert into the tbl_deliveryboy table
    $insert_query1 = "INSERT INTO tbl_deliveryboy (`user_id`, `db_phone`, `db_firstname`, `db_middlename`, `db_lastname`,  `db_aadhar`, `db_licence`,`latitude`,`longitude`) 
                    VALUES ('$user_id', '$phone', '$first_name', '$middle_name', '$last_name', '$aadhar_card_file', '$license_file','$latitude','$longitude')";
    
    // Execute the query
    if(mysqli_query($conn, $insert_query1)) 
    {
        // Insert data into tbl_address
        $insert_query2 = "INSERT INTO tbl_address (`user_id`, `address_address1`, `address_address2`, `address_pincode`, `address_district`, `address_state`, `address_country`) 
                        VALUES ('$user_id', '$address_line_1', '$address_line_2', '$pincode', '$district', '$state', '$country')";

        if(mysqli_query($conn, $insert_query2)) 
        {
            $update_query2 = "UPDATE tbl_user SET verify_token = '$verify_token' WHERE user_id = '$user_id'";
            
            if(mysqli_query($conn, $update_query2)) 
            {
                sendemail_verify($email, $verify_token);
                header("Location: verify_message.php");
                exit(0);
            }
            else
            {
                $_SESSION["message"] = "couln,t send mail";
                header("Location: dbregister.php");
                exit(0);
            } 
        }
    
        else 
        {
            // Error occurred while inserting data into tbl_address
            // Handle the error
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
            header("Location: dbregister.php");
            exit();
        }
    } 
    else 
    {
        // Error occurred while inserting data into tbl_deliveryboy
        // Handle the error
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
        header("Location: dbregister.php");
        exit();
    }

}

?>

<!-- <?php
// session_start();
// include('../dbcon.php');

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

// // Load Composer's autoloader
// require '../vendor/autoload.php';

// function sendemail_verify($email, $verify_token)
// {
//     $mail = new PHPMailer(true);
//     $mail->SMTPDebug = 3;
//     $mail->isSMTP();
//     $mail->SMTPAuth = true;

//     $mail->Host = 'smtp.gmail.com';
//     $mail->Username = 'bookshell45@gmail.com';
//     $mail->Password = 'kddg nagj cfvj vpso';

//     $mail->SMTPSecure = "tls";
//     $mail->Port = 587;

//     $mail->setFrom('bookshell45@gmail.com', 'Bookshell');
//     $mail->addAddress($email);

//     // Content
//     $mail->isHTML(true);
//     $mail->Subject = 'Email verification from Bookshell';

//     $email_template = "
//         <h2>You have registered with Bookshell</h2>
//         <h5>Verify your email address to Login with the below given link</h5>
//         <br></br>
//         <a href='http://localhost/book/seller/../verify_email.php?token=$verify_token'>Click here</a>
//      ";

//     $mail->Body = $email_template;

//     $mail->send();
   
// }




// if (!empty($_POST['register_btn'])) {
//     $email = mysqli_real_escape_string($conn, $_POST["email"]);
//     $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
//     $password = mysqli_real_escape_string($conn, $_POST["password"]);
//     $usertype = mysqli_real_escape_string($conn, $_POST["usertype"]);
//     $verify_token = md5(rand());

//     // Check if email already exists
//     $checkemail = "SELECT user_email FROM `tbl_user` WHERE user_email='$email' LIMIT 1";
//     $checkmail_run = mysqli_query($conn, $checkemail);

//     if (mysqli_num_rows($checkmail_run) > 0) {
//         $_SESSION["message"] = "Email already registered, please login";
//         header("Location: ../login.php");
//         exit(0);
//     }

//     // Hash the user's password
//     $hashed_password = password_hash($password, PASSWORD_DEFAULT);

//     $user_query = "INSERT INTO `tbl_user`(`user_email`, `user_password`, `verify_token`, `usertype`) VALUES ('$email','$hashed_password','$verify_token', '$usertype')";
//     $user_query_run = mysqli_query($conn, $user_query);

//     if ($user_query_run) {
//         // After inserting into tbl_user
//         $user_id = mysqli_insert_id($conn);

//         // Insert email and phone number into tbl_customer
//         $customer_query = "INSERT INTO `tbl_deliveryboy`(`user_id`, `db_phone`) VALUES ('$user_id',  '$phone')";
//         $customer_query_run = mysqli_query($conn, $customer_query);

//         header("Location: dbregister2.php");
//         exit(0);
//     } else {
//         // Set error message and redirect
//         $_SESSION["message"] = "Registration failed";
//         header("Location: dbregister.php");
//         exit(0);
//     }
// } else {
//     // If register_btn is not set, redirect with an error message
//     $_SESSION["message"] = "Invalid request";
//     header("Location: dbregister.php");
//     exit(0);
// }
//         sendemail_verify($email, $verify_token);

//         // Set success message and redirect
//         $_SESSION["message"] = "Registered successfully! Please verify your email address";
//         header("Location: dbregister.php");
//         exit(0);
//     } else {
//         // Set error message and redirect
//         $_SESSION["message"] = "Registration failed";
//         header("Location: dbregister.php");
//         exit(0);
//     }
// } else {
//     // If register_btn is not set, redirect with an error message
//     $_SESSION["message"] = "Invalid request";
//     header("Location: dbregister.php");
//     exit(0);
// }
?> -->

