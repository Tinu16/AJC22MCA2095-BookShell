<?php
session_start();
include("../dbcon.php");

if(isset($_SESSION['auth_user']) && isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    $current_user_id = $_SESSION['auth_user']['user_id'];

    // Fetch messages from the database
    $query = "SELECT m.*, u.user_email, c.customer_firstname FROM tbl_message m
              LEFT JOIN tbl_user u ON m.sender_id = u.user_id
              LEFT JOIN tbl_customer c ON m.sender_id = c.customer_id
              WHERE m.book_id = '$book_id' ORDER BY m.message_id ASC";
    $result = mysqli_query($conn, $query);

    // Check if there are messages
    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $message = htmlspecialchars($row['message']);
            $sender_name = $row['customer_firstname'] ?? $row['user_email'];
            $sender_id = $row['sender_id'];
            
            // Determine message style based on sender
            $messageStyle = ($sender_id == $current_user_id) ? "text-align: right; background-color: #0380fc; color: white;" : "text-align: left; background-color: white; color: #333;";
            
            // Output message with appropriate style
            echo "<div style='padding: 5px; margin: 5px; border-radius: 5px; $messageStyle'><strong></strong>$message</div>";
        }
    } else {
        echo "<div>No messages yet.</div>";
    }
}
?>
