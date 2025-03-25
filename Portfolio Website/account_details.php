<?php
session_start();
require('connect_db.php');

// Redirect to login if user is not logged in.
if (!isset($_SESSION['username'])) {
    header("Location: account_login.php");
    exit();
}

$username = $_SESSION['username'];

// Fetch user information
$query = "SELECT email FROM user WHERE username='$username'";
$result = mysqli_query($link, $query);
$user = mysqli_fetch_assoc($result);
$email = $user['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];

    if (isset($_POST['update'])) {
        // Update email
        if (!empty($_POST['email'])) {
            $new_email = mysqli_real_escape_string($link, trim($_POST['email']));
            if (preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $new_email)) {
                $update_email_query = "UPDATE user SET email='$new_email' WHERE username='$username'";
                mysqli_query($link, $update_email_query);
                $email = $new_email;
            } else {
                $errors[] = 'Invalid email format.';
            }
        }

        // Update password
        if (!empty($_POST['password1']) && !empty($_POST['password2'])) {
            if ($_POST['password1'] === $_POST['password2']) {
                $new_password = mysqli_real_escape_string($link, trim($_POST['password1']));
                $update_password_query = "UPDATE user SET password=SHA2('$new_password', 256) WHERE username='$username'";
                mysqli_query($link, $update_password_query);
            } else {
                $errors[] = 'Passwords do not match.';
            }
        }
    }

    if (isset($_POST['delete'])) {
        // Delete account
        $delete_query = "DELETE FROM user WHERE username='$username'";
        mysqli_query($link, $delete_query);
        session_destroy();
        header("Location: account_login.php");
        exit();
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Account Details</h1>
        <?php if (!empty($errors)) { ?>
            <div class="error-messages">
                <?php foreach ($errors as $error) { echo "<p>$error</p>"; } ?>
            </div>
        <?php } ?>
        <form action="account_details.php" method="post" class="box">
            <div class="row">
                <label for="username">Username:</label>
                <input type="text" id="username" value="<?php echo htmlspecialchars($username); ?>" disabled>
            </div>

            <div class="row">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
            </div>

            <div class="row">
                <label for="password1">New Password:</label>
                <input type="password" name="password1" id="password1">
            </div>

            <div class="row">
                <label for="password2">Confirm New Password:</label>
                <input type="password" name="password2" id="password2">
            </div>

            <div class="row">
                <button type="submit" name="update" class="btn-primary">Update</button>
                <button type="submit" name="delete" class="btn-danger" onclick="return confirm('Are you sure you want to delete your account? This action cannot be reversed.');">Delete Account</button>
            </div>
        </form>
    </div>
</body>
</html>
