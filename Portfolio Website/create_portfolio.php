<?php
session_start();
require('connect_db.php');

#redirect to login if user is not logged in.
if (!isset($_SESSION['username'])) {
    header("Location: account_login.php");
    exit();
}

//store session variables
$username = $_SESSION['username'];
$userID = $_SESSION['userID'];

# Display all of the current users portfolios using a query to the portfolios table
$portfolioList = "SELECT portfolios.portfolioid, portfolios.title, portfolios.description 
FROM portfolios 
WHERE portfolios.userid = $userID ";
              
$result = mysqli_query( $link, $portfolioList ) ;
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
    <div class="navigation-bar">   
        <div class="logo">
            <a style="color:white; text-decoration:none;"href="dashboard.php">PortaFolio</a>
        </div>
        <div class="navigation-links">
            <a href="dashboard.php">Back to Home</a>
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

    <h1>Your Portfolios</h1>
    <?php
    echo '<div class="row">';
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="portfolio_list_grid">';
        while ($row = mysqli_fetch_assoc($result)) {
            $portfolioID = $row['portfolioid'];
            $portfolioTitle = $row['title'];
            echo '<div class="portfolio_list_item">';
            echo '<a class="portfolio_Link" href="portfolio_page.php?portfolioID=' . $portfolioID . '">' . htmlspecialchars($portfolioTitle) . '</a>';
            echo '</div>';}
            echo '</div>';
        }
    else 
    {
        echo '<p>You have not created a portfolio yet. Once you create one, it will appear here.</p>';
    }
    ?>
    <div id="create-portfolio-modal" class="modal">
        <div class="modal-content">
            <span class="modal-close-btn" onclick="closePopup('create-portfolio-modal')">&times;</span>
            <h2>Create a New Portfolio</h2>
            <form class="popup-form" action="create_portfolio_action.php" method="POST">
                <label for="portfolioTitle">Portfolio Title:</label>
                <input type="text" name="portfolioTitle" required>

                <label for="portfolioDescription">Description:</label>
                <textarea name="portfolioDescription" rows="3" required></textarea>

                <button type="submit" class="btn">Create Portfolio</button>
            </form>
        </div>
    </div>

    <div style="padding:10px;">
    <h2>Create a New Portfolio</h2>
    <button id="add-project-btn" class="btn" onclick="openPopup('create-portfolio-modal')">Create a New Portfolio</button>
</div>

    
</div>
</div>
</body>
</html>