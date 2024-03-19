<?php
session_start();
include("../dbcon.php");
include("config.php");
include("../authentication.php");
include("../includes/header.php");
include("../includes/topbar.php");

// Check if the user is logged in
if(isset($_SESSION['auth_user'])) {
    // Initialize book title variable
    $book_title = "";

    // Check if ubook_id is provided
    if(isset($_GET['ubook_id'])) {
        $ubook_id = $_GET['ubook_id'];

        // Query to fetch the book title
        $book_query = "SELECT * FROM tbl_usedbooks WHERE ubook_id = '$ubook_id'";
        $book_result = mysqli_query($conn, $book_query);

        if($book_result && mysqli_num_rows($book_result) > 0) {
            $book_row = mysqli_fetch_assoc($book_result);
            $book_title = $book_row['ubook_name'];
            $seller=$book_row['ubook_sellerid'];
        }

        // Prepare and execute query to fetch the last message sent by each sender for the specified ubook_id
        $query = "SELECT m.message_id, 
                    m.sender_id,
                    u.user_email AS sender,
                    m.message, 
                    m.viewed
                FROM tbl_message m
                LEFT JOIN tbl_user u ON m.sender_id = u.user_id
                WHERE m.message_id IN (
                SELECT MAX(message_id)
                FROM tbl_message
                WHERE book_id = '$ubook_id'
                GROUP BY sender_id 
                )";


        $result = mysqli_query($conn, $query);

        // Echo the result to check if it's false or a valid result set
        if(!$result) {
            echo "Error: " . mysqli_error($conn);
        } else {
            // Start HTML content with the same template as ubook_view.php
            ?>
            <div class="container mt-2">
                <div class="data">
                    <div id="message"></div>
                    <div class="container">
                        <h2>Messages for <?php echo $book_title; ?></h2>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                // Loop through the result set and fetch each row
                                while($row = mysqli_fetch_assoc($result)) {
                                    echo "<div class='card mb-3'>";
                                    echo "<div class='card-body'>";
                                    echo "<p><strong>Sender:</strong> " . $row['sender'] . "</p>";
                                    echo "<p> " . $row['message'] . "</p>";
                                    // Add reply button with link to chat.php including both sender_id and ubook_id
                                    echo "<a href='chat.php?seller_id=" . $seller . "&receiver_id=" . $row['sender'] . "&ubook_id=" . $ubook_id . "' class='btn btn-primary'>Reply</a>";

                                    echo "</div></div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        // Check if any messages are found
        if(mysqli_num_rows($result) == 0) {
            echo "<p>No messages found for the specified book.</p>";
        }
    }
} else {
    // User is not logged in
    $_SESSION['error_message'] = "Please log in to view used books.";
    header("Location: ../login.php");
    exit();
}
include("../includes/scripts.php");
include("../includes/footer.php");
?>
