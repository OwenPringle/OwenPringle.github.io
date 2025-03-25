<!DOCTYPE html>

<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    # Open database connection.
    require('connect_db.php');
    # Get connection, load, and validate functions.
    require('login_action.php');

    # Check login.
    list($check, $data) = validate($link, $_POST['email'], $_POST['pass']);

    if ($check) {
        # On success, set session data and redirect to the dashboard.
        session_start();
        $_SESSION['userID'] = $data['userID'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['email'] = $data['email'];
        
        # Redirect to user dashboard.
        header("Location: dashboard.php");
        exit();
    } else {
        # Display error messages.
        $errors = $data;
    }

    # Close database connection.
    mysqli_close($link);
}

# Display any error messages if present.
if (isset($errors) && !empty($errors)) {
    $error_message = implode(' ', $errors);
    echo "<script>alert('Login failed: $error_message');</script>";
}
?>

<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Login Page</title>
</head>
<body class="login-page">
    <!--Login is taken through a form box for taking email and password-->
    <form class="box" action="account_login.php" method="post">
        <!--Login section-->
        <div class="logo-container">
        <img src="images/logo.png" alt="Website Logo">
    </div>
        <h1>Account Login</h1>
        <div class="row">
            <label for="email"><b>Email</b></label>
            <input id="email" type="text" name="email" placeholder="Enter Email" required>

            <label for="pass"><b>Password</b></label>
            <input id="pass" type="password" name="pass" placeholder="Enter Password" required>

            <button class="login-button" type="submit">Login</button>
        </div>
        <!--End of Login section-->


        <!--Register and Password reset section-->
        <div class="row">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
        <!--End of Register and Password reset section-->
    </form>
</body>
</html>
