<?php
// Start the session
session_start();
// Include database connection file
include 'db.php';
include 'loading.php';

// Check if photographer ID is provided in the URL
if(isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $photographer_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Query to retrieve photographer information based on ID
    $sql = "SELECT * FROM users WHERE id = '$photographer_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch photographer information
        $row = $result->fetch_assoc();
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $userlocation = $row['userlocation'];
        $userbiography = $row['userbiography'];
        $email = $row['email'];
        $phone = $row['phone'];
        $instagram = $row['instagram'];
        $facebook = $row['facebook'];
        $tiktok = $row['tiktok'];
        $profile_photo = 'uploads/' . $row['profile_photo'];

        // Fetch user pictures with user details
        $user_pictures_query = "SELECT p.picture_path, p.picture_name, u.id, u.firstname, u.lastname, u.profile_photo FROM pictures p JOIN users u ON p.username = u.username WHERE u.id = '$photographer_id'";
        $user_pictures_result = $conn->query($user_pictures_query);

        $conn->close();
    } else {
        // Photographer ID is not found
        header("Location: error.php");
        exit();
    }
} else {
    // Photographer ID is not provided
    header("Location: error.php");
    exit();
}

function formatURL($url)
{
    // Check if the URL already starts with "http://" or "https://"
    if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
        return $url; // If it does, return the URL as is
    } else {
        return 'http://' . $url; // Otherwise, prepend "http://" to the URL
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $firstname . ' ' . $lastname; ?>'s Profile</title>
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

<div class="contact-container">
    <div class="contact-buttons">
        <a href="tel:<?php echo $phone; ?>" class="contact-btn">Call</a>
        <a href="mailto:<?php echo $email; ?>" class="contact-btn">Email</a>
    </div>
</div>

<div class="profile-container">
    <div class="profile-header">
        <div class="profile-photo">
            <img src="<?php echo $profile_photo; ?>" alt="<?php echo $firstname . ' ' . $lastname; ?>">
        </div>
        <h1><?php echo $firstname . ' ' . $lastname; ?></h1>
    </div>
    
    <div class="profile-details">




    <p class="biography"><strong></strong> <?php echo $userbiography; ?></p>

    <div class="location-container">
            <img src="uploads/icons/location.png" alt="Location Icon" class="location-icon">
        </div>
        <p><?php echo $userlocation; ?></p><br>


        <?php if (isset($_SESSION['username']) && $_SESSION['role'] === 'client'): ?>
            <form action="save_profile.php" method="POST">
                <input type="hidden" name="photographer_id" value="<?php echo $photographer_id; ?>">
                <button type="submit" class="save-profile-btn">Save Profile</button>
            </form>
        <?php endif; ?>


        <div class="social-media" style="margin-top: 40px;">
            <ul>
                <?php if (!empty($instagram)): ?>
                    <li><a href="<?php echo formatURL($instagram); ?>" target="_blank">Instagram</a></li>
                <?php endif; ?>
                <?php if (!empty($facebook)): ?>
                    <li><a href="<?php echo formatURL($facebook); ?>" target="_blank">Facebook</a></li>
                <?php endif; ?>
                <?php if (!empty($tiktok)): ?>
                    <li><a href="<?php echo formatURL($tiktok); ?>" target="_blank">TikTok</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="review-button-container">
    <a href="reviews.php?id=<?php echo $photographer_id; ?>" class="view-reviews-btn">See Reviews</a>
        </div>
    </div>
</div>

<div class="masonry-container">
    <div class="masonry">
        <?php
        if ($user_pictures_result->num_rows > 0) {
            while ($row = $user_pictures_result->fetch_assoc()) {
                echo '<div class="masonry-item">';
                echo '<img src="' . $row['picture_path'] . '" alt="' . $row['picture_name'] . '">';
                echo '<div class="user-details">';
                
                // Make the profile name clickable with a link to the photographer's profile page
                echo '<a href="photographer_profile.php?id=' . $row['id'] . '" class="user-name">' . $row['firstname'] . ' ' . $row['lastname'] . '</a>';
                
                echo '</div>'; // Close user-details
                echo '</div>'; // Close masonry-item
            }
        } else {
            echo 'No user pictures found.';
        }
        ?>
    </div>
</div>


<!-- Scroll to top button -->
<div class="scroll-to-top" id="scrollToTopBtn" onclick="scrollToTop()">
  <img src="uploads/icons/top.png" alt="Icon Image">
</div>


<script>
  // Show/hide the scroll-to-top button based on scrolling
  window.onscroll = function() {scrollFunction()};

  function scrollFunction() {
    var scrollToTopBtn = document.getElementById("scrollToTopBtn");
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      scrollToTopBtn.style.display = "flex";
    } else {
      scrollToTopBtn.style.display = "none";
    }
  }

  // Scroll to top function with smooth animation
  function scrollToTop() {
    var currentPosition = window.scrollY || document.documentElement.scrollTop;
    if (currentPosition > 0) {
      window.requestAnimationFrame(scrollToTop);
      window.scrollTo(0, currentPosition - currentPosition / 8);
    }
  }
</script>

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
