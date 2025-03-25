<?php
session_start();
require('connect_db.php');

if (isset($_GET['portfolioID']) && is_numeric($_GET['portfolioID'])) {
    $portfolioID = $_GET['portfolioID'];

    //when deleting portfolio make sure to delete all projects, images and videos related to that portfolio aswell.
    mysqli_query($link, "DELETE FROM user_images WHERE projectID IN (SELECT projectID FROM projects WHERE portfolioID = $portfolioID)");
    mysqli_query($link, "DELETE FROM user_videos WHERE projectID IN (SELECT projectID FROM projects WHERE portfolioID = $portfolioID)");
    mysqli_query($link, "DELETE FROM projects WHERE portfolioID = $portfolioID");

    //delete the portfolio itself once all projects videos and images have been remeoved
    if (mysqli_query($link, "DELETE FROM portfolios WHERE portfolioID = $portfolioID")) {
        echo "<script>
        alert('portfolio and all projects relating to it have been deleted.');
        window.location.href = 'create_portfolio.php';
        </script>";			
    } else {
        echo "<script>
        alert('Error deleting portfolio.');
        window.location.href = 'portfolio_page.php?portfolioID=$portfolioID';
        </script>";		
    }
} else {
    echo "<script>
    alert('Portfolio ID is missing or invalid.');
    window.location.href = 'create_portfolio.php';
    </script>";
}
?>
