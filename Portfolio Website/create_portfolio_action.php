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

//check if the form in create_portfolio has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $portfolioTitle = mysqli_real_escape_string($link, $_POST['portfolioTitle']);
    $portfolioDescription = mysqli_real_escape_string($link, $_POST['portfolioDescription']);

    //insert the new portfolio title and description into the portfolios table
    $insertPortfolioQuery = "INSERT INTO portfolios (userid, title, description) VALUES ('$userID', '$portfolioTitle', '$portfolioDescription')";

    //run the query
    if (mysqli_query($link, $insertPortfolioQuery)) {
        //if it was successful in creation then send the user back to the create_portfolio page to view their list of portfolios
        header("Location: create_portfolio.php");
        exit();
    } else {
        //if it fails then show error message
        echo "Error creating the portfolio: " . mysqli_error($link);
    }
}
?>
