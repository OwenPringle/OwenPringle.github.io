<?php
# Access session.
session_start();
# Connect to the database.
require('connect_db.php');

//redirect to login if user is not logged in.
if (!isset($_SESSION['username'])) {
    header("Location: account_login.php");
    exit();
}

//check to see if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload'])) {
    //recieve and store all relevant information from the form submitted by the user
    $projectID = mysqli_real_escape_string($link, $_POST['projectID']);
    $title = mysqli_real_escape_string($link, $_POST['title']);
    $description = mysqli_real_escape_string($link, $_POST['description']);
    $mediaType = $_POST['mediaType'];

    //setting the path to upload directory
    $uploadDirectory = "uploads/";

    $fileName = basename($_FILES["file"]["name"]);
    $targetFilePath = $uploadDirectory . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    //Setting list of allowed video and image types for project content
    $allowedImageTypes = ["jpg", "jpeg", "png", "gif"];
    $allowedVideoTypes = ["mp4", "mov", "avi", "wmv"];

    //if an image is uploaded by user and the image file extension is of the correct type then
    if ($mediaType == "image" && in_array($fileType, $allowedImageTypes)) 
    {
        //store the relevant table and column names in table and column variables for images
        $table = "user_images";
        $column = "image_url";
    } 
    //else if a video is uploaded by the user and the video is of the correct allowed extenstion then
    elseif ($mediaType == "video" && in_array($fileType, $allowedVideoTypes)) 
    {
        //store the relevant table and column names in table and column variables for videos
        $table = "user_videos";
        $column = "video_url";
    } 
    //else the file type is invalid so alert the user
    else 
    {
        die("Invalid file type.");
    }

    //uploading the file to the database and moving it to the uploads folder to be stored for use
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
        //insert the data into the correct table depending on whether it was a video or image being uploaded by the user
        $insertQuery = "INSERT INTO $table (projectID, title, description, " . ($mediaType == "image" ? "image_url" : "video_url") . ") 
        VALUES ('$projectID', '$title', '$description', '$fileName')";
        //run the query
        $insertResult = @mysqli_query($link, $insertQuery);

        //if successful the redirect to project page else display error to user
        if ($insertResult) 
        {
            header("Location: project_page.php?projectID=$projectID");
            exit();
        } 
        else {
            die("Database error: " . mysqli_error($link));
        }
    } 
    else
    {
        die("File upload failed.");
    }
}

?>
