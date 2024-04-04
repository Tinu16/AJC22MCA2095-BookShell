<?php
session_start();
include("../dbcon.php");


if (isset($_POST['add_book'])) {
    // Retrieve seller_id from session or wherever it's stored
    $seller_id = $_SESSION['auth_user']["user_id"]; // Adjust this based on where you store seller_id

    // Retrieve form data
    $book = mysqli_real_escape_string($conn, $_POST['book']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $volume = mysqli_real_escape_string($conn, $_POST['volume']);
    $edition = mysqli_real_escape_string($conn, $_POST['edition']);
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $publisher = mysqli_real_escape_string($conn, $_POST['publisher']);
    $page = mysqli_real_escape_string($conn, $_POST['page']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $language = mysqli_real_escape_string($conn, $_POST['language']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    $i = $_FILES["image"]["name"];
    $path = $_FILES["image"]["tmp_name"];
    move_uploaded_file($_FILES["image"]["tmp_name"],"../images/".$_FILES["image"]["name"]);
    // File upload handling for digital book (PDF)
    if ($_POST['upload_digital_book'] == 'yes' && isset($_FILES['digital_book'])) {
        $digital_book_name = $_FILES['digital_book']['name'];
        $digital_book_tmp = $_FILES['digital_book']['tmp_name'];
        $digital_book_path = "digital_books/";
        $digital_book_target = $digital_book_path . basename($digital_book_name);

        if (move_uploaded_file($digital_book_tmp, $digital_book_target)) {
            $insert_ebook_query = "INSERT INTO tbl_ebook (ebook_pdf, ebook_status) VALUES ('$digital_book_name', 'active')";
            if (mysqli_query($conn, $insert_ebook_query)) {
                $ebook_id = mysqli_insert_id($conn);
                $insert_book_query = "INSERT INTO tbl_book (seller_id, book_name, author_id, book_volume, book_edition, book_isbn, category_id, publisher_id, book_page, book_description, book_price, book_quantity, book_language,book_image, ebook_id) VALUES ('$seller_id', '$book', '$author', '$volume', '$edition', '$isbn', '$category', '$publisher', '$page', '$description', '$price', '$quantity', '$language','$i', '$ebook_id')";
                if (mysqli_query($conn, $insert_book_query)) {
                    $_SESSION["message"] = "Book added successfully.";
                    header("Location: addbook.php");
                    exit();
                } else {
                    $_SESSION["message"] = "Error inserting book details: " . mysqli_error($conn);
                    header("Location: addbook.php");
                    exit();
                }
            } else {
                $_SESSION["message"] = "Error inserting ebook details: " . mysqli_error($conn);
                header("Location: addbook.php");
                exit();
            }
        } else {
            $_SESSION["message"] = "Error uploading digital book.";
            header("Location: addbook.php");
            exit();
        }
    } else {
        $insert_book_query = "INSERT INTO tbl_book (seller_id, book_name, author_id, book_volume, book_edition, book_isbn, category_id, publisher_id, book_page, book_description, book_price, book_quantity, book_language) VALUES ('$seller_id', '$book', '$author', '$volume', '$edition', '$isbn', '$category', '$publisher', '$page', '$description', '$price', '$quantity', '$language')";
        if (mysqli_query($conn, $insert_book_query)) {
            $_SESSION["message"] = "Book added successfully.";
            header("Location: addbook.php");
            exit();
        } else {
            $_SESSION["message"] = "Error inserting book details: " . mysqli_error($conn);
            header("Location: addbook.php");
            exit();
        }
    }
}

?>
<?php
include("../includes/header.php");
include("../includes/seller_sidebar.php");
include("../includes/topbar.php");
include("../message.php");
?>
<div style="clear:both;"></div>

<!-- Form to Add a New Book -->
<div class="container mt-2">
    <h2>ADD BOOK</h2>
    <!-- Nested Row within Card Body -->
    <form class="user" action="#" method="POST" enctype="multipart/form-data">
        <div class="col-lg-7">
            <div class="p-7">
                <div class="text-center">
                    <?php
                    // Include any messages here
                    include("../message.php");
                    ?>
                </div>

                <div class="form-group">
                    <label for="book">Book</label>
                    <input type="book" name="book" class="form-control" id="book">
                </div>

                <div class="form-group">
                    <label>Author</label>
                    <select name="author" class="form-control" required>
                        <?php
                        $sql = mysqli_query($conn, "SELECT author_id,author_name FROM tbl_author where author_status=1");

                        while ($row = mysqli_fetch_array($sql)) {
                        ?>
                            <option value="<?php echo $row["author_id"] ?>"><?php echo $row["author_name"] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="volume">Volume</label>
                    <input type="volume" name="volume" class="form-control" id="volume">
                </div>

                <div class="form-group">
                    <label for="edition">Edition</label>
                    <input type="edition" name="edition" class="form-control" id="edition">
                </div>

                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" name="isbn" class="form-control" id="isbn">
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category" class="form-control" required>
                        <?php
                        $sql = mysqli_query($conn, "SELECT subcategory_id,subcategory_name FROM tbl_subcategory where subcategory_status=1");

                        while ($row = mysqli_fetch_array($sql)) {
                        ?>
                            <option value="<?php echo $row["subcategory_id"] ?>"><?php echo $row["subcategory_name"] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Publisher</label>
                    <select name="publisher" class="form-control" required>
                        <?php
                        $sql = mysqli_query($conn, "SELECT publisher_id,publisher_name FROM tbl_publisher where publisher_status=1");

                        while ($row = mysqli_fetch_array($sql)) {
                        ?>
                            <option value="<?php echo $row["publisher_id"] ?>"><?php echo $row["publisher_name"] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="page">Total Number of Pages</label>
                    <input type="page" name="page" class="form-control" id="page">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" class="form-control" id="description">
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="price" name="price" class="form-control" id="price">
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="quantity" name="quantity" class="form-control" id="quantity">
                </div>

                <div class="form-group">
                    <label for="language">Language</label>
                    <input type="language" name="language" class="form-control" id="language">
                </div>

                <div class="form-group">
                    <label for="image">Upload Image</label>
                    <input type="file" name="image" class="form-control" id="image">
                </div>

                <!-- Option to upload a digital book -->
                <div class="form-group">
                    <label for="upload_digital_book">Do you want to upload the book in digital format?</label>
                    <select name="upload_digital_book" id="upload_digital_book" class="form-control">
                        <option value="no">No</option>
                        <option value="yes">Yes</option>
                    </select>
                </div>

                <!-- Field to upload digital book -->
                <div id="digital_book_field" style="display: none;">
                    <div class="form-group">
                        <label for="digital_book">Upload Digital Book</label>
                        <input type="file" name="digital_book" class="form-control" id="digital_book">
                    </div>
                </div>

                <div class="form-group">
                    <input type="submit" name="add_book" class="btn btn-success btn-user btn-block" value="ADD">
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Script to show/hide digital book upload field based on user selection -->
<script>
    document.getElementById('upload_digital_book').addEventListener('change', function() {
        var digitalBookField = document.getElementById('digital_book_field');
        if (this.value === 'yes') {
            digitalBookField.style.display = 'block';
        } else {
            digitalBookField.style.display = 'none';
        }
    });
</script>

<?php
include("../includes/scripts.php");
?>
</body>
