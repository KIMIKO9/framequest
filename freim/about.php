<?php
// Start the session
session_start();
// Include database connection file
include 'loading.php';

?>

<!DOCTYPE html>
<html lang="en">

    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frame Quest</title>
    <link rel="stylesheet" href="styles.css">
    </head>


<body class="globalbody">


<div class="banner" id="banner">
    <div class="navigation">
        <div class="logo">Frame Quest</div>
        <div class="menu">
            <a href="index.php">Home</a>
            <a href="gallery.php">Gallery</a>
            <a href="photographers.php">Photographers</a>
            <a href="about.php">About</a>
        </div>
        <div class="user-info">
            <?php
            // Check if user is logged in
            if (isset($_SESSION['username'])) {
                // Display user icon with link to dashboard
                echo '<a href="dashboard.php" class="user-icon"><img src="uploads/icons/settings.png" alt="User Icon" width="40"></a>';

                // Display save icon for clients
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'client') {
                    echo '<a href="saved_profiles.php" class="user-icon"><img src="uploads/icons/saved.png" alt="Save Icon" width="40"></a>';
                }

                // Display upload icon if the user is a photographer
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'photographer') {
                    echo '<a href="upload_picture.php" class="user-icon"><img src="uploads/icons/upload.png" alt="Upload Icon" width="40"></a>';
                    
                    // Construct the link for the photographer profile
                    $photographer_id = $_SESSION['id'];
                    $photographer_profile_link = 'photographer_profile.php?id=' . urlencode($photographer_id);
                    echo '<a href="' . $photographer_profile_link . '" class="user-icon"><img src="uploads/icons/profile.png" alt="Photographer Profile Icon" width="40"></a>';
                }

                // Display logout button
                echo '<a href="logout.php" class="logout-btn">Logout</a>';
            } else {
                // Display login and register links if user is not logged in
                echo '<a href="login.php" class="submitlogin-btn">Login</a>';
                echo '<a href="register.php" class="submit-btn">Register</a>';
            }
            ?>
        </div>
    </div>
</div>


    <div class="aboutfq-container">
        <div class="aboutfq-text">
            <h2>About Frame Quest</h2>
            
            <p>Frame Quest is a platform for discovering talented photographers and showcasing your own work. Here are a few reasons why photographers and users love Frame Quest:</p>

        </div>
    </div>
</div>

<div class="aboutfqrow">
    <div class="aboutfqrowbox">
        <img src="uploads/moc1.jpg" alt="Image 1">
    </div>
    <div class="aboutfqrowbox">
        <img src="uploads/moc2.jpg" alt="Image 2">
    </div>
    <div class="aboutfqrowbox">
        <img src="uploads/moc3.jpg" alt="Image 3">
    </div>
</div>


    <div class="aboutfq-container">
        <div class="aboutfq-text">
        
            <p><strong>For Photographers</strong></p>
            <ul style="list-style-type: none; padding-left: 0;">
            <p>Gain exposure by aquiring a wider audience and showcasing your skills to potential clients.</li>
            </ul>
        </div>
    </div>
</div>


<div class="aboutfq-container">
        <div class="aboutfq-text">
        
            <p><strong>For Users</strong></p>
            <ul style="list-style-type: none; padding-left: 0;">
            <p>Find the perfect photographer by browsing  through a diverse selection of photographers to find the perfect match for your project.</li>
            <p>Choose with confidence by reading reviews and viewing portfolios.</li>
            </ul>
        </div>
    </div>
</div>




    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Frame Quest</h3>
            </div>
            <div class="footer-section">
                <h3>Navigation</h3>
                <ul>
                    <li><a href="register.php">Register</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>framequest@gmail.com</p>
            </div>
        </div>
        <hr class="footer-divider">
        <div class="footer-bottom">
            <p>All rights reserved &copy; 2024 Frame Quest</p>
        </div>
    </footer>

</body>

</html>
