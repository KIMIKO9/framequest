<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    echo "User ID is not set.";
    exit();
}

// Get the client's ID from the session
$client_id = $_SESSION['id'];

// Fetch saved profiles for the logged-in client
$saved_profiles_query = "SELECT u.id, u.firstname, u.lastname, u.profile_photo FROM users u INNER JOIN saved_profiles s ON u.id = s.photographer_id WHERE s.client_id = '$client_id'";
$saved_profiles_result = $conn->query($saved_profiles_query);

// Check if the query was executed successfully
if (!$saved_profiles_result) {
    echo "Error executing query: " . $conn->error;
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Photographer Profiles</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

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

<div class="content-container">
    <div class="saved-profiles">
        <div class="banner-text middle-text">
            <h2>Enjoy never losing your saved choices!</h2>
        </div>
        <div class="photographers-cards">
            <?php
            // Check if there are any saved profiles
            if ($saved_profiles_result->num_rows > 0) {
                // Output data of each row
                while ($row = $saved_profiles_result->fetch_assoc()) {
                    // Display saved profiles
                    echo '<div class="photographer-card saved-profile" data-photographer-id="' . $row['id'] . '">';
                    echo '<img src="uploads/' . $row['profile_photo'] . '" alt="' . $row['firstname'] . ' ' . $row['lastname'] . '">';
                    echo '<h3>' . $row['firstname'] . ' ' . $row['lastname'] . '</h3>';
                    echo '<a href="photographer_profile.php?id=' . $row['id'] . '" class="profile-link">View Profile</a>';
                    echo '<div class="trash-icon-container">';
                    echo '<img src="uploads/icons/bin.png" alt="Delete" class="trash-icon">';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No saved profiles yet.</p>';
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


<script>
document.querySelectorAll('.trash-icon').forEach(function(icon) {
    icon.addEventListener('click', function() {
        var card = this.closest('.photographer-card');
        var photographerId = card.getAttribute('data-photographer-id');

        var formData = new FormData();
        formData.append('photographer_id', photographerId);

        fetch('delete_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data === 'Profile deleted successfully') {
                card.remove();
            } else {
                console.error('Error deleting profile:', data);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
</script>


</body>
</html>
