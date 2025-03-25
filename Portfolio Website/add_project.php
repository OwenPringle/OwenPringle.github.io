<?php
session_start();
require('connect_db.php');

#redirect to login if user is not logged in.
if (!isset($_SESSION['username'])) {
    header("Location: account_login.php");
    exit();
}

//store session variables
$userID = $_SESSION['userID'];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_project'])) {
    $portfolioID = $_POST['portfolioID'];
    $projectTitle = mysqli_real_escape_string($link, $_POST['title']);
    $projectDescription = mysqli_real_escape_string($link, $_POST['description']);
    
    //insert the new project title and description into the projects table
    $insertProjectQuery = "INSERT INTO projects (portfolioid, title, description) VALUES ('$portfolioID', '$projectTitle', '$projectDescription')";

    //run the query
    if (mysqli_query($link, $insertProjectQuery)) {
        //if it was successful in creation then send the user back to the portfolio page to view their list of projects
        header("Location: portfolio_page.php?portfolioID=$portfolioID");
        exit();
    } else {
        //if it fails then show error message
        echo "There was an error adding the project: " . mysqli_error($link);
    }
}
?>
