<?php
// Start a session if none exists
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection file
include 'db.php';
include 'loading.php';

// Retrieve the username from the session if available
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Prepare and execute query to fetch user role from the database
$user_role_query = "SELECT role FROM users WHERE username = ?";
$user_role_stmt = $conn->prepare($user_role_query);
$user_role_stmt->bind_param("s", $username);
$user_role_stmt->execute();
$user_role_result = $user_role_stmt->get_result();

// Handle the query result
if ($user_role_result->num_rows === 1) {
    $user_role_row = $user_role_result->fetch_assoc();
    $user_role = $user_role_row['role'];
    $_SESSION['role'] = $user_role; // Save the user role in the session

    // Fetch and save user ID in the session
    $user_id_query = "SELECT id FROM users WHERE username = ?";
    $user_id_stmt = $conn->prepare($user_id_query);
    $user_id_stmt->bind_param("s", $username);
    $user_id_stmt->execute();
    $user_id_result = $user_id_stmt->get_result();

    if ($user_id_result->num_rows === 1) {
        $user_id_row = $user_id_result->fetch_assoc();
        $_SESSION['id'] = $user_id_row['id']; // Save the user ID in the session
    }
}
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

<div class="custom-banner">
    <div class="bold-text">Discover the extraordinary. Capture the unforgettable. All in one place! </div>
</div>


<div class="homecontent">
    <div class="box">
        <img src="uploads/vehicle.jpg" alt="Image 1">
        <div class="text">Vehicle</div>
    </div>
    <div class="box">
        <img src="uploads/wedding.jpg" alt="Image 2">
        <div class="text">Wedding</div>
    </div>
    <div class="box">
        <img src="uploads/animals.jpg" alt="Image 3">
        <div class="text">Wildlife</div>
    </div>
</div>

<div class="see-more-btn">
    <a href="gallery.php">See More</a>
</div>

<div class="about-container">
    <div class="about-image">
        <img src="uploads/bannerwelcome5.png" alt="About Image">
    </div>
    <div class="about-text">
        <h2>What is Frame Quest?</h2>
        <p>
        Place For Creative Minds & Seekers
        </p>
        <p>
        Frame Quest connects users with skilled artists, making it easier to find a photographer for any need. Whether it's the emotion of a wedding day or the desire to capture family, pets or an important day.
        </p>
        <p>
        The photographers at Frame Quest specialize in turning those moments into timeless, visually engaging memories.
        </p>
    </div>
</div>


    <div class="fullwidth">
        <div class="container">
            <div class="text-section">
            Capturing moments, painting stories – photographers can turn life into art.
            </div>

            <div class="image-section">
                <img src="uploads/capture.jpg" alt="Image">
            </div>

        </div>
    </div>

    


    <div class="quality">
    <div class="quality-item">
        <img src="uploads/icons/lock.png" alt="Secure Icon">
        <p>Secure Platform</p>
    </div>
    <div class="quality-item">
        <img src="uploads/icons/high-quality.png" alt="Quality Icon">
        <p>Quality Service</p>
    </div>
    <div class="quality-item">
        <img src="uploads/icons/growth.png" alt="Growth Icon">
        <p>Growth Opportunity</p>
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
