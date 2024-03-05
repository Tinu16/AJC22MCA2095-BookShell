<?php
session_start();
include("../dbcon.php");
include("config.php");
include("../authentication.php");
include("../includes/header.php");

include("../includes/topbar.php");
include("../message.php");

// Check if ubook_id is provided and if the user is logged in
if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['ubook_id']) && isset($_SESSION['auth_user'])) {
    $ubook_id = $_GET['ubook_id'];
    
    // Prepare and execute the SQL query to delete the book
    $delete_query = "DELETE FROM tbl_usedbooks WHERE ubook_id = '$ubook_id'";
    $result = mysqli_query($conn, $delete_query);
    
    if($result) {
        // Book deleted successfully
        $_SESSION['success_message'] = "Book deleted successfully.";
    } else {
        // Error occurred while deleting the book
        $_SESSION['error_message'] = "Failed to delete the book. Please try again later.";
    }
}
if(isset($_GET['action']) && $_GET['action'] == 'sold_out' && isset($_GET['ubook_id']) && isset($_SESSION['auth_user'])) {
    $ubook_id = $_GET['ubook_id'];
    
    // Prepare and execute the SQL query to mark the book as sold out
    $update_query = "UPDATE tbl_usedbooks SET ubook_sold = 1 WHERE ubook_id = '$ubook_id'";
    $result = mysqli_query($conn, $update_query);
    
    if($result) {
        // Book marked as sold out successfully
        $_SESSION['success_message'] = "Book marked as sold out.";
    } else {
        // Error occurred while marking the book as sold out
        $_SESSION['error_message'] = "Failed to mark the book as sold out. Please try again later.";
    }
}
?>
<div <div class="container mt-2">
    <div class="data">
        <div id="message"></div>
        <div class="container">
            <h2>Books to Sell</h2> <!-- Add this heading -->
            <div class="row" id="book-container">
                <?php
                $result = mysqli_query($conn, "SELECT b.ubook_id, b.ubook_name, a.author_name, p.publisher_name, b.ubook_year, b.ubook_condition, b.ubook_price, b.ubook_description, pics.tbl_ubookpic1, b.ubook_sold
                FROM tbl_usedbooks b
                INNER JOIN tbl_author a ON b.ubook_author = a.author_id
                INNER JOIN tbl_publisher p ON b.ubook_publisher = p.publisher_id
                INNER JOIN tbl_ubookpics pics ON b.ubook_picid = pics.tbl_ubookpicid
                WHERE b.ubook_status = 0"); // Assuming ubook_status = 1 means the book is available

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
                                <!-- Edit and Delete buttons -->
                                <div class="mt-3">
                                    <?php if ($data['ubook_sold'] == 1) : ?>
                                        <button class="btn btn-primary" disabled>Edit</button>
                                        <button class="btn btn-warning" disabled>Sold Out</button>
                                        <button class="btn btn-danger" disabled>Delete</button>
                                    <?php else : ?>
                                        <a href="usedbooks_edit.php?ubook_id=<?php echo $data['ubook_id']; ?>" class="btn btn-primary">Edit</a>
                                        <a href="?action=sold_out&ubook_id=<?php echo $data['ubook_id']; ?>" class="btn btn-warning">Sold Out</a>
                                        <a href="?action=delete&ubook_id=<?php echo $data['ubook_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
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
<div style="clear:both;"></div>

