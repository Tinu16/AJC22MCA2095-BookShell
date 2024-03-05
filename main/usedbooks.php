<?php
session_start();

include("../dbcon.php");
include("../includes/header.php");
//include("../includes/customer_sidebar.php");
include("../includes/topbar.php");
include("../message.php");

?>

<div style="clear:both;"></div>

<!-- Form to Add a New Book -->
<div class="container mt-2">
    <h2>Sell Your Book</h2>
    <form id="add-book-form" enctype="multipart/form-data" action="ubook.php" method="POST" onsubmit="return validateForm()">
    
        <div class="form-group">
            <label for="book_name">Book Name:</label>
            <input type="text" class="form-control" id="book_name" name="book_name" oninput="validateBookName()">
            <small id="book_name_error" class="error" style="color: red; font-size: small;"></small>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="author">Author:</label>
                <select name="author" class="form-control" oninput="validateAuthor()">
                    <option value="" selected disabled>Select Author</option>
                    <?php
                    $sql = mysqli_query($conn, "SELECT author_id, author_name FROM tbl_author WHERE author_status = 1");

                    while ($row = mysqli_fetch_array($sql)) {
                        ?>
                        <option value="<?php echo $row["author_id"] ?>"><?php echo $row["author_name"] ?></option>
                        <?php
                    }
                    ?>
                </select>
                <small id="author_error" class="error" style="color: red; font-size: small;"></small>
            </div>
        
            <div class="form-group col-md-4">
                <label for="condition">Condition:</label>
                <input type="text" class="form-control" id="condition" name="condition" oninput="validateCondition()">
                <small id="condition_error" class="error" style="color: red; font-size: small;"></small>
            </div>
            <div class="form-group col-md-4">
                <label for="price">Expected Price:</label>
                <input type="number" class="form-control" id="price" name="price" min="0" oninput="validatePrice()">
                <small id="price_error" class="error" style="color: red; font-size: small;"></small>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="publisher">Publisher:</label>
                <select name="publisher" class="form-control" oninput="validatePublisher()">
                <option value="" selected disabled>Select Publisher</option>
                        <?php
                        $sql = mysqli_query($conn, "SELECT publisher_id,publisher_name FROM tbl_publisher where publisher_status=1");

                        while ($row = mysqli_fetch_array($sql)) {
                        ?>
                            <option value="<?php echo $row["publisher_id"] ?>"><?php echo $row["publisher_name"] ?></option>
                        <?php } ?>
                    </select>
                <small id="publisher_error" class="error" style="color: red; font-size: small;"></small>
            </div>
            <div class="form-group col-md-6">
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
            
        </div>

        
        <div class="form-group">
            <label for="des">Short Description:</label>
            <input type="text" class="form-control" id="des" name="des" oninput="validateDescription()">
            <small id="des_error" class="error" style="color: red; font-size: small;"></small>
        </div>

        <div class="form-group row">
    <div class="col-md-4">
        <input type="file" class="form-control-file file-input" id="image1" name="image1" onchange="validateImage(this)">
    </div>
    <div class="col-md-4">
        <input type="file" class="form-control-file file-input" id="image2" name="image2" onchange="validateImage(this)">
    </div>
    <div class="col-md-4">
        <input type="file" class="form-control-file file-input" id="image3" name="image3" onchange="validateImage(this)">
    </div>
</div>
<div id="image_preview" class="row mt-2"></div>
<small id="pic_error" class="error" style="color: red; font-size: small;"></small>


        <div class="row">
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary" name="add_ubook" id="submit-btn">Submit</button>
            </div>
        </div>
    </form>
</div>


<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
function displayImagePreview(input) {
    const file = input.files[0];
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const imagePreview = document.getElementById('image_preview');
        const imgContainer = document.createElement('div');
        imgContainer.className = 'image-container';
        
        const img = document.createElement('img');
        img.src = e.target.result;
        img.className = 'img-thumbnail';
        imgContainer.appendChild(img);
        
        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.className = 'delete-btn';
        deleteBtn.innerHTML = '&times;';
        deleteBtn.addEventListener('click', function() {
            imgContainer.remove(); // Remove the image container
            input.value = ''; // Clear the file input
        });
        imgContainer.appendChild(deleteBtn);
        
        imagePreview.appendChild(imgContainer);
    }
    
    reader.readAsDataURL(file);
}

// Image preview
document.getElementById('image1').addEventListener('change', function() {
    displayImagePreview(this);
});
document.getElementById('image2').addEventListener('change', function() {
    displayImagePreview(this);
});
document.getElementById('image3').addEventListener('change', function() {
    displayImagePreview(this);
});
// Add more images
function validateImage(input) {
    const file = input.files[0];
    const fileSize = file.size; // File size in bytes
    const allowedFormats = ['image/jpeg', 'image/png', 'image/gif']; // Allowed image formats

    // Check file size (assuming max size is 5MB)
    if (fileSize > 5 * 1024 * 1024) {
        document.getElementById('pic_error').textContent = 'File size exceeds 5MB.';
        input.value = ''; // Clear the file input
    } else if (!allowedFormats.includes(file.type)) {
        document.getElementById('pic_error').textContent = 'Only JPEG, PNG, and GIF formats are allowed.';
        input.value = ''; // Clear the file input
    } else {
        document.getElementById('pic_error').textContent = '';
    }
}



function validateBookName() {
    const bookName = document.getElementById('book_name').value.trim(); // Remove leading and trailing whitespaces
    const errorElement = document.getElementById('book_name_error');
    const regex = /^[A-Za-z][A-Za-z0-9\s]*$/; // Regex pattern to allow only letters, digits, and spaces, with the first character being a letter

    if (!bookName) {
        errorElement.textContent = 'Book name is required.';
    } else if (!regex.test(bookName)) {
        errorElement.textContent = 'Book name must start with a letter and contain only letters, digits, and spaces.';
    } else {
        errorElement.textContent = '';
    }
}


function validateDescription() {
    const description = document.getElementById('des').value.trim(); // Remove leading and trailing whitespaces
    const errorElement = document.getElementById('des_error');
    errorElement.textContent = description ? '' : 'Short description is required.';
}

function validateAuthor() {
    const author = document.getElementById('author').value;
    const errorElement = document.getElementById('author_error');
    errorElement.textContent = author ? '' : 'Please select an author.';
}

function validatePublisher() {
    const publisher = document.getElementById('publisher').value;
    const errorElement = document.getElementById('publisher_error');
    errorElement.textContent = publisher ? '' : 'Please select a publisher.';
}

function validatePublishedYear() {
    const publishedYear = document.getElementById('published_year').value;
    const errorElement = document.getElementById('published_year_error');
    errorElement.textContent = publishedYear ? '' : 'Please select a published year.';
}

function validateCondition() {
    const condition = document.getElementById('condition').value.trim(); // Remove leading and trailing whitespaces
    const errorElement = document.getElementById('condition_error');
    errorElement.textContent = condition ? '' : 'Condition is required.';
}

function validatePrice() {
    const price = document.getElementById('price').value.trim(); // Remove leading and trailing whitespaces
    const errorElement = document.getElementById('price_error');
    errorElement.textContent = (!isNaN(price) && parseFloat(price) > 0) ? '' : 'Price must be a valid number greater than 0.';
}

// Add an event listener to the form submit event
document.getElementById("add-book-form").addEventListener("submit", function(event) {
    // Validate the form
    if (!validateForm()) {
        // Prevent form submission if validation fails
        event.preventDefault();
    }
});

// Function to validate the form
function validateForm() {
    validateBookName();
    validateDescription();
    validateAuthor();
    validatePublisher();
    validatePublishedYear();
    validateCondition();
    validatePrice();
    
    // Check if any error message exists
    const errorMessages = document.querySelectorAll('.error');
    for (let i = 0; i < errorMessages.length; i++) {
        if (errorMessages[i].textContent) {
            return false; // Return false if any validation fails
        }
    }
    return true; // Return true if all fields are valid
}


</script>

<style>
.image-container {
    position: relative;
    display: inline-block;
    margin-right: 10px;
}

.img-thumbnail {
    width: 200px;
    height: 200px;
}

.delete-btn {
    position: absolute;
    top: 0;
    right: 0;
    background-color: rgba(255, 255, 255, 0.7);
    border: none;
    border-radius: 50%;
    padding: 5px;
    cursor: pointer;
}
</style>


<?php
include("../includes/scripts.php");
include("../includes/footer.php");
?>
