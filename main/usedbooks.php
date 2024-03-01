<?php
session_start();
include("../dbcon.php");
include("config.php");
include("../authentication.php");
include("../includes/header.php");
include("../includes/customer_sidebar.php");
include("../includes/topbar.php");
include("../message.php");

function uploadImage($file) {
    // Check if file was uploaded without errors
    if ($file["error"] == UPLOAD_ERR_OK) {
        $temp_name = $file["tmp_name"];
        $upload_dir = "uploads/"; // Directory where you want to store uploaded images
        $file_name = basename($file["name"]);
        $target_path = $upload_dir . $file_name;

        // Check if file already exists
        if (file_exists($target_path)) {
            // If file exists, generate a unique file name
            $file_name = uniqid() . '_' . $file_name;
            $target_path = $upload_dir . $file_name;
        }

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($temp_name, $target_path)) {
            // Return the file path if upload was successful
            return $target_path;
        } else {
            // Return false if upload failed
            return false;
        }
    } else {
        // Return false if file upload encountered an error
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $book_name = $_POST["book_name"];
    $author = $_POST["author"];
    $publisher = $_POST["publisher"];
    $published_year = $_POST["published_year"];
    $condition = $_POST["condition"];
    $price = $_POST["price"];
    $isbn = $_POST["isbn"];
    $category = $_POST["category"];
    
    // Process uploaded images (assuming you're storing file paths in the database)
    $book_image1 = uploadImage($_FILES["book_image1"]);
    $book_image2 = uploadImage($_FILES["book_image2"]);
    $book_image3 = uploadImage($_FILES["book_image3"]);

    // Insert data into the database table
    $sql = "INSERT INTO your_table_name (ubook_name, ubook_author, ubook_publisher, ubook_year, ubook_condition, ubook_price, ubook_isbn, ubook_pic1, ubook_pic2, ubook_pic3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$book_name, $author, $publisher, $published_year, $condition, $price, $isbn, $book_image1, $book_image2, $book_image3]);

    // Redirect to a success page or display a success message
    header("Location: success.php");
    exit();
}

?>
    
 


<div style="clear:both;"></div>

<!-- Form to Add a New Book -->
<div class="container mt-2">
    <h2>Sell Your Book</h2>
    <form id="add-book-form" enctype="multipart/form-data" action="#">
        <div class="form-group">
            <label for="book_name">Book Name:</label>
            <input type="text" class="form-control" id="book_name" name="book_name">
            <small id="book_name_error" class="error" style="color: red; font-size: small;"></small>
        </div>
        <div class="form-group">
            <label for="author">Author:</label>
            <input type="text" class="form-control" id="author" name="author">
            <small id="author_error" class="error" style="color: red; font-size: small;"></small>
        </div>
        <div class="form-group">
            <label for="publisher">Publisher:</label>
            <input type="text" class="form-control" id="publisher" name="publisher">
            <small id="publisher_error" class="error" style="color: red; font-size: small;"></small>
        </div>
        <div class="form-group">
            <label for="published_year">Published Year:</label>
            <input type="number" class="form-control" id="published_year" name="published_year" min="1000" max="9999">
            <small id="published_year_error" class="error" style="color: red; font-size: small;"></small>
        </div>
        <div class="form-group">
            <label for="condition">Condition:</label>
            <input type="text" class="form-control" id="condition" name="condition">
            <small id="condition_error" class="error" style="color: red; font-size: small;"></small>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" class="form-control" id="price" name="price" min="0">
            <small id="price_error" class="error" style="color: red; font-size: small;"></small>
        </div>
        <div class="form-group">
            <label for="isbn">ISBN:</label>
            <input type="text" class="form-control" id="isbn" name="isbn">
            <small id="isbn_error" class="error" style="color: red; font-size: small;"></small>
        </div>
        <div class="form-group">
            <label for="isbn">Category:</label>
            <input type="text" class="form-control" id="category" name="category">
            <small id="category_error" class="error" style="color: red; font-size: small;"></small>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="book_image1">Upload Picture 1:</label>
                    <input type="file" class="form-control-file file-input" id="book_image1" name="book_image1">
                    <small id="pic1" style="color: red; font-size: small;"></small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="book_image2">Upload Picture 2:</label>
                    <input type="file" class="form-control-file file-input" id="book_image2" name="book_image2">
                    <small id="pic2" style="color: red; font-size: small;"></small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="book_image3">Upload Picture 3:</label>
                    <input type="file" class="form-control-file file-input" id="book_image3" name="book_image3">
                    <small id="pic3" style="color: red; font-size: small;"></small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary" name="add_ubook" id="submit-btn">Submit</button>
            </div>
        </div>
    </form>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // Function to validate each input field
        function validateInput(input, errorElement, errorMessage) {
            if (input.val().trim() === '') {
                errorElement.text(errorMessage);
                return false;
            } else {
                errorElement.text('');
                return true;
            }
        }

        // Function to validate file inputs
        function validateFileInput(fileInput, errorElement) {
            var file = fileInput[0].files[0];
            var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

            if (!file) {
                errorElement.text('Please select an image file');
                return false;
            } else if (!allowedExtensions.test(file.name)) {
                errorElement.text('Only JPG, JPEG, PNG, and GIF files are allowed');
                return false;
            } else {
                errorElement.text('');
                return true;
            }
        }

        // Validate form fields on input
        $('#book_name').on('input', function() {
            validateInput($(this), $('#book_name_error'), 'Book Name is required');
        });

        $('#author').on('input', function() {
            validateInput($(this), $('#author_error'), 'Author is required');
        });

        $('#publisher').on('input', function() {
            validateInput($(this), $('#publisher_error'), 'Publisher is required');
        });

        $('#published_year').on('input', function() {
            var year = $(this).val();
            if (year < 1000 || year > 9999 || isNaN(year)) {
                $('#published_year_error').text('Please enter a valid year');
            } else {
                $('#published_year_error').text('');
            }
        });

        $('#condition').on('input', function() {
            validateInput($(this), $('#condition_error'), 'Condition is required');
        });

        $('#price').on('input', function() {
            var price = $(this).val();
            if (isNaN(price)) {
                $('#price_error').text('Please enter a valid price');
            } else {
                $('#price_error').text('');
            }
        });

        $('#isbn').on('input', function() {
            validateInput($(this), $('#isbn_error'), 'ISBN is required');
        });

        $('#category').on('input', function() {
            validateInput($(this), $('#category_error'), 'Category is required');
        });

        // Validate file inputs
        $('.file-input').on('change', function() {
            validateFileInput($(this), $(this).siblings('.error'));
        });

        // Submit form on button click
        $('#submit-btn').click(function(e) {
            e.preventDefault();

            // Validate all fields
            var isValid = true;
            isValid = validateInput($('#book_name'), $('#book_name_error'), 'Book Name is required') && isValid;
            isValid = validateInput($('#author'), $('#author_error'), 'Author is required') && isValid;
            isValid = validateInput($('#publisher'), $('#publisher_error'), 'Publisher is required') && isValid;

            var year = $('#published_year').val();
            if (year < 1000 || year > 9999 || isNaN(year)) {
                $('#published_year_error').text('Please enter a valid year');
                isValid = false;
            }

            isValid = validateInput($('#condition'), $('#condition_error'), 'Condition is required') && isValid;

            var price = $('#price').val();
            if (isNaN(price)) {
                $('#price_error').text('Please enter a valid price');
                isValid = false;
            }

            isValid = validateInput($('#isbn'), $('#isbn_error'), 'ISBN is required') && isValid;
            isValid = validateInput($('#category'), $('#category_error'), 'Category is required') && isValid;

            // Validate file inputs
            $('.file-input').each(function() {
                isValid = validateFileInput($(this), $(this).siblings('.error')) && isValid;
            });

            // If all validations pass, submit the form
            if (isValid) {
                $('#add-book-form').submit();
            }
        });
    });
</script>

<?php
include("../includes/scripts.php");
include("../includes/footer.php");
?>
