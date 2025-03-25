<?php
session_start();
require('connect_db.php');

//check to make sure user is logged into an account
if (!isset($_SESSION['username'])) {
    header("Location: account_login.php");
    exit();
}

//if the project id has been passed through store it using the GET function
if (isset($_GET['projectID']) && is_numeric($_GET['projectID'])) {
    //store the project ID retrieved through GET
    $projectID = $_GET['projectID'];
    $projectInfoQuery = "SELECT * FROM projects WHERE projectID = $projectID";
    $projectInfoResult = mysqli_query($link, $projectInfoQuery);

    if (mysqli_num_rows($projectInfoResult) > 0) {
    //store the portfolio ID of the portfolio that the project is stored in
    $projectInfo = mysqli_fetch_assoc($projectInfoResult);
    $portfolioID = $projectInfo['portfolioid'];


    //delete all the images and videos inside the project and delete the project itself
    mysqli_query($link, "DELETE FROM user_images WHERE projectID = $projectID" );
    mysqli_query($link, "DELETE FROM user_videos WHERE projectID = $projectID");
    if (mysqli_query($link, "DELETE FROM projects WHERE projectID = $projectID")) {
        //if deletion was a success then redirect to the portfolio page
        echo "<script>
                alert('The project and everything stored inside has been successfully deleted.');
                window.location.href = 'portfolio_page.php?portfolioID=$portfolioID';
              </script>";
    } 
    else {
        //else if deletion was not successful then redirect to portfolio page and display error message
        echo "<script>
                alert('There was an unexpected error deleting the project.');
                window.location.href = 'portfolio_page.php?portfolioID=$portfolioID';
              </script>";
    }
}
}

?>
