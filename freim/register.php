<?php
// Start the session
session_start();
// Include database connection file
include 'db.php';
include 'loading.php';

// Initialize error message variable
$password_error = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all fields are set and not empty
    if (isset($_POST["username"], $_POST["firstname"], $_POST["lastname"], $_POST["email"], $_POST["password"], $_POST["role"]) && 
        !empty($_POST["username"]) && !empty($_POST["firstname"]) && !empty($_POST["lastname"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["role"])) {
        
        // Check password length
        if (strlen($_POST["password"]) < 7) {
            $password_error = "Password must be at least 7 characters long.";
        } else {
            // Prepare and bind parameters
            $stmt = $conn->prepare("INSERT INTO users (username, firstname, lastname, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $username, $firstname, $lastname, $email, $password, $role);

            // Set parameters
            $username = $_POST["username"];
            $firstname = $_POST["firstname"];
            $lastname = $_POST["lastname"];
            $email = $_POST["email"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $role = $_POST["role"];

            // Execute the query
            if ($stmt->execute()) {
                // Redirect to login.php
                header("Location: login.php");
                exit(); // Ensure that script execution stops after redirection
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        // Fields are not properly filled
        $password_error = "Please fill all the fields.";
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
                echo '<a href="dashboard.php" class="user-icon"><img src="uploads/settings.png" alt="User Icon" width="40"></a>';

                // Display save icon for clients
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'client') {
                    echo '<a href="saved_profiles.php" class="user-icon"><img src="uploads/saved.png" alt="Save Icon" width="40"></a>';
                }

                // Display upload icon if the user is a photographer
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'photographer') {
                    echo '<a href="upload_picture.php" class="user-icon"><img src="uploads/upload.png" alt="Upload Icon" width="40"></a>';
                    
                    // Construct the link for the photographer profile
                    $photographer_id = $_SESSION['id'];
                    $photographer_profile_link = 'photographer_profile.php?id=' . urlencode($photographer_id);
                    echo '<a href="' . $photographer_profile_link . '" class="user-icon"><img src="uploads/profile.png" alt="Photographer Profile Icon" width="40"></a>';
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
  <h2>Register</h2>
  <form id="registrationForm" action="register.php" method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="text" name="firstname" placeholder="First Name" required>
    <input type="text" name="lastname" placeholder="Last Name" required>
    <input type="email" name="email" placeholder="Email" required>

    <input type="password" name="password" placeholder="Password, minimum 7 characters" required>
    <span class="error"><?php echo $password_error; ?></span>


    <p><a> What are you here for?</a></p>


    <div class="buttons">
      <button type="button" class="role-btn" data-role="photographer">Photographer</button>
      <button type="button" class="role-btn" data-role="client">Client</button>
    </div>
    <input type="hidden" name="role" id="selectedRole" value="">
    <button type="submit" class="connect-btn">Register</button>
    <p>Already have an account? <a href="login.php">Log in</a></p>
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


<script>
document.addEventListener("DOMContentLoaded", function() {
  const roleButtons = document.querySelectorAll('.role-btn');
  const selectedRoleInput = document.getElementById('selectedRole');
  
  roleButtons.forEach(button => {
    button.addEventListener('click', function() {
      roleButtons.forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');
      selectedRoleInput.value = this.dataset.role;
    });
  });

  
});
</script>

</body>
</html>
