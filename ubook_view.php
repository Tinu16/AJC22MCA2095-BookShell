<?php
session_start();
include("../dbcon.php");
include("config.php");
include("../authentication.php");
include("../includes/header.php");
include("../includes/customer_sidebar.php");
ob_start(); // Start output buffering
include("../includes/topbar.php");
 // Include the topbar.php file
include("../message.php");

// Check if the user is logged in
if(isset($_SESSION['auth_user'])) {
    $seller_id = $_SESSION['auth_user']['user_id']; // Get the seller ID from the session
    
    // Handle Sold Out Action
    if(isset($_GET['action']) && $_GET['action'] == 'sold_out' && isset($_GET['ubook_id'])) {
        $ubook_id = $_GET['ubook_id'];
        
        // Prepare and execute the SQL query to mark the book as sold out
        $update_query = "UPDATE tbl_usedbooks SET ubook_sold = 1 WHERE ubook_id = '$ubook_id' AND ubook_sellerid = '$seller_id'";
        $result = mysqli_query($conn, $update_query);
        
        if($result) {
            // Book marked as sold out successfully
            $_SESSION['success_message'] = "Book marked as sold out.";
        } else {
            // Error occurred while marking the book as sold out
            $_SESSION['error_message'] = "Failed to mark the book as sold out. Please try again later.";
        }
    }
    
    // Handle Delete Action
    if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['ubook_id'])) {
        $ubook_id = $_GET['ubook_id'];
        
        // Prepare and execute the SQL query to delete the book
        $delete_query = "DELETE FROM tbl_usedbooks WHERE ubook_id = '$ubook_id' AND ubook_sellerid = '$seller_id'";
        $result = mysqli_query($conn, $delete_query);
        
        if($result) {
            // Book deleted successfully
            $_SESSION['success_message'] = "Book deleted successfully.";
            // Redirect back to the page to reflect the changes
            header("Location: ubook_view.php");
            exit();
        } else {
            // Error occurred while deleting the book
            $_SESSION['error_message'] = "Failed to delete the book. Please try again later.";
        }
    }
    
    // Prepare the query to retrieve books added by the current customer
    $query = "SELECT b.ubook_id, b.ubook_name, a.author_name, p.publisher_name, b.ubook_year, b.ubook_condition, b.ubook_price, b.ubook_description, pics.tbl_ubookpic1, b.ubook_sold, DATE_FORMAT(b.ubook_adddate, '%Y-%m-%d') AS ubook_adddate
              FROM tbl_usedbooks b
              INNER JOIN tbl_author a ON b.ubook_author = a.author_id
              INNER JOIN tbl_publisher p ON b.ubook_publisher = p.publisher_id
              INNER JOIN tbl_ubookpics pics ON b.ubook_picid = pics.tbl_ubookpicid
              WHERE b.ubook_sellerid = '$seller_id' AND b.ubook_status = 0
              ORDER BY b.ubook_adddate DESC"; // Order by ubook_adddate in descending order
    
    // Execute the query
    $result = mysqli_query($conn, $query);
    
    // Check if any books are found
    if(mysqli_num_rows($result) > 0) {
        // Display the books
        
?>
<div class="container mt-2">
    <div class="data">
        <div id="message"></div>
        <div class="container">
            <h2>Books to Sell</h2> <!-- Add this heading -->
            <div class="row" id="book-container">
                <?php
                while ($data = mysqli_fetch_assoc($result)) {
                ?>
                    <div class="col-lg-4 mb-3">
                        <div class="card">
                            <img class="card-img-top" src="../images/<?php echo $data['tbl_ubookpic1']; ?>" alt="<?php echo $data['ubook_name']; ?>" style="height: 300px;width: 300px;">
                            <div class="card-body">
                                <a href='usedbook_details.php?ubook_id=<?php echo $data['ubook_id']; ?>'><?php echo $data['ubook_name'] . " (" . $data['author_name'] . ")"; ?></a>
                                <p class="card-text">Publisher: <?php echo $data['publisher_name']; ?></p>
                                <p class="card-text">Published Year: <?php echo $data['ubook_year']; ?></p>
                                <p class="card-text">Condition: <?php echo $data['ubook_condition']; ?></p>
                                <p class="card-text">Price: Rs.<?php echo $data['ubook_price']; ?></p>
                                <p class="card-text">Added On: <?php echo $data['ubook_adddate']; ?></p> <!-- Added Date -->
                                <!-- Edit and Delete buttons -->
                                <div class="mt-3">
                                    <?php if ($data['ubook_sold'] == 1) : ?>
                                        <button class="btn btn-primary" disabled>Edit</button>
                                        <button class="btn btn-warning" disabled>Sold Out</button>
                                        <button class="btn btn-danger" disabled>Delete</button>
                                    <?php else : ?>
                                        <a href="usedbooks_edit.php?ubook_id=<?php echo $data['ubook_id']; ?>" class="btn btn-primary">Edit</a>
                                        <a href="#" class="btn btn-warning" onclick="confirmSoldOut(<?php echo $data['ubook_id']; ?>)">Sold Out</a>
                                        <a href="#" class="btn btn-danger" onclick="confirmDelete(<?php echo $data['ubook_id']; ?>)">Delete</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap Modal for Confirm Message -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Action</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to proceed with this action?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a id="confirmActionBtn" class="btn btn-primary" href="#">Confirm</a>
            </div>
        </div>
    </div>
</div>

<div style="clear:both;"></div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function confirmSoldOut(ubook_id) {
        // Set the action URL
        var actionUrl = "ubook_view.php?action=sold_out&ubook_id=" + ubook_id;
        // Set the action URL for the confirmation button
        document.getElementById('confirmActionBtn').setAttribute('href', actionUrl);
        // Open the confirm modal
        $('#confirmModal').modal('show');
    }

    function confirmDelete(ubook_id) {
        // Set the action URL
        var actionUrl = "ubook_view.php?action=delete&ubook_id=" + ubook_id;
        // Set the action URL for the confirmation button
        document.getElementById('confirmActionBtn').setAttribute('href', actionUrl);
        // Open the confirm modal
        $('#confirmModal').modal('show');
    }
</script>


<?php
    } else {
        // No books found
        echo "<p>No books added by you.</p>";
    }
} else {
    // User is not logged in
    $_SESSION['error_message'] = "Please log in to view your books.";
    header("Location: ../login.php");
    exit();
}
?>
