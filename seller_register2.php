<?php
session_start();
include("../dbcon.php");
include("../includes/header.php");

if(isset($_GET['user_id']) ) {
    // Retrieve the values from the URL
    $user_id = $_GET['user_id'];
} else {
    // Redirect back to the previous page or handle the error
    header("Location: seller_register.php");
    exit();
}
?>
<body class="bg-gradient-primary">
    <div class="container">
        
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <!-- Left Column - Personal Details -->
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Personal Details</h1>
                            </div>
                            <!-- Personal Details Form -->
                            <form  method="POST" action="seller_registercode.php" id="firstForm" enctype="multipart/form-data" onSubmit="return validateForm()">
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">    
                            <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="first_name" id="first_name" placeholder="First Name" oninput="validateFirstName()">
                                    <small id="firstNameError" style="color: red;"></small>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="middle_name" id="middle_name" placeholder="Middle Name" oninput="validateMiddleName()">
                                    <small id="middleNameError" style="color: red;"></small>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="last_name" id="last_name" placeholder="Last Name" oninput="validateLastName()">
                                    <small id="lastNameError" style="color: red;"></small>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="store" id="store" placeholder="Store Name" oninput="validateStoreName()">
                                    <small id="storeNameError" style="color: red;"></small>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="pan" id="pan" placeholder="PAN" oninput="validatePAN()">
                                    <small id="panError" style="color: red;"></small>
                                </div>
                                <div class="form-group">
                                    <label>Aadhar card
                                    <input type="file" class="form-control-file" name="aadhar_card_file" id="aadhar_card_file" onchange="validateAadharCard()">
                                    <small id="aadharCardError" style="color: red;"></small></label>
                                </div>
                        </div>
                    </div>
                    <!-- Right Column - Address and Bank Details -->
                    <div class="col-lg-6">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Address & Bank Details</h1>
                            </div>
                            <!-- Address and Bank Details Form -->
                            
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" name="address_line_1" id="address_line_1" placeholder="Address Line 1" oninput="validateAddressLine1()">
                                <small id="addressLine1Error" style="color: red;"></small>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" name="address_line_2" id="address_line_2" placeholder="Address Line 2" oninput="validateAddressLine2()">
                                <small id="addressLine2Error" style="color: red;"></small>
                            </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="pincode" id="pincode" placeholder="Pincode" oninput="validatePincode()">
                                    <small id="pincodeError" style="color: red;"></small>
                                </div>
                                <div class="form-group d-flex">
                                <input type="text" class="form-control form-control-user flex-fill mr-2"  name="district" id="district" placeholder="District">
                                <input type="text" class="form-control form-control-user flex-fill mr-2" name="state" id="state" placeholder="State">
                                <input type="text" class="form-control form-control-user flex-fill"  name="country" id="country" placeholder="Country">
                                </div>
                               
                                <!-- Bank Details Section -->
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="account_number" id="account_number" placeholder="Account Number" oninput="validateAccountNumber()">
                                    <small id="accountNumberError" style="color: red;"></small>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="ifsc_code" id="ifsc_code" placeholder="IFSC Code" oninput="validateIFSCCode()">
                                    <small id="ifscCodeError" style="color: red;"></small>
                                </div>
                                <!-- Submit Button -->
                                <div class="text-right">
                                    <button onclick="window.history.back();" class="btn btn-secondary btn-user mr-2">Back</button>
                                    <input type="submit" id="submitLastForm" name="sellerreg1" class="btn btn-primary btn-user" value="Submit" onSubmit="return validateForm()">
                                </div>
                            </form>
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsOVk8WNgJygXTj6B69zXK_bBQ7RPcs7M&callback=initMap" async defer></script>
                               <script>
   
   function validateForm() {
    // Perform individual validations
    if (!validateFirstName() || !validateMiddleName() || !validateLastName() || !validateAadharCard()  || !validateLicense() || !validateAddressLine1() || !validateAddressLine2() || !validatePincode() || !validateAccountNumber() || !validateIFSCCode()) {
        return false;
    }

    // Check for empty fields
    var inputs = document.querySelectorAll('input[type="text"], input[type="file"]');
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].value.trim() === '') {
            alert('Please fill out all fields.');
            return false;
        }
    }

    return true;
}
function initializeInputListeners() {
    var firstNameInput = document.getElementById("first_name");
    var middleNameInput = document.getElementById("middle_name");
    var lastNameInput = document.getElementById("last_name");

    // Add event listener to first name input
    firstNameInput.addEventListener('input', function() {
        this.value = this.value.replace(/\s/g, ''); // Remove spaces
    });

    // Add event listener to middle name input
    middleNameInput.addEventListener('input', function() {
        this.value = this.value.replace(/\s/g, ''); // Remove spaces
    });

    // Add event listener to last name input
    lastNameInput.addEventListener('input', function() {
        this.value = this.value.replace(/\s/g, ''); // Remove spaces
    });
}

initializeInputListeners();

function validateFirstName() {
    var firstName = document.getElementById("first_name").value.trim();
    var firstNameError = document.getElementById("firstNameError");

    // Clear previous error message
    firstNameError.innerHTML = "";

    // Check if first name is empty
    if (firstName === "") {
        firstNameError.innerHTML = "First name is required.";
        return false;
    }

    // Check if first name contains only letters and is at least 3 characters long
    if (!/^[a-zA-Z]{3,}$/.test(firstName)) {
        firstNameError.innerHTML = "First name must contain only letters and be at least 3 characters long.";
        return false;
    }

    return true;
}
function validateStoreName() {
    var storeName = document.getElementById("store").value.trim();
    var storeNameError = document.getElementById("storeNameError");

    // Clear previous error message
    storeNameError.innerHTML = "";

    // Check if store name is empty
    if (storeName === "") {
        storeNameError.innerHTML = "Store name is required.";
        return false;
    }

    // Check if store name contains only numbers and letters
    if (!/^[a-zA-Z0-9]+$/.test(storeName)) {
        storeNameError.innerHTML = "Store name must contain only numbers and letters.";
        return false;
    }

    return true;
}
function validateMiddleName() {
    var middleName = document.getElementById("middle_name").value.trim();
    var middleNameError = document.getElementById("middleNameError");

    // Clear previous error message
    middleNameError.innerHTML = "";

    // Check if middle name contains only letters or spaces, and is not empty
    if (!/^[a-zA-Z\s]+$/.test(middleName)) {
        middleNameError.innerHTML = "Middle name must contain only letters.";
        return false;
    }

    return true;
}

function validateLastName() {
    var lastName = document.getElementById("last_name").value.trim();
    var lastNameError = document.getElementById("lastNameError");

    // Clear previous error message
    lastNameError.innerHTML = "";

    // Check if last name contains only letters or spaces, and is not empty
    if (!/^[a-zA-Z\s]+$/.test(lastName)) {
        lastNameError.innerHTML = "Last name must contain only letters.";
        return false;
    }

    return true;
}



function validateAadharCard() {
    var aadharCardFile = document.getElementById("aadhar_card_file");
    var aadharCardError = document.getElementById("aadharCardError");

    // Clear previous error message
    aadharCardError.innerHTML = "";

    // Check if aadhar card file is selected
    if (aadharCardFile.files.length === 0) {
        aadharCardError.innerHTML = "Please select an Aadhar card file.";
        return false;
    }

    // Check if the file type is PDF
    if (aadharCardFile.files[0].type !== "application/pdf") {
        aadharCardError.innerHTML = "Please upload a PDF file.";
        return false;
    }

    // Check if aadhar card file size is within limit (assuming maximum size of 5 MB)
    if (aadharCardFile.files[0].size > 5 * 1024 * 1024) {
        aadharCardError.innerHTML = "Aadhar card file size must be less than 5 MB.";
        return false;
    }

    return true;
}

function validatePAN() {
    var panInput = document.getElementById("pan").value;
    var panError = document.getElementById("panError");

    // Clear previous error message
    panError.innerHTML = "";

    // Check if the PAN follows the Indian format
    if (!/^[A-Z]{5}[0-9]{4}[A-Z]{1}/.test(panInput)) {
        panError.innerHTML = "Please enter a valid PAN (e.g., ABCDE1234F).";
    }
    if (panInput.length > 10) {
        document.getElementById("pan").value = panInput.slice(0, 10);
    }
}
                            
    $(document).ready(function(){
    $('#pincode').on('input', function(){
        var pincode = $(this).val().trim();
        if(pincode.length === 6) {
            $.ajax({
                type: 'POST',
                url: 'fetch_location_data.php',
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
            // Clear district, state, and country fields if pincode is less than 6 digits
            $('#district').val('');
            $('#state').val('');
            $('#country').val('');
            $('#pincodeError').text('');
        }
    });
});

function validateAddressLine1() {
        var addressLine1 = document.getElementById("address_line_1").value.trim();
        var addressLine1Error = document.getElementById("addressLine1Error");

        // Clear previous error message
        addressLine1Error.innerHTML = "";

        // Check if address line 1 is empty
        if (addressLine1 === "") {
            addressLine1Error.innerHTML = "Address Line 1 is required.";
            return false;
        }

        return true;
    }

    function validateAddressLine2() {
        var addressLine2 = document.getElementById("address_line_2").value.trim();
        var addressLine2Error = document.getElementById("addressLine2Error");

        // Clear previous error message
        addressLine2Error.innerHTML = "";

        // Check if address line 2 is empty
        if (addressLine2 === "") {
            addressLine2Error.innerHTML = "Address Line 2 is required.";
            return false;
        }

        return true;
    }

        function validatePincode() {
    var pincodeInput = document.getElementById("pincode");
    var pincodeError = document.getElementById("pincodeError");

    // Clear previous error message
    pincodeError.innerHTML = "";

    // Retrieve the pincode value
    var pincode = pincodeInput.value.trim();

    // Remove non-numeric characters
    pincode = pincode.replace(/\D/g, '');

    // Limit the length to six digits
    pincode = pincode.slice(0, 6);

    // Update the pincode value
    pincodeInput.value = pincode;

    // Perform validation for pincode format (Indian format)
    if (pincode.length !== 6) {
        pincodeError.textContinnerHTMLent = "Please enter a valid Indian pincode with six digits.";
        return false;
    }

    return true;
}
function validateAccountNumber() {
    var accountNumber = document.getElementById("account_number").value.trim();
    var accountNumberError = document.getElementById("accountNumberError");

    // Clear previous error message
    accountNumberError.innerHTML = "";

    // Check if account number is empty
    if (accountNumber === "") {
        accountNumberError.innerHTML = "Account number is required.";
        return false;
    }

    // Regular expression to match the format of the account number
    var accountNumberRegex = /^[0-9]{9,18}$/; // Assuming account numbers are between 9 to 18 digits

    // Check if account number matches the expected format
    if (!accountNumberRegex.test(accountNumber)) {
        accountNumberError.innerHTML = "Please enter a valid account number.";
        return false;
    }

    return true;
}
function validateIFSCCode() {
    var ifscCode = document.getElementById("ifsc_code").value.trim();
    var ifscCodeError = document.getElementById("ifscCodeError");

    // Clear previous error message
    ifscCodeError.innerHTML = "";

    // Check if IFSC code is empty
    if (ifscCode === "") {
        ifscCodeError.innerHTML = "IFSC code is required.";
        return false;
    }

    // Regular expression to match the format of the IFSC code
    var ifscCodeRegex = /^[A-Z]{4}[0][A-Z0-9]{6}$/; // Assuming IFSC codes follow the standard format of 4 letters followed by 0 and 6 alphanumeric characters

    // Check if IFSC code matches the expected format
    if (!ifscCodeRegex.test(ifscCode)) {
        ifscCodeError.innerHTML = "Please enter a valid IFSC code.";
        return false;
    }

    return true;
}

       
    </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
