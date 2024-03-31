<?php
// Include necessary files and start session
session_start();
include("../dbcon.php");
include("config.php");
include("../authentication.php");
include("../includes/header.php");
//include("../includes/customer_sidebar.php");
ob_start(); // Start output buffering
include("../includes/topbar.php");
include("../message.php");

// Check if user is logged in
if (isset($_SESSION['auth_user'])) {
    // Query to fetch all ebook details
    $query = "SELECT e.ebook_pdf, e.ebook_status, b.book_name, b.book_image, a.author_name, p.publisher_name FROM tbl_ebook e 
              INNER JOIN tbl_book b ON e.ebook_id = b.ebook_id
              INNER JOIN tbl_author a ON b.author_id = a.author_id
              INNER JOIN tbl_publisher p ON b.publisher_id = p.publisher_id";
    
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        // Display ebooks using card design
        echo "<div class='container mt-2'>";
        echo "<div class='row'>";
        while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="col-lg-4 mb-3">
                <div class="card">
                    <img class="card-img-top" src="../images/<?php echo $row['book_image']; ?>" alt="<?php echo $row['book_name']; ?>" style="height: 300px;width: 300px;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['book_name'] . " (" . $row['author_name'] . ")"; ?></h5>
                        <p class="card-text">Publisher: <?php echo $row['publisher_name']; ?></p>
                        <?php
                        // Get the first 10 pages of the PDF file
                        $pdf_path = "../seller/digital_books/" . $row['ebook_pdf'];
                        ?>
                        <a href="<?php echo $pdf_path . "#page=1"; ?>" class="btn btn-primary">Read First 10 Pages</a>
                    </div>
                </div>
            </div>
            <?php
        }
        echo "</div>"; // Close the row
        echo "</div>"; // Close the container
    } else {
        echo "<p class='no-books-message'>No ebooks found.</p>";
    }
} else {
    // Redirect to login page or display an error message
    $_SESSION["message"] = "Please log in to view this page.";
    header("Location:../login.php");
    exit(); // Make sure to exit after redirecting
}
?>
