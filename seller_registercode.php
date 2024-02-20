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
    $verify_token = md5(rand());

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

    $user_query = "INSERT INTO `tbl_user`(`user_email`, `user_password`, `verify_token`, `usertype`) VALUES ('$email','$hashed_password','$verify_token', '$usertype')";
    $user_query_run = mysqli_query($conn, $user_query);

    if ($user_query_run) { 
        // After inserting into tbl_user
        $user_id = mysqli_insert_id($conn);

        // Insert email and phone number into tbl_customer
        $customer_query = "INSERT INTO `tbl_seller`(`user_id`, `seller_phone`) VALUES ('$user_id','$phone')";
        $customer_query_run = mysqli_query($conn, $customer_query);
        sendemail_verify($email, $verify_token);

        // Set success message and redirect
        $_SESSION["message"] = "Registered successfully! Please verify your email address";
        header("Location: seller_register.php");
        exit(0);
    } else {
        // Set error message and redirect
        $_SESSION["message"] = "Registration failed";
        header("Location: seller_register.php");
        exit(0);
    }
}


if (!empty($_POST['sellerreg1'])) {
        $user_id = $_POST['user_id'];
        $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
        $middle_name = mysqli_real_escape_string($conn, $_POST['middle_name']);
        $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
        $store=mysqli_real_escape_string($conn, $_POST['store']);
        $pan=mysqli_real_escape_string($conn, $_POST['pan']);
        $aadhar_card_file = $_FILES['aadhar_card_file']['name'];
        $aadhar_card_temp = $_FILES['aadhar_card_file']['tmp_name'];
        move_uploaded_file($aadhar_card_temp, "../uploads/aadhar/".$aadhar_card_file);
        $address_line_1 = mysqli_real_escape_string($conn, $_POST['address_line_1']);
        $address_line_2 = mysqli_real_escape_string($conn, $_POST['address_line_2']);
        $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
        $district=$_POST['district'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $account_number = mysqli_real_escape_string($conn, $_POST['account_number']);
        $ifsc_code = mysqli_real_escape_string($conn, $_POST['ifsc_code']);

   
        
        $query1 = "UPDATE tbl_seller SET 
            seller_firstname = '$first_name', 
            seller_middlename = '$middle_name', 
            seller_lastname = '$last_name', 
            seller_storename='$store',
            seller_pan='$pan',
            seller_aadhar = '$aadhar_card_file', 
            seller_accountno = '$account_number', 
            seller_ifsccode = '$ifsc_code' 
          WHERE user_id = $user_id";

// Execute the query
if (mysqli_query($conn, $query1)) {
    $insert_query2 = "INSERT INTO tbl_address (`user_id`, `address_address1`, `address_address2`, `address_pincode`, `address_district`, `address_state`, `address_country`) 
    VALUES ('$user_id', '$address_line_1', '$address_line_2', '$pincode', '$district', '$state', '$country')";

    if(mysqli_query($conn, $insert_query2)) 
    {
        $_SESSION["message"] = "Thank you for registering with us.You will get a mail when your account get activated";
        header("Location: ../login.php");
        exit(0);
    }
    else 
    {
    // Error occurred while inserting data into tbl_address
    // Handle the error
    $_SESSION['error'] = "Error: " . mysqli_error($conn);
    header("Location: seller_register.php");
    exit();
    }
   
} 

    
    }
    
    ?>

