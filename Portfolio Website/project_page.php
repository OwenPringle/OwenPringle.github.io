<?php
session_start();
require('connect_db.php');

// Redirect to login if user is not logged in.
if (!isset($_SESSION['username'])) {
    header("Location: account_login.php");
    exit();
}

//store username and user ID session variables
$username = $_SESSION['username'];
$userID = $_SESSION['userID'];

//get the portfolio ID and use it to retrieve information about the portfolio from the database
if (isset($_GET['projectID']) && is_numeric($_GET['projectID'])) {
    $projectID = $_GET['projectID'];

    $projectInfoQuery = "SELECT * FROM projects WHERE projectID = $projectID ";
    $result = mysqli_query($link, $projectInfoQuery);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $projectTitle = $row['title'];
        $projectDescription = $row['description'];
        $projectAbout = $row['about'];
        $ProjectPortfolioID = $row['portfolioid'];

    }
    else {
        header("Location: dashboard.php");
        exit();
    }

    //get the portfolio creator ID for hiding certain elements in a view only mode.
    $portfolioInfoQuery = "SELECT * FROM portfolios WHERE portfolioid = $ProjectPortfolioID ";
    $result = mysqli_query($link, $portfolioInfoQuery);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $portfolioCreatorID = $row['userid'];
    }
    else {
        header("Location: dashboard.php");
        exit();
    }
}

//Update about page function
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_about'])) {
    $updatedAbout = mysqli_real_escape_string($link, $_POST['about']);
    $updateQuery = "UPDATE projects SET about = '$updatedAbout' WHERE projectid = $projectID";
    
    if (mysqli_query($link, $updateQuery)) {
        echo "success";
    } else {
        echo "error";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Your Portfolios</title>
        <link rel="stylesheet" href="style.css">
        <script type="text/javascript" src="dropdown.js"></script>
        <script type="text/javascript" src="edit_portfolio.js"></script>
    </head>

    <body>

    <!--Start of navigation bar-->
    <div class="navigation-bar">   
        <div class="logo">
            <a style="color:white; text-decoration:none;"href="dashboard.php">PortaFolio</a>
        </div>
        <div href="dashboard.php" class="navigation-links">
        <a href="portfolio_page.php?portfolioID=<?php echo $ProjectPortfolioID; ?>">Back to Portfolio Page</a>
        </div>

        <div class="user-menu">
            <button onclick="toggleDropdown()">
                <i class="fas fa-user"></i> <?php echo htmlspecialchars($username); ?>
            </button>
            <div class="dropdown">
                <a href="account_details.php">Account Details</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
    <!--End of navigation bar-->


    <div class="portfolio-page-heading">
        <h1><?php echo htmlspecialchars($projectTitle); ?></h1>
    </div>

    <!--Start of about section-->
    <div class="section" id="about-portfolios">
    <div class="section-writing">
        <h2>About the Project</h2>
        <p id="about-text"><?php echo nl2br(htmlspecialchars($projectAbout)); ?></p>
            
        <div id="about-edit" style="display: none;">
            <textarea id="about-input"><?php echo htmlspecialchars($projectAbout); ?></textarea>
            <br>
            <button onclick="saveProjectAbout(<?php echo $projectID; ?>)" id="save-about-btn">Save</button>
            </div>

            <?php if ($userID == $portfolioCreatorID): ?>
            <button id="edit-about-button" class="btn" onclick="enableEdit()">Edit About Section</button>
            <?php endif; ?>
        </div>
    </div>
    </div>
    <!--End of about section-->

    <!--Start of photos section-->
    <div class="section" id="about-portfolios">
        <div class="section-writing">
            <!--Display User Images-->
            <div class="media-section">
                <h2>Image Gallery</h2>
                <div class="carousel-container">
                    <button class="prev-btn" onclick="scrollCarousel('image-gallery', -1)">&#10094;</button>
                    <div class="carousel" id="image-gallery">
                        <?php
                        $imageQuery = "SELECT * FROM user_images WHERE projectID = $projectID";
                        $imageResult = mysqli_query($link, $imageQuery);
                        while ($imageRow = mysqli_fetch_assoc($imageResult)) {
                            echo "<div class='media-item'>";
                            echo "<img src='uploads/{$imageRow['image_url']}' alt='" . htmlspecialchars($imageRow['title']) . "'>";
                            echo "<div class='overlay'><p>" . htmlspecialchars($imageRow['description']) . "</p></div>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                    <button class="next-btn" onclick="scrollCarousel('image-gallery', 1)">&#10095;</button>
                </div>
            </div>
            <!--End of User Images Display-->
        </div>
    </div>
    <!--End of photos section-->
    
        
    <!--Start of videos section-->
    <div class="section" id="about-portfolios">
        <div class="section-writing">
            <!--Display User Videos-->
            <div class="media-section">
                <h2>Videos</h2>
                <div class="carousel-container">
                    <button class="prev-btn" onclick="scrollCarousel('video-gallery', -1)">&#10094;</button>
                    <div class="carousel" id="video-gallery">
                        <?php
                        $videoQuery = "SELECT * FROM user_videos WHERE projectID = $projectID";
                        $videoResult = mysqli_query($link, $videoQuery);
                        while ($videoRow = mysqli_fetch_assoc($videoResult)) {
                            echo "<div class='media-item'>";
                            echo "<video controls>";
                            echo "<source src='uploads/{$videoRow['video_url']}' type='video/mp4'>";
                            echo "Your browser does not support the video tag.";
                            echo "</video>";
                            echo "<div class='overlay'><p>" . htmlspecialchars($videoRow['description']) . "</p></div>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                    <button class="next-btn" onclick="scrollCarousel('video-gallery', 1)">&#10095;</button>
                </div>
            </div>
            <!--End of User Videos Display-->
        </div>
    </div>
    <!--End of videos section-->

    <?php if ($userID == $portfolioCreatorID): ?>
    <div class="portfolio-page-footer">
    <button class="edit-btn" onclick="openPopup('upload-media-modal')">Upload Media</button>
    <button class="delete-btn"><a href="delete_project.php?projectID=<?php echo $projectID; ?>" onclick="return confirm('Are you sure you want to delete this project and everything inside? This action cannot be reversed.');">Delete Project</a></button>
    </div>
    <?php endif; ?>

    <!-- Start of Upload Media Modal -->
     <div id="upload-media-modal" class="modal">
        <div class="modal-content">
            <span class="modal-close-btn" onclick="closePopup('upload-media-modal')">&times;</span>
            <h2>Upload Images or Videos</h2>
            <form action="upload_media.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="projectID" value="<?php echo $projectID; ?>">
                <label for="mediaType">Select Media Type:</label>
                <select name="mediaType" required>
                    <option value="image">Image</option>
                    <option value="video">Video</option>
                </select>
                <br><br>
                <label for="title">Media Title:</label>
                <input type="text" name="title" required placeholder="Media Title">
                <br><br>

                <label for="description">Description:</label>
                <textarea name="description" rows="3"></textarea>
                <br><br>

                <label for="file">Upload File:</label>
                <input type="file" name="file" accept="image/*,video/*" required>
                <br><br>
                <button type="submit" name="upload">Upload</button>
            </form>
        </div>
    </div>
    <!-- End of Upload Media Modal -->
</body>
</html>
