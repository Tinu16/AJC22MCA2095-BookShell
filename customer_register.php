<?php
session_start();
    include("../dbcon.php");
    include("../includes/header.php");
?>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-register-image" style="background-image: url('../img/book2.jpeg')"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                            <?php
                                    include("../message.php");
                               ?>
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" method="POST" action="customer_registercode.php" >
                                <div class="form-group row">
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-user" name="email" id="email" placeholder="Email Address" onInput="validateEmail()">
                                    <small id="email_error" style="color: red;"></small>
                                </div>
                                <div class="form-group">
                                    <input type="phone" class="form-control form-control-user" name="phone" id="phone" placeholder="Mobile Number" oninput="validatePhone()" >
                                    <small id="phoneError" style="color: red;"></small>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Password" oninput="validatePassword()">
                                        <small id="passwordError" style="color: red;"></small>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" name="confirmPassword" id="confirmPassword" placeholder="Repeat Password" oninput="validateConfirmPassword()" required>
                                        <small id="confirmPasswordError" style="color: red;"></small>
                                        </div>
                                    <input type="hidden" name="usertype" value="customer">
                                </div>
                                <input type="submit" name="register_btn" class="btn btn-primary btn-user btn-block" value="Register Account" onsubmit="return validateForm()">
                                <hr>
                                <a href="index.html" class="btn btn-google btn-user btn-block">
                                    <i class="fab fa-google fa-fw"></i> Register with Google
                                </a>
                            </form>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="../login.php">Already have an account? Login!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>

function validateEmail() {
          var email = document.getElementById('email').value;
          var email_error = document.getElementById('email_error');
    
          var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/.test(email);
          let message = "";

        if (!emailPattern) 
            {
                message += "Enter a valid email.<br>";
            }
       
            email_error.innerHTML = message;

        if (emailPattern) 
            {
                email_error.style.color = "green";
                email_error.textContent = "Email is valid!";
            } 
        else 
            {
                email_error.style.color = "red";
            }
      }
function validatePhone() {
    var phoneInput = document.getElementById("phone").value;
    var phoneError = document.getElementById("phoneError");

    // Clear previous error message
    phoneError.innerHTML = "";

    // Check if the phone number contains letters
    if (/[a-zA-Z]/.test(phoneInput)) {
        phoneError.innerHTML = "Phone number must not contain letters.";
    }

    // Restrict input to 10 digits
   if (phoneInput.length > 10) {
        document.getElementById("phone").value = phoneInput.slice(0, 10);
    }

    // Check if the phone number follows the Indian format
    if (!/^(\+91)?[6789]\d{9}$/.test(phoneInput)) {
        phoneError.innerHTML = "Please enter a valid Indian phone number.";
    }
}

function validatePassword() {
    var passwordInput = document.getElementById("password").value;
    var passwordError = document.getElementById("passwordError");

    // Clear previous error messages
    passwordError.innerHTML = "";

    // Check if the password meets the specified criteria
    if (!/(?=.*\d)/.test(passwordInput)) {
        passwordError.innerHTML += "Password must contain at least 1 digit.<br>";
    }
    if (!/(?=.*[!@#$%^&*])/.test(passwordInput)) {
        passwordError.innerHTML += "Password must contain at least 1 symbol (!@#$%^&*).<br>";
    }
    if (!/(?=.*[a-z])/.test(passwordInput)) {
        passwordError.innerHTML += "Password must contain at least 1 lowercase letter.<br>";
    }
    if (!/(?=.*[A-Z])/.test(passwordInput)) {
        passwordError.innerHTML += "Password must contain at least 1 uppercase letter.<br>";
    }
    if (passwordInput.length < 6) {
        passwordError.innerHTML += "Password must be at least 6 characters.<br>";
    }
}

function validateConfirmPassword() {
    var passwordInput = document.getElementById("password").value;
    var confirmPasswordInput = document.getElementById("confirmPassword").value;
    var confirmPasswordError = document.getElementById("confirmPasswordError");

    // Clear previous error message
    confirmPasswordError.innerHTML = "";

    // Check if the confirm password matches the entered password
    if (passwordInput !== confirmPasswordInput) {
        confirmPasswordError.innerHTML = "Passwords do not match.";
    }
}

function validateForm() {
    // Perform individual validations
    validatePhone();
    validatePassword();
    validateConfirmPassword();

    // Check if there are any error messages and if any required field is empty
    if (
        document.getElementById("phoneError").innerHTML !== "" ||
        document.getElementById("passwordError").innerHTML !== "" ||
        document.getElementById("confirmPasswordError").innerHTML !== "" ||
        document.getElementById("email_error").innerHTML !== "" ||
        document.getElementById('email').value.trim() === "" ||
        document.getElementById('phone').value.trim() === "" ||
        document.getElementById('password').value.trim() === "" ||
        document.getElementById('confirmPassword').value.trim() === ""
    ) {
        // Prevent form submission if there are errors or required fields are empty
        return false;
    }

    // Proceed with form submission if validation passes
    alert("Form submitted successfully!");
    return true;
}
</script>
<?php 
    include("../includes/scripts.php");
?> 


