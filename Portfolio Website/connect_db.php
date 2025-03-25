<?php #HELPER FUNCTION TO CONNECT TO DATABASE ASSOCIATED WITH WEBSITE

#connect with link to database hosted on university servers
$link = mysqli_connect('132.145.18.222', 'omp2000', 'wnd4VKSANY3', 'omp2000');
#use this to connect to locally hosted database
#$link = mysqli_connect('localhost', 'root', '', 'portfolio_website');

if (!$link) 
{ 
    #IF THE CONNECTION DOES NOT WORK THE DISPLAY ERROR MESSAGE
    die('Could not connect to MySQL: ' . mysqli_connect_error()); 
} 
?>
