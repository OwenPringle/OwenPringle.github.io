<!DOCTYPE html>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Include database connection
    require('connect_db.php');

    // Initialize array for storing errors
    $errors = array();

    // Check for a username
    if (empty($_POST['username'])) {
        $errors[] = 'Enter your username.';
    } else {
        $un = mysqli_real_escape_string($link, trim($_POST['username']));
    }

    // Check for an email address
    if (empty($_POST['email'])) {
        $errors[] = 'Enter your email address.';
    } else {
        $e = mysqli_real_escape_string($link, trim($_POST['email']));
        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $e)) {
            $errors[] = 'Invalid email address.';
        }
    }

    // Check for a password and make sure both passwords entered match
    if (!empty($_POST['pass1'])) {
        if ($_POST['pass1'] != $_POST['pass2']) {
            $errors[] = 'Passwords do not match.';
        } else {
            $p = mysqli_real_escape_string($link, trim($_POST['pass1']));
        }
    } else {
        $errors[] = 'Enter your password.';
    }

    // Check email and username availability, if they exist already then error is displayed prompting a change.
    if (empty($errors)) {
        $checkQuery = "SELECT userID FROM users WHERE email='$e'";
        $checkResult = @mysqli_query($link, $checkQuery);

        $checkNameAvailability = "SELECT userID FROM user WHERE username='$un'";
        $checkNameResult = @mysqli_query($link, $checkNameAvailability);

        if ($checkResult && mysqli_num_rows($checkResult) != 0) {
            $errors[] = 'Email address already registered.';
        }

        if ($checkNameResult && mysqli_num_rows($checkNameResult) != 0) {
            $errors[] = 'Username is already taken, please try another username.';
        }
    }

    //If there are no errors then insert the new user into the database accessed through connect_db connection
    if (empty($errors)) {
        $insertQuery = "INSERT INTO user (username, password, email) 
                        VALUES ('$un', SHA2('$p',256), '$e')";
        $insertResult = @mysqli_query($link, $insertQuery);

        //if insertion into database is a success then prompt user to login to new account.
        if ($insertResult) {
            echo "<script>
                    alert('You have successfully registered your account! You will now be redirected to the login page.');
                    window.location.href = 'account_login.php';
                  </script>";
        }
        //else insertion was a failure so display error message to user.
         else {
            echo 'Registration failed: ' . mysqli_error($link);
        }

        // Close the database connection
        if (isset($link) && $link) {
            mysqli_close($link);
        }

        exit();
    }
    //if registration fails the display error message and prompt user with fixes
     else {
        $errorMessages = implode(' ', $errors);
        echo "<script>alert('Oops, looks like there is an issue: $errorMessages');</script>";

        // Close the database connection
        if (isset($link) && $link) {
            mysqli_close($link);
        }
    }
}
?>

<html>
<head>
    <meta charset="utf-8">
    <title>Account Registration</title>
    <link rel="stylesheet" href="style.css">
</head>

<!--HTML elements to create the register form that the  user interacts with-->
<body class="login-page">



<form class="box" action="register.php" method="post">
    <div class="logo-container">
        <img src="images/logo.png" alt="Website Logo">
    </div>

    <h1>Account Registration</h1>

    <div class="row">
        
      <label for="username"><b>Username</b></label>
      <input for="username" type="text" name="username" placeholder="Username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>">

      <label for="email"><b>Email</b></label>
      <input for="email" type="text" name="email" placeholder="Email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">

      <label for="password"><b>Password</b></label>
      <input for="pass1" type="password" name="pass1" placeholder="Password" value="<?php if (isset($_POST['pass1'])) echo $_POST['pass1']; ?>">
      <label for="password"><b>Repeat Password</b></label>
      <input for="pass2" type="password" name="pass2" placeholder="Confirm Password" value="<?php if (isset($_POST['pass2'])) echo $_POST['pass2']; ?>">

      
      <button class="login-button" type="submit">Register</button>
    </div>

    <div class="row">
        <p>Already have an account?</p>
        <a href="account_login.php">Login here</a>
    </div>
</form>

</body>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXz2htPH01sSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+TbbvYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqks2by6br4gND7DXjqke9RmUpD8jgGtD72P9yug3goQfGIIeyAns"
        crossorigin="anonymous"></script>
</html>
