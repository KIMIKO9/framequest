<?php
// Start the session
session_start();

// Include database connection file
include 'db.php';

define('DEFAULT_PHOTOGRAPHER_ID', 1);

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
    } else {
        // Photographer ID is not found
        header("Location: error.php"); // Redirect to an error 
    }
} else {
    // Photographer ID is not provided
    header("Location: error.php");
    exit();
}

// Fetch reviews for the photographer
$reviews_query = "SELECT r.review_text, r.created_at, u.firstname, u.lastname, u.profile_photo FROM reviews r JOIN users u ON r.client_id = u.id WHERE r.photographer_id = '$photographer_id' ORDER BY r.created_at DESC";
$reviews_result = $conn->query($reviews_query);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews for <?php echo $firstname . ' ' . $lastname; ?></title>
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

<div class="review-container">
    <div class="review-section write-review">
        <h2>Write a Review</h2>
        <?php if (isset($_SESSION['username']) && $_SESSION['role'] === 'client'): ?>
            <form action="submit_review.php" method="POST">
                <input type="hidden" name="photographer_id" value="<?php echo $photographer_id; ?>">
                <textarea name="review_text" placeholder="Write your review here..." required></textarea>
                <button type="submit" class="submit-review-btn">Submit Review</button>
            </form>
        <?php else: ?>
            <p>You need to be logged in as a client to write a review.</p>
        <?php endif; ?>
    </div>

    <div class="review-section view-reviews">
        <h2>Reviews</h2>
        <div class="reviews-container">
            <?php
            if ($reviews_result->num_rows > 0) {
                while ($review = $reviews_result->fetch_assoc()) {
                    echo '<div class="review">';
                    echo '<img src="' . (!empty($review['profile_photo']) ? 'uploads/' . $review['profile_photo'] : 'uploads/icons/userprofiledefault.png') . '" alt="Client Photo" width="40">';
                    echo '<div class="review-content">';
                    echo '<p><strong>' . $review['firstname'] . ' ' . $review['lastname'] . ':</strong> ' . $review['review_text'] . '</p>';
                    echo '<p class="review-date">' . $review['created_at'] . '</p>';
                    
                    echo '</div>'; // Close review-content
                    echo '</div>'; // Close review
                }
            } else {
                echo 'No reviews found.';
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
