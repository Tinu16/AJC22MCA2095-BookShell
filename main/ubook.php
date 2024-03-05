<?php
session_start();
include("../dbcon.php");

// Check if the form is submitted
if(isset($_POST['add_ubook'])) {
    // Retrieve form data
    $seller_id = $_SESSION['auth_user']["user_id"]; // Assuming you have a session variable for seller id
    $book_name = $_POST['book_name'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $year = $_POST['published_year'];
    $condition = $_POST['condition'];
    $price = $_POST['price'];
    $description = $_POST['des'];
    
    // Image names
    $image1 = $_FILES['image1']['name'];
    $image2 = $_FILES['image2']['name'];
    $image3 = $_FILES['image3']['name'];
    
    // Image upload directory
    $upload_dir = "../images/";

    // Move uploaded images to the destination directory
    move_uploaded_file($_FILES['image1']['tmp_name'], $upload_dir . $image1);
    move_uploaded_file($_FILES['image2']['tmp_name'], $upload_dir . $image2);
    move_uploaded_file($_FILES['image3']['tmp_name'], $upload_dir . $image3);

    // Insert image filenames into ubook_pics table
    $sql_insert_images = "INSERT INTO `tbl_ubookpics`(`tbl_ubookpic1`, `tbl_ubookpic2`, `tbl_ubookpic3`) VALUES ('$image1', '$image2', '$image3')";
    if(mysqli_query($conn, $sql_insert_images)) {
        // Get the last inserted ID
        $pic_id = mysqli_insert_id($conn);
        
        // Insert book details along with the associated image ID into tbl_usedbooks table
        $sql_insert_book = "INSERT INTO tbl_usedbooks (ubook_sellerid, ubook_name, ubook_author, ubook_publisher, ubook_year, ubook_condition, ubook_price, ubook_picid, ubook_description) 
                            VALUES ('$seller_id', '$book_name', '$author', '$publisher', '$year', '$condition', '$price', '$pic_id', '$description')";
        if(mysqli_query($conn, $sql_insert_book)) {
            $_SESSION["message"] = "Data inserted successfully!";
            header("Location: ubook_view.php");
            exit(0);
        } else {
            $_SESSION["message"] = "Error inserting book details: " ;
            header("Location: usedbooks.php");
            exit(0);
        }
    } else {
        $_SESSION["message"] = "Error inserting image filenames: " ;
        header("Location:  usedbooks.php");
        exit(0);
    }
}

?>
