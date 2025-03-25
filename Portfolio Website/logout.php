<?php
session_start();
session_unset();
session_destroy();

//users session is destroyed on logout and they are redirected to the login page. (user is unable to get back into website without logging back in.)
header("Location: account_login.php");
exit();
?>
