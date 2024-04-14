<?php
session_start();
include("../dbcon.php");

// Handling item removal from the cart
if (isset($_GET['remove'])) {
    $cart_id = $_GET['remove'];

    // Prepare and execute SQL query to delete the cart item
    $stmt = $conn->prepare("DELETE FROM tbl_cart WHERE cart_id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();

    // Redirect to cart.php after item removal
    header("Location: cartadd.php");
    exit();
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $user_id = $_SESSION["auth_user"]["user_id"];
    $address_line1 = trim($_POST["address_line1"]);
    $address_line2 = trim($_POST["address_line2"]);
    $district = trim($_POST["district"]);
    $state = trim($_POST["state"]);
    $pincode = trim($_POST["pincode"]);
    $payment_method = $_POST["payment_method"];

    // Check if all required fields are filled
    if (empty($address_line1) ||  empty($district) || empty($state) || empty($pincode) || $payment_method == "credit_card") {
        $error = "All fields are required.";
    } else {
        // Insert user details into the database
        $stmt = $conn->prepare("INSERT INTO tbl_shippingadr (user_id, address1, address2, pincode,  district, state) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $user_id, $address_line1, $address_line2, $pincode, $district, $state);
        $stmt->execute();
        $stmt->close();

        // Insert payment method into the database
        $stmt = $conn->prepare("INSERT INTO tbl_payment_method (user_id, payment_method) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $payment_method);
        $stmt->execute();
        $stmt->close();

        // Redirect user to a confirmation page or perform further actions
        header("Location: confirmation_page.php");
        exit();
    }
}
?>
<?php

include("../config.php");
include("../authentication.php");
include("../includes/header.php");
include("../includes/topbar.php");

?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card mt-4">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Fill in Your Details</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="address_line1">Address Line 1:</label>
                            <input type="text" class="form-control" id="address_line1" name="address_line1" required>
                        </div>
                        <div class="form-group">
                            <label for="address_line2">Address Line 2:</label>
                            <input type="text" class="form-control" id="address_line2" name="address_line2">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="pincode">Pincode:</label>
                                <input type="text" class="form-control" id="pincode" name="pincode" required>
                            </div>
                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="district">District:</label>
                                <input type="text" class="form-control" id="district" name="district" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="state">State:</label>
                                <input type="text" class="form-control" id="state" name="state" required>
                            </div>
                        </div>
                        <!-- Payment method selection -->
                        <div class="form-group">
                            <label for="payment_method">Payment Method:</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="credit_card">Choose</option>
                                <option value="cod">Cash on delivery</option>
                                <option value="Razorpay">Razorpay</option>
                            </select>
                        </div>
                        <?php if (isset($error)) : ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card mt-5">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get the current user_id from the session
                            $user_id = $_SESSION["auth_user"]["user_id"];

                            // Prepare and execute SQL query to fetch cart items of the current user
                            $stmt = $conn->prepare('SELECT c.*, b.* FROM tbl_cart c INNER JOIN tbl_book b ON c.book_id = b.book_id WHERE c.user_id = ?');
                            $stmt->bind_param("i", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $total_price = 0;
                            $grand_total = 0;
                            while ($row = $result->fetch_assoc()):
                            ?>
                                <tr>
                                    <td><img src='../images/<?php echo $row['book_image']; ?>' alt='<?php echo $row['book_name']; ?>' width="50"></td>
                                    <td><?= $row['book_name'] ?></td>
                                    <td>Rs.<?= number_format($row['book_price'], 2); ?></td>
                                    <td>
                                        <a href="?remove=<?= $row['cart_id'] ?>" class="text-danger lead" onclick="return confirm('Are you sure want to remove this item?');"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <?php $grand_total += ($row["book_price"]*$row["quantity"]) ?>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <div class="text-right">
                        <h5>Grand Total: Rs.<?= number_format($grand_total, 2); ?></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $('#pincode').on('input', function(){
        var pincode = $(this).val().trim();
        if(pincode.length === 6) {
            $.ajax({
                type: 'POST',
                url: '../delivery_boy/fetch_location_data.php',
                data: {pincode: pincode},
                dataType: 'json',
                success: function(data){
                    if(data.error) {
                        $('#pincodeError').text(data.error);
                    } else {
                        $('#district').val(data.district);
                        $('#state').val(data.state);
                        $('#country').val(data.country);
    
                        $('#pincodeError').text('');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText); // Log any errors for debugging
                }
            });
        } else {
               // Clear existing location data if pincode is not complete
               $('#district').val('');
            $('#state').val('');
            $('#country').val('');

            $('#pincodeError').text('Please enter a valid Indian pincode with six digits.');
        }
    });
});
</script>
<?php
include("../includes/footer.php");
?>
