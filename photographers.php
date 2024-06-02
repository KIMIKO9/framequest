<?php
// Start the session
session_start();
// Include database connection file
include 'db.php';
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

<div class="height">

<div class="content-container">
    <div class="sort">
        <div style="float: right;">
            <form method="GET" id="location-filter-form">
                <div class="select-wrapper">
                    <select id="location" name="location" onchange="document.getElementById('location-filter-form').submit();">
                        <option value="">Select Location</option>
                        <?php
                        // Fetch locations from the locations table
                        $locations_query = "SELECT * FROM locations";
                        $locations_result = $conn->query($locations_query);
                        if ($locations_result->num_rows > 0) {
                            while ($row = $locations_result->fetch_assoc()) {
                                $selected = (isset($_GET['location']) && $_GET['location'] == $row['location_name']) ? 'selected' : '';
                                echo '<option value="' . $row['location_name'] . '" ' . $selected . '>' . $row['location_name'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No locations found</option>';
                        }
                        ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="photographers-container">
    <div class="photographers-cards">
        <?php
        $sql = "SELECT * FROM users WHERE role = 'photographer' AND profile_photo IS NOT NULL AND userbiography IS NOT NULL";

        if (isset($_GET['location']) && !empty($_GET['location'])) {
            $location = $_GET['location'];
            $sql .= " AND userlocation = ?";
        }

        // Prepare and execute query
        if (isset($location)) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $location);
        } else {
            $stmt = $conn->prepare($sql);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="photographer-card">';
                $src = 'uploads/' . $row['profile_photo']; 
                echo '<img src="'.$src.'" alt="' . $row['firstname'] . ' ' . $row['lastname'] . '">';
                echo '<h3>' . $row['firstname'] . ' ' . $row['lastname'] . '</h3>';
                echo '<a href="photographer_profile.php?id=' . $row['id'] . '" class="profile-link">View Profile</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No photographers found.</p>';
        }
        ?>
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
