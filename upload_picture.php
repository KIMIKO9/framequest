<?php
// Start the session
session_start();

// Include database connection file
include 'db.php';
include 'loading.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit;
}

// Get the username from the session
$username = $_SESSION['username'];

// Fetch user role from the database
$user_role_query = "SELECT role FROM users WHERE username = ?";
$user_role_stmt = $conn->prepare($user_role_query);
$user_role_stmt->bind_param("s", $username);
$user_role_stmt->execute();
$user_role_result = $user_role_stmt->get_result();

if ($user_role_result->num_rows === 1) {
    $user_role_row = $user_role_result->fetch_assoc();
    $user_role = $user_role_row['role'];
} else {
    $error_message = "Error: User role not found.";
}

// Process picture upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_media'])) {
    // Handle picture upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["media_file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an image
    $check = getimagesize($_FILES["media_file"]["tmp_name"]);
    if ($check === false) {
        $error_message = "File is not an image.";
        $uploadOk = 0;
    }

    if ($_FILES["media_file"]["size"] > 5000000) {
        $error_message = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $error_message = "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["media_file"]["tmp_name"], $target_file)) {
            // File uploaded successfully, insert into database
            $picture_name = $_POST['picture_name'];
            $categories = $_POST['categories']; // Retrieve selected categories

            // Insert picture information into database
            $insert_sql = "INSERT INTO pictures (username, picture_name, picture_path, upload_timestamp, category_id) VALUES (?, ?, ?, NOW(), ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            // Prepare a string to hold category IDs
            $category_ids = '';
            foreach ($categories as $category_name) {
                $category_name = trim($category_name);
                // Fetch category ID based on category name
                $category_query = "SELECT id FROM categories WHERE category_name = ?";
                $category_stmt = $conn->prepare($category_query);
                $category_stmt->bind_param("s", $category_name);
                $category_stmt->execute();
                $category_result = $category_stmt->get_result();
                if ($category_result->num_rows == 1) {
                    $category_row = $category_result->fetch_assoc();
                    $category_id = $category_row['id'];
                    // Append category ID to the string
                    $category_ids .= $category_id . ',';
                }
            }
            // Remove the trailing comma
            $category_ids = rtrim($category_ids, ',');
            $insert_stmt->bind_param("ssss", $username, $picture_name, $target_file, $category_ids);
            $insert_success = $insert_stmt->execute();

            if ($insert_success) {
                $success_message = "Picture uploaded successfully!";
                // Redirect to the same page to prevent form resubmission
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $error_message = "Error uploading picture.";
            }
        } else {
            $error_message = "Sorry, there was an error uploading your file.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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
<div class="settings-container">
<div class="settings-tabs">
    <?php if ($user_role != "client") : ?>
        <div class="tab" onclick="showSection('upload-media')">Upload Media</div>
        <div class="tab" onclick="showSection('delete-media')">Delete Media</div>
    <?php endif; ?>
</div>

<div class="settings-section" id="upload-media">
    <h3>Upload Media</h3>
    <form id="upload-media-form" method="POST" enctype="multipart/form-data">
        <label for="media_file">Upload Picture:</label>
        <input type="file" id="media_file" name="media_file" accept="image/*"><br>
        <label for="picture_name">Picture Name:</label>
        <input type="text" id="picture_name" name="picture_name"><br>
        <label for="categories">Categories:</label><br>
        <div class="upload-wrapper">
    <select id="categories" name="categories[]" multiple>
        <?php
        // Fetch categories from the database
        $categories_query = "SELECT * FROM categories";
        $categories_result = $conn->query($categories_query);
        if ($categories_result->num_rows > 0) {
            while ($row = $categories_result->fetch_assoc()) {
                echo '<option value="' . $row['category_name'] . '">' . $row['category_name'] . '</option>';
            }
        } else {
            echo '<option value="">No categories found</option>';
        }
        ?>
    </select>
</div>
        <button type="submit" name="upload_media" class="settings-button">Upload</button>
        <p id="upload-media-update-message"></p>
    </form>
</div>

    <div class="settings-section deletetab" id="delete-media">
    <h3>Delete Pictures</h3>
    <div class="masonry">
        <?php
        // Fetch user's uploaded pictures from the database
        $pictures_query = "SELECT id, picture_name, picture_path FROM pictures WHERE username = ?";
        $pictures_stmt = $conn->prepare($pictures_query);
        $pictures_stmt->bind_param("s", $username);
        $pictures_stmt->execute();
        $pictures_result = $pictures_stmt->get_result();

        if ($pictures_result->num_rows > 0) {
            while ($picture = $pictures_result->fetch_assoc()) {
                echo '<div class="masonry-item">';
                echo '<img id="picture_' . $picture['id'] . '" src="' . $picture['picture_path'] . '" alt="' . htmlspecialchars($picture['picture_name']) . '">';
                echo '<form class="delete-form" method="POST">';
                echo '<input type="hidden" name="picture_id" value="' . $picture['id'] . '">';
                echo '<button type="button" class="delete-btn" onclick="deletePicture(' . $picture['id'] . ')">Delete</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "No pictures uploaded.";
        }
        ?>
    </div>

    <div id="success-message" class="success-message"></div>
<div id="error-message" class="error-message"></div>

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
function showSection(sectionId) {
    // Hide all settings sections
    var sections = document.getElementsByClassName("settings-section");
    for (var i = 0; i < sections.length; i++) {
        sections[i].style.display = "none";
    }
    // Show the selected section
    document.getElementById(sectionId).style.display = "block";
}

// Initially show the first section
document.addEventListener('DOMContentLoaded', function() {
    var initialSectionId = document.querySelector('.settings-section').id;
    showSection(initialSectionId);
});

function deletePicture(pictureId) {
    // Directly delete the picture
    const formData = new FormData();
    formData.append('delete_picture', true);
    formData.append('picture_id', pictureId);

    fetch('delete_picture.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            return response.text();
        } else {
            throw new Error('Network response was not ok.');
        }
    })
    .then(data => {

        document.getElementById('success-message').innerHTML = data;
        setTimeout(function() {
            document.getElementById('success-message').innerHTML = '';
        }, 3000);

        const deletedPicture = document.getElementById('picture_' + pictureId);
        if (deletedPicture) {
            deletedPicture.parentNode.remove();
        }
    })
    .catch(error => {
        console.error('Error:', error);

        document.getElementById('error-message').innerHTML = 'Error deleting picture. Please try again.';
        setTimeout(function() {
            document.getElementById('error-message').innerHTML = '';
        }, 3000);
    });
}
</script>



</body>
</html>
