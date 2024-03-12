<?php
session_start();
include("../dbcon.php");
include("../includes/header.php");


// Check if ubook_id is provided and if the user is logged in
if(isset($_GET['ubook_id']) && isset($_SESSION['auth_user'])) {
    $ubook_id = $_GET['ubook_id'];
    
    // Fetch book details
    $sql = "SELECT * FROM tbl_usedbooks WHERE ubook_id = '$ubook_id'";
    $result = mysqli_query($conn, $sql);
    $book = mysqli_fetch_assoc($result);

    if(!$book) {
        $_SESSION['error_message'] = "Book not found.";
        header("Location: index.php");
        exit();
    }

    // Initialize variables with book details if they exist
    $ubook_name = isset($book['ubook_name']) ? $book['ubook_name'] : '';
    $ubook_condition = isset($book['ubook_condition']) ? $book['ubook_condition'] : '';
    $ubook_price = isset($book['ubook_price']) ? $book['ubook_price'] : '';
    $ubook_description = isset($book['ubook_description']) ? $book['ubook_description'] : '';

    // Check if the form is submitted for editing
    if(isset($_POST['edit_book'])) {
        // Retrieve form data
        $ubook_name = $_POST['ubook_name'];
        $author_id = $_POST['author_id']; 
        $publisher_id = $_POST['publisher_id'];
        $ubook_year = $_POST['published_year'];
        $ubook_condition = $_POST['ubook_condition'];
        $ubook_price = $_POST['ubook_price'];
        $ubook_description = $_POST['ubook_description'];

        // Check if the book already exists
        $check_query = "SELECT * FROM tbl_usedbooks WHERE ubook_name = '$ubook_name' AND ubook_id != '$ubook_id'";
        $check_result = mysqli_query($conn, $check_query);
        if(mysqli_num_rows($check_result) > 0) {
            $_SESSION['error_message'] = "Book with the same name already exists.";
            header("Location: usedbooks_edit");
            exit();
        }

        // Update the book details in the database
        $update_query = "UPDATE tbl_usedbooks SET 
                            ubook_name = '$ubook_name', 
                            ubook_author = '$author_id', 
                            ubook_publisher = '$publisher_id', 
                            ubook_year = '$ubook_year', 
                            ubook_condition = '$ubook_condition', 
                            ubook_price = '$ubook_price', 
                            ubook_description = '$ubook_description' 
                        WHERE ubook_id = '$ubook_id'";
        $update_result = mysqli_query($conn, $update_query);

        if($update_result) {
            $_SESSION['success_message'] = "Book details updated successfully.";
            header("Location: ubook_view.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Failed to update book details. Please try again later.";
        }
    }
} else {
    $_SESSION['error_message'] = "Unauthorized access.";
    header("Location: index.php");
    exit();
}
include("../includes/topbar.php");
?>





<div class="container mt-2">
    <div class="data">
        <div id="message"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h2>Edit Book Details</h2>
                    <form method="POST">
                        <div class="form-group">
                            <label for="ubook_name">Book Name:</label>
                            <input type="text" class="form-control" id="ubook_name" name="ubook_name" value="<?php echo $book['ubook_name']; ?>">
                        </div>
                        <!-- Add other form fields for editing book details -->
<div class="form-group">
    <label for="author_id">Author:</label>
    <select class="form-control" id="author_id" name="author_id">
        <?php
        // Fetch all authors from the database
        $author_query = "SELECT * FROM tbl_author";
        $author_result = mysqli_query($conn, $author_query);

        // Loop through authors to display them as options
        while ($author = mysqli_fetch_assoc($author_result)) {
            // Check if the current author is the one associated with the book
            $selected = ($book['author_id'] == $author['author_id']) ? "selected" : "";
            echo "<option value='{$author['author_id']}' {$selected}>{$author['author_name']}</option>";
        }
        ?>
    </select>
</div>

<div class="form-group">
    <label for="publisher_id">Publisher:</label>
    <select class="form-control" id="publisher_id" name="publisher_id">
        <?php
        // Fetch all publishers from the database
        $publisher_query = "SELECT * FROM tbl_publisher";
        $publisher_result = mysqli_query($conn, $publisher_query);

        // Loop through publishers to display them as options
        while ($publisher = mysqli_fetch_assoc($publisher_result)) {
            // Check if the current publisher is the one associated with the book
            $selected = ($book['publisher_id'] == $publisher['publisher_id']) ? "selected" : "";
            echo "<option value='{$publisher['publisher_id']}' {$selected}>{$publisher['publisher_name']}</option>";
        }
        ?>
    </select>
</div>

<div class="form-group">
<label for="published_year">Published Year:</label>
            <select class="form-control" id="published_year" name="published_year" oninput="validatePublishedYear()">
                    <?php
                    // Get the current year
                    $currentYear = date('Y');
                    // Loop through the years, starting from 100 years ago to 10 years in the future
                    for ($i = $currentYear; $i >= $currentYear - 1000; $i--) {
                        // Output each year as an option element
                        echo "<option value=\"$i\">$i</option>";
                    }
                    ?>
                </select>
                <small id="published_year_error" class="error" style="color: red; font-size: small;"></small>
            </div>

<div class="form-group">
    <label for="ubook_condition">Condition:</label>
    <input type="text" class="form-control" id="ubook_condition" name="ubook_condition" value="<?php echo $book['ubook_condition']; ?>">
</div>

<div class="form-group">
    <label for="ubook_price">Price:</label>
    <input type="text" class="form-control" id="ubook_price" name="ubook_price" value="<?php echo $book['ubook_price']; ?>">
</div>

<div class="form-group">
    <label for="ubook_description">Description:</label>
    <textarea class="form-control" id="ubook_description" name="ubook_description"><?php echo $book['ubook_description']; ?></textarea>
</div>


                        <button type="submit" name="edit_book" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="clear:both;"></div>
