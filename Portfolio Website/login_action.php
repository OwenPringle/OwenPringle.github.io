<?php # LOGIN HELPER FUNCTIONS. (COMPLETED FUNCTIONALITY)

# Function to load specified or default URL.
function load( $page = 'account_login.php' )
{
    # Begin URL with protocol, domain, and current directory.
    $url = 'http://' . $_SERVER[ 'HTTP_HOST' ] . dirname( $_SERVER[ 'PHP_SELF' ] ) ;
    # Remove trailing slashes then append page name to URL.
    $url = rtrim( $url, '/\\' ) ;
    $url .= '/' . $page ;
    # Execute redirect then quit. 
    header( "Location: $url" ) ; 
    exit() ;
}


# Function to check email address and password. 
function validate( $link, $email = '', $pwd = '')
{
    # Initialize array for storing errors when logging in.
    $errors = array() ; 
    
    # Check if email is entered
    if ( empty( $email ) ) 
    { $errors[] = 'Enter your email address.' ; } 
    else { $e = mysqli_real_escape_string( $link, trim( $email ) ) ; } #if email is not empty then store it in $e
    
    # Check password field is entered.
    if ( empty( $pwd ) ) 
    { $errors[] = 'Enter your password.' ; } 
    else { $p = mysqli_real_escape_string( $link, trim( $pwd ) ) ; } #if password is not empty then store it in $p

    //if there are no errors then check to see if the email and password exist inside the database
    if (empty($errors)) {
    $q = "SELECT userID, username FROM user WHERE email='$e' AND password=SHA2('$p',256)";
    $result = mysqli_query($link, $q);

    #if the details are not valid then error message will be displayed
    if (!$result) 
    {
        $errors[] = 'Error: ' . mysqli_error($link);
    } 
    else 
    {
        #If login details are valid
        if (@mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            return array(true, $row);
        } 
        else 
        {
            $errors[] = 'Email address or password not found. Please try again or register an account.';
        }
    }
}

#if login fails then return array of error messages to the user to assist on what to fix.
return array( false, $errors ) ; 
}
	




		
