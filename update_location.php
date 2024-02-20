<?php
session_start();
include("../dbcon.php");

if(isset($_POST['user_id']) && isset($_POST['latitude']) && isset($_POST['longitude'])) {
    $user_id = $_POST['user_id'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // Update latitude and longitude in the database
    $update_query = "UPDATE tbl_deliveryboy SET latitude = '$latitude', longitude = '$longitude' WHERE user_id = '$user_id'";
    mysqli_query($conn, $update_query);
}
?>
