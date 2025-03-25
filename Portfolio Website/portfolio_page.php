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
if (isset($_GET['portfolioID']) && is_numeric($_GET['portfolioID'])) {
    $portfolioID = $_GET['portfolioID'];

    $portfolioInfoQuery = "SELECT * FROM portfolios WHERE portfolioid = $portfolioID ";
    $result = mysqli_query($link, $portfolioInfoQuery);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $portfolioTitle = $row['title'];
        $portfolioDescription = $row['description'];
        $portfolioAbout = $row['about'];
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
    $updateQuery = "UPDATE portfolios SET about = '$updatedAbout' WHERE portfolioid = $portfolioID";
    
    if (mysqli_query($link, $updateQuery)) {
        echo "success";
    } else {
        echo "error";
    }
    exit();
}

//use an SQL query to store all the projects stored within this portfolio
$projectsQuery = "SELECT * FROM projects WHERE portfolioid = $portfolioID";
$projectsResult = mysqli_query($link, $projectsQuery);

// Check if projects are available
$projects = [];
if (mysqli_num_rows($projectsResult) > 0) 
{
    while ($project = mysqli_fetch_assoc($projectsResult)) {$projects[] = $project;}
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

    <?php if ($userID == $portfolioCreatorID): ?>
    <!--Start of navigation bar-->
    <div class="navigation-bar">   
        <div class="logo">
            <a style="color:white; text-decoration:none;"href="dashboard.php">PortaFolio</a>
        </div>
        <div href="dashboard.php" class="navigation-links">
            <a href="create_portfolio.php">Back to Portfolio Browser</a>
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
    <?php endif; ?>


    <div class="portfolio-page-heading">
        <h1><?php echo htmlspecialchars($portfolioTitle); ?></h1>
    </div>

    <!--Start of about section-->
    <div class="section" id="about-portfolios">
        <div class="section-writing">
            <h2>About the Creator</h2>
            <p id="about-text"><?php echo nl2br(htmlspecialchars($portfolioAbout)); ?></p>
            
            <div id="about-edit" style="display: none;">
                <textarea id="about-input"><?php echo htmlspecialchars($portfolioAbout); ?></textarea>
                <br>
                <button onclick="savePortfolioAbout(<?php echo $portfolioID; ?>)" id="save-about-btn">Save</button>
            </div>
            
             <?php if ($userID == $portfolioCreatorID): ?>
                <button id="edit-about-button" class="btn" onclick="enableEdit()">Edit About Section</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!--End of about section-->

    <!--Start of projects section-->
    <div class="section" id="portfolio-projects-section">
    <div class="section-writing">
        <h2>Projects</h2>
        <ul>
            <?php foreach ($projects as $project): ?>
                <li><a href="project_page.php?projectID=<?php echo $project['projectid']; ?>"><?php echo htmlspecialchars($project['title']); ?></a></li>
            <?php endforeach; ?>
        </ul>

         <?php if ($userID == $portfolioCreatorID): ?>
            <button id="add-project-btn" class="btn" onclick="openPopup('add-project-modal')">Add Project</button>
        <?php endif; ?>
    </div>
    </div>
    <!--End of projects section-->

    <!--Start of social media/external links section-->
    <div class="section" id="about-portfolios">
        <div class="section-writing">
            <h2>Contact & Social Media</h2>
            <p>links to social media and an email/contact number for user</p>
        </div>
    </div>
    <!--End of social media/external links section-->

    <?php if ($userID == $portfolioCreatorID): ?>
    <div class="portfolio-page-footer">
        <!--<a href="edit_portfolio.php?portfolioID=<?php echo $portfolioID; ?>" class="edit-btn">Edit Portfolio</a>-->
        <a href="delete_portfolio.php?portfolioID=<?php echo $portfolioID; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete your portfolio? This action cannot be reversed.');">Delete Portfolio</a>
        <a onclick="copyCurrentURL()" class="edit-btn">Copy Shareable Link</a>
    </div>
    <?php endif; ?>


    <!-- Start of add project popup (Hidden until add ) -->
     <div id="add-project-modal" class="modal">
        <div class="modal-content">
            <span class="modal-close-btn" onclick="closePopup('add-project-modal')">&times;</span>
            <h2>Add a New Project</h2>

            <form action="add_project.php?portfolioID=<?php echo $portfolioID; ?>" method="POST" class="popup-form">
                <label for="project-title">Project Title:</label>
                <input type="text" id="project-title" name="title" required><br><br>

                <label for="project-description">Description:</label>
                <textarea id="project-description" name="description" required></textarea><br><br>
                
                <input type="hidden" name="portfolioID" value="<?php echo $portfolioID; ?>">

                <button type="submit" id="add-project-btn" name="add_project">Add Project</button>
            </form>
        </div>
    </div>
    <!-- End of Add Project Modal -->
</body>
</html>
