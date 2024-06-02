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

// Retrieve user's information from the database
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $email = $row['email'];
    $phone = $row['phone'];
    $profile_photo = $row['profile_photo'];
    $userbiography = $row['userbiography'];
    $userlocation = $row['userlocation'];
    $instagram = $row['instagram'];
    $facebook = $row['facebook'];
    $tiktok = $row['tiktok'];
    $user_role = $row['role'];

    // Check if there's a profile photo stored in the database
    if (!empty($profile_photo)) {
        // Construct the full path to the profile photo
        $profile_photo_path = 'uploads/' . $profile_photo;
        // Check if the file exists
        if (file_exists($profile_photo_path)) {
            // If profile photo exists, set it as the source for the preview
            $profile_photo_src = $profile_photo_path;
        } else {
            // If the file is missing, set a default preview image
            $profile_photo_src = 'uploads/icons/userprofiledefault.png'; 
        }
    } else {
        // If no profile photo exists, set a default preview image
        $profile_photo_src = 'uploads/icons/userprofiledefault.png';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check which form was submitted
    if (isset($_POST['update_personal_info'])) {
        // Update personal information
        $new_firstname = $_POST['new_firstname'];
        $new_lastname = $_POST['new_lastname'];
        $new_email = $_POST['new_email'];
        $new_phone = $_POST['new_phone'];

        $update_sql = "UPDATE users SET firstname = ?, lastname = ?, email = ?, phone = ? WHERE username = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssss", $new_firstname, $new_lastname, $new_email, $new_phone, $username);
        if ($update_stmt->execute()) {
            // Update session variables
            $_SESSION['email'] = $new_email;
            $_SESSION['phone'] = $new_phone;

            // Reload the page to reflect changes
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            // Handle error
            echo "Error: " . $conn->error;
        }
    } elseif (isset($_POST['update_public_profile'])) {
        // Update public profile (biography and location)
        $new_userlocation = $_POST['new_userlocation'];
        $new_userbiography = $_POST['new_userbiography'];

        $update_sql = "UPDATE users SET userlocation = ?, userbiography = ? WHERE username = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sss", $new_userlocation, $new_userbiography, $username);
        if ($update_stmt->execute()) {
            // Reload the page to reflect changes
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            // Handle error
            echo "Error: " . $conn->error;
        }
    } elseif (isset($_POST['update_social_media'])) {
        // Update social media links
        $new_instagram = $_POST['new_instagram'];
        $new_facebook = $_POST['new_facebook'];
        $new_tiktok = $_POST['new_tiktok'];

        $update_sql = "UPDATE users SET instagram = ?, facebook = ?, tiktok = ? WHERE username = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssss", $new_instagram, $new_facebook, $new_tiktok, $username);
        if ($update_stmt->execute()) {
            // Reload the page to reflect changes
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            // Handle error
            echo "Error: " . $conn->error;
        }
    } elseif (isset($_POST['update_profile_picture'])) {
        // Update profile picture
        if (isset($_FILES['new_profile_photo']) && $_FILES['new_profile_photo']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/';
            $tmp_name = $_FILES['new_profile_photo']['tmp_name'];
            $original_name = basename($_FILES['new_profile_photo']['name']);
            $upload_file = $upload_dir . $original_name;

            // Move the uploaded file to the desired directory
            if (move_uploaded_file($tmp_name, $upload_file)) {
                // Update the profile photo path in the database
                $update_sql = "UPDATE users SET profile_photo = ? WHERE username = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ss", $original_name, $username);
                if ($update_stmt->execute()) {
                    // Reload the page to reflect changes
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit;
                } else {
                    // Handle error
                    echo "Error: " . $conn->error;
                }
            } else {
                // Handle error
                echo "Error uploading file.";
            }
        }
    } elseif (isset($_POST['update_contacts'])) {
        // Update contact information
        $new_email = $_POST['new_email'];
        $new_phone = $_POST['new_phone'];

        $update_sql = "UPDATE users SET email = ?, phone = ? WHERE username = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sss", $new_email, $new_phone, $username);
        if ($update_stmt->execute()) {
            // Update email and phone in session
            $_SESSION['email'] = $new_email;
            $_SESSION['phone'] = $new_phone;

            // Reload the page to reflect changes
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            // Handle error
            echo "Error: " . $conn->error;
        }
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

<div class="content-container">
    <div class="settings-container">
        <div class="settings-tabs">
            <div class="tab" onclick="showSection(event, 'personal-info')">Personal Information</div>
            <div class="tab" onclick="showSection(event, 'contacts')">Contacts</div>
            <div class="tab" onclick="showSection(event, 'profile-picture')">Profile Picture</div>
            <?php if ($user_role != "client") : ?>
                <div class="tab" onclick="showSection(event, 'public-profile')">Public Profile</div>

                <div class="tab" onclick="showSection(event, 'social-media')">Social Media</div>
            <?php endif; ?>
        </div>

        <div class="settings-section" id="personal-info-section" style="display: none;">
        <h2>Personal Information</h2>
        <p>This is where you can change the information for your public profile to show to others!</p><br>

        
        <h2><?php echo $firstname . ' ' . $lastname; ?></h2>



        </form>
    </div>


    
    <div class="settings-section" id="contacts-section" style="display: none;">
            <h3>Contacts</h3>
            <form id="contacts-form" method="POST">
                <label for="new_phone">Phone Number:</label>
                <input type="text" id="new_phone" name="new_phone" value="<?php echo $phone; ?>"><br>
                <label for="new_email">Email:</label>
                <input type="email" id="new_email" name="new_email" value="<?php echo $email; ?>"><br>
                <button type="button" onclick="updateSettings('contacts', 'contacts-form')" class="settings-button">Save Changes</button>
                <p id="contacts-update-message"></p>
                <input type="hidden" name="update_contacts">
            </form>
        </div>

        <div class="settings-section" id="profile-picture-section" style="display: none;">
            <h3>Profile Picture</h3>
            <form id="profile-picture-form" method="POST" enctype="multipart/form-data">
                <label for="new_profile_photo">Upload Profile Photo:</label>
                <input type="file" id="new_profile_photo" name="new_profile_photo" accept="image/gif, image/jpeg, image/png" onchange="previewProfilePhoto(event)"><br>
                <div class="profile-photo-preview-container">
                    <?php if (!empty($profile_photo)) : ?>
                        <img id="profile-photo-preview" src="<?php echo $profile_photo_src; ?>" alt="" style="display: block;">
                        <p id="originalFileName">Original File Name: <?php echo $profile_photo; ?></p>
                    <?php else : ?>
                        <img id="profile-photo-preview" src="uploads/icons/userprofiledefault.png" alt="Profile Photo Preview" style="display: block;">
                    <?php endif; ?>
                </div><br>
                <button type="button" onclick="updateProfilePicture()" class="settings-button">Save Changes</button>
                <p id="profile-picture-update-message"></p>
                <input type="hidden" name="update_profile_picture">
            </form>
        </div>

        <div class="settings-section" id="public-profile-section" style="display: none;">
    <h3>Public Profile</h3>
    <form id="public-profile-form" method="POST">
        <label for="new_userlocation">Location:</label><br>
        <div class="select-wrapper">
            <select id="new_userlocation" name="new_userlocation">
                <?php
                // Fetch locations from the locations table
                $locations_query = "SELECT * FROM locations";
                $locations_result = $conn->query($locations_query);
                if ($locations_result->num_rows > 0) {
                    while ($row = $locations_result->fetch_assoc()) {
                        $selected = ($userlocation == $row['location_name']) ? 'selected' : '';
                        echo '<option value="' . $row['location_name'] . '" ' . $selected . '>' . $row['location_name'] . '</option>';
                    }
                } else {
                    echo '<option value="">No locations found</option>';
                }
                ?>
            </select>
        </div><br><br>

        <label for="new_userbiography">Biography:</label>
        <input type="text" id="new_userbiography" name="new_userbiography" value="<?php echo $userbiography; ?>"><br>

        <button type="button" onclick="updateSettings('public-profile', 'public-profile-form')" class="settings-button">Save Changes</button>
        <p id="public-profile-update-message"></p> <!-- Success message will be displayed here -->
        <input type="hidden" name="update_public_profile">
    </form>
</div>


        <?php if ($user_role != "client") : ?>
            <div class="settings-section" id="social-media-section" style="display: none;">
            <h3>Social Media</h3>
            <form id="social-media-form" method="POST">
                <label for="new_instagram">Instagram:</label>
                <input type="text" id="new_instagram" name="new_instagram" value="<?php echo $instagram; ?>"><br>
                <label for="new_facebook">Facebook:</label>
                <input type="text" id="new_facebook" name="new_facebook" value="<?php echo $facebook; ?>"><br>
                <label for="new_tiktok">TikTok:</label>
                <input type="text" id="new_tiktok" name="new_tiktok" value="<?php echo $tiktok; ?>"><br>
                <button type="button" onclick="updateSettings('social-media', 'social-media-form')" class="settings-button">Save Changes</button>
                <p id="social-media-update-message"></p>
                <input type="hidden" name="update_social_media">
            </form>
        </div>
        <?php endif; ?>

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
function updateSettings(section, formId) {
    console.log('Updating settings for section:', section);
    console.log('Form ID:', formId);
    
    const formData = new FormData(document.getElementById(formId));
    fetch('<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            const messageElement = document.getElementById(section + '-update-message');
            messageElement.textContent = 'Information updated successfully!';
        } else {
            throw new Error('Network response was not ok.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const messageElement = document.getElementById(section + '-update-message');
        messageElement.textContent = 'Error updating information. Please try again.';
    });
}

    function showSection(event, sectionId) {
        const sections = document.querySelectorAll('.settings-section');
        sections.forEach(section => {
            section.style.display = 'none';
        });
        document.getElementById(sectionId + '-section').style.display = 'block';

        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => {
            tab.classList.remove('active');
        });
        event.currentTarget.classList.add('active');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const defaultTab = document.querySelector('.tab');
        showSection({ currentTarget: defaultTab }, 'personal-info');
    });

    function previewProfilePhoto(event) {
        const input = event.target;
        const imagePreview = document.getElementById('profile-photo-preview');
        const originalFileName = document.getElementById('originalFileName');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
            originalFileName.textContent = 'Original File Name: ' + input.files[0].name;
        } else {
            imagePreview.style.display = 'block';
        }
    }

    function updateProfilePicture() {
        const formData = new FormData(document.getElementById('profile-picture-form'));
        fetch('<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                const messageElement = document.getElementById('profile-picture-update-message');
                messageElement.textContent = 'Profile photo updated successfully!';
            } else {
                throw new Error('Network response was not ok.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const messageElement = document.getElementById('profile-picture-update-message');
            messageElement.textContent = 'Error updating profile photo. Please try again.';
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        showSection({ currentTarget: document.querySelector('.tab') }, 'personal-info');
    });
</script>

</body>

</html>