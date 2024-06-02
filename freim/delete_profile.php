<?php
session_start();
include 'db.php';

// Check if user is logged in and has a client role
if (isset($_SESSION['username']) && $_SESSION['role'] === 'client') {
    // Check if photographer ID is provided
    if (isset($_POST['photographer_id'])) {
        // Sanitize the input to prevent SQL injection
        $photographer_id = mysqli_real_escape_string($conn, $_POST['photographer_id']);
        $client_id = $_SESSION['id']; // Get client ID from session

        // Delete the saved profile from the database
        $delete_query = "DELETE FROM saved_profiles WHERE client_id = '$client_id' AND photographer_id = '$photographer_id'";
        if ($conn->query($delete_query) === TRUE) {
            echo "Profile deleted successfully";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Photographer ID not provided";
    }
} else {
    // User is not logged in or does not have a client role
    echo "Unauthorized access";
}

// Close the database connection
$conn->close();
?>
