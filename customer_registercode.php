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
        <a href='http://localhost/book/customer/../verify_email.php?token=$verify_token'>Click here</a>
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
        $customer_query = "INSERT INTO `tbl_customer`(`user_id`, `customer_phone`) VALUES ('$user_id','$phone')";
        $customer_query_run = mysqli_query($conn, $customer_query);

        sendemail_verify($email, $verify_token);

        // Set success message and redirect
        $_SESSION["message"] = "Registered successfully! Please verify your email address";
        header("Location: customer_register.php");
        exit(0);
    } else {
        // Set error message and redirect
        $_SESSION["message"] = "Registration failed";
        header("Location: customer_register.php");
        exit(0);
    }
} else {
    // If register_btn is not set, redirect with an error message
    $_SESSION["message"] = "Invalid request";
    header("Location: customer_register.php");
    exit(0);
}
?>