<?php
// Start the session
session_start();

// Include database connection file
include 'db.php';
include 'loading.php';

// Fetch all categories with their associated images
$categories_query = "SELECT c.id, c.category_name, c.category_image, COUNT(p.id) AS total_pictures FROM categories c LEFT JOIN pictures p ON c.id = p.category_id GROUP BY c.id";
$categories_result = $conn->query($categories_query);

// Fetch user pictures with user details
$user_pictures_query = "SELECT p.picture_path, p.picture_name, u.id, u.firstname, u.lastname, u.profile_photo FROM pictures p JOIN users u ON p.username = u.username";
$user_pictures_result = $conn->query($user_pictures_query);
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

<div class="gallery-container">
    <h1 class="gallery-header">Categories</h1>

    <div class="categories-carousel-container">
        <div class="categories-carousel">
        <?php
if ($categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        echo '<div class="category-card" onclick="showCategoryPictures(' . $row['id'] . ')">';
        echo '<img class="category-image" src="' . $row['category_image'] . '" alt="' . $row['category_name'] . '">';
        echo '<div class="category-details">';
        echo '<div class="category-name">' . $row['category_name'] . '</div>';
        echo '<div class="category-pictures">Total Pictures: ' . $row['total_pictures'] . '</div>';
        echo '</div>'; // Close category-details
        echo '</div>'; // Close category-card
    }
} else {
    echo 'No categories found.';
}
?>

        </div>
    </div>


    <div class="carousel-arrow-container" id="carousel-arrow-container">
        <div class="carousel-arrow carousel-arrow-left">
            <svg width="24" height="24" viewBox="0 0 24 24">
                <path fill="#000000" d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
            </svg>
        </div>
        <div class="carousel-arrow carousel-arrow-right">
            <svg width="24" height="24" viewBox="0 0 24 24">
                <path fill="#000000" d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z" />
            </svg>
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
                
                $profile_src = 'uploads/' . $row['profile_photo'];
                echo '<img src="' . $profile_src . '" alt="' . $row['firstname'] . ' ' . $row['lastname'] . '">';
                
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
    </div>
<!-- Scroll to top button -->
<div class="scroll-to-top" id="scrollToTopBtn" onclick="scrollToTop()">
  <img src="uploads/icons/top.png" alt="Icon Image">
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the scroll carousel
    const categoriesCarousel = document.querySelector('.categories-carousel');
    const carouselArrowContainer = document.getElementById('carousel-arrow-container');
    const carouselLeftArrow = document.querySelector('.carousel-arrow-left');
    const carouselRightArrow = document.querySelector('.carousel-arrow-right');

    // Function to adjust arrow positions dynamically
    function adjustArrowPositions() {
        const containerWidth = categoriesCarousel.offsetWidth;
        const arrowContainerWidth = carouselArrowContainer.offsetWidth;
        const arrowWidth = carouselLeftArrow.offsetWidth;

        const leftArrowPosition = (containerWidth - arrowContainerWidth) / 2 - arrowWidth - 10; 
        const rightArrowPosition = (containerWidth + arrowContainerWidth) / 2 + 10; 

        carouselLeftArrow.style.left = leftArrowPosition + 'px';
        carouselRightArrow.style.right = (containerWidth - rightArrowPosition) + 'px'; 
    }

    adjustArrowPositions();

    window.addEventListener('resize', adjustArrowPositions);

    carouselLeftArrow.addEventListener('click', function() {
        categoriesCarousel.scrollBy({
            left: -categoriesCarousel.offsetWidth,
            behavior: 'smooth'
        });
    });

    carouselRightArrow.addEventListener('click', function() {
        categoriesCarousel.scrollBy({
            left: categoriesCarousel.offsetWidth,
            behavior: 'smooth'
        });
    });
});


function showCategoryPictures(categoryId) {
    // Send AJAX request to fetch pictures for the selected category
    fetch('get_category_pictures.php?category_id=' + categoryId)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch category pictures');
            }
            return response.json();
        })
        .then(data => {
            // Update the gallery with the received picture data
            const galleryContainer = document.querySelector('.masonry');
            galleryContainer.innerHTML = ''; // Clear existing content
            data.forEach(picture => {
                const masonryItem = document.createElement('div');
                masonryItem.classList.add('masonry-item');
                const img = document.createElement('img');
                img.src = picture.picture_path;
                img.alt = picture.picture_name;
                
                const userDetails = document.createElement('div');
                userDetails.classList.add('user-details');
                
                const profileImg = document.createElement('img');
                profileImg.src = 'uploads/' + picture.profile_photo;
                profileImg.alt = picture.firstname + ' ' + picture.lastname;
                profileImg.classList.add('user-profile');
                
                const userInfoPopup = document.createElement('div');
                userInfoPopup.classList.add('user-info-popup');
                userInfoPopup.innerText = picture.firstname + ' ' + picture.lastname;
                
                // Append elements
                userDetails.appendChild(profileImg);
                userDetails.appendChild(userInfoPopup);
                masonryItem.appendChild(img);
                masonryItem.appendChild(userDetails);
                galleryContainer.appendChild(masonryItem);
            });
        })
        .catch(error => {
            console.error('Error fetching category pictures:', error);
        });
}



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
