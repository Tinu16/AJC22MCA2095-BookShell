<?php
session_start();
include("../dbcon.php");
// Retrieve latest location from database
$sql = "SELECT latitude, longitude FROM tbl_deliveryboy ORDER BY last_updated DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $location = array('latitude' => $row['latitude'], 'longitude' => $row['longitude']);
    echo json_encode($location);
} else {
    echo json_encode(array('error' => 'No location data available.'));
}

$conn->close();
?>
