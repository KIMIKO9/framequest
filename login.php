<?php
// Start the session
session_start();

// Include the database connection file
include 'db.php';
include 'loading.php';

// Initialize error message variables
$email_error = $password_error = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if email and password are set and not empty
    if (empty($_POST["email"])) {
        $email_error = "Please enter your email.";
    } else {
        $email = $_POST["email"];
        // Prepare SQL statement to fetch user from database based on email
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        
        // Bind parameters and execute the statement
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        // Get result set
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows == 1) {
            // Fetch user information
            $row = $result->fetch_assoc();
            // Verify password
            if (password_verify($_POST["password"], $row["password"])) {
                // Store username in session
                $_SESSION['username'] = $row["username"];
                // Redirect to index.php with username parameter
                header("Location: index.php?username=" . urlencode($row["username"]));
                exit;
            } else {
                $password_error = "Invalid password. Please try again.";
            }
        } else {
            $email_error = "Email not found. Please enter a valid email.";
        }

        $stmt->close();
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

<div class="form-container">
  <a href="index.php" class="logo">Frame Quest</a>
  <h2>Login</h2>
  
  <form action="login.php" method="post">
    <input type="text" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    
    <span class="error"><?php echo $password_error; ?></span>
    <span class="error"><?php echo $email_error; ?></span>

    <div class="buttons">
        <button type="submit" class="connect-btn">Login</button>
    </div>
    <p>Don't have an account? <a href="register.php">Register</a></p>
  </form>
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
