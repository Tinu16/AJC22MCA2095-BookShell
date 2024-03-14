<?php
session_start();
include("../dbcon.php");

if(isset($_SESSION['auth_user'])) {
    // Check if book_id is provided
    if(isset($_GET['book_id']) && !empty($_GET['book_id'])) {
        // Get the book_id from the query string
        $book_id = $_GET['book_id'];
        $user_id = $_SESSION['auth_user']['user_id'];

        // Fetch messages related to the book for the logged-in user
        $query = "SELECT m.message, IF(m.sender_id = '$user_id', 'You', IFNULL(c.customer_firstname, u.user_email)) AS sender_name
                  FROM tbl_message m
                  INNER JOIN tbl_user u ON m.sender_id = u.user_id
                  LEFT JOIN tbl_customer c ON u.user_id = c.customer_id
                  WHERE (m.sender_id = '$user_id' OR m.receiver_id = '$user_id')
                  AND m.book_id = '$book_id'
                  ORDER BY m.message_id ASC";

        $result = mysqli_query($conn, $query);

        // Check if there are any messages
        if(mysqli_num_rows($result) > 0) {
            // Loop through each message and display them along with sender names
            while($row = mysqli_fetch_assoc($result)) {
                $senderName = $row['sender_name'];
                $message = $row['message'];
                // Format the message as per your requirements
                echo "<div><strong>$senderName:</strong> $message</div>";
            }
        } else {
            echo "<p>No messages for this book</p>";
        }
    } else {
        echo "<p>Book ID not provided</p>";
    }
} else {
    echo "<p>User not logged in</p>";
}
?>
