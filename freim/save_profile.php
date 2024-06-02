<?php
session_start();
include 'db.php';

// Check if the user is logged in and is a client
if (isset($_SESSION['id'], $_SESSION['username'], $_SESSION['role']) && $_SESSION['role'] === 'client' && isset($_POST['photographer_id'])) {
    // Get client ID from session
    $client_id = $_SESSION['id'];
    // Sanitize input
    $photographer_id = mysqli_real_escape_string($conn, $_POST['photographer_id']);

    // Check if the profile is not already saved
    $check_query = "SELECT * FROM saved_profiles WHERE client_id = ? AND photographer_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("ii", $client_id, $photographer_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result === false) {
        // Log query error
        error_log("Query Error: " . $conn->error);
    } else {
        if ($check_result->num_rows == 0) {
            // Save the profile
            $insert_query = "INSERT INTO saved_profiles (client_id, photographer_id) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("ii", $client_id, $photographer_id);
            if ($insert_stmt->execute() === false) {
                // Log insert error
                error_log("Insert Error: " . $conn->error);
            }
        }
    }
}

// Redirect back to the photographer profile page
$redirect_url = "photographer_profile.php";
if (isset($photographer_id)) {
    $redirect_url .= "?id=$photographer_id";
}
header("Location: $redirect_url");
exit();
?>
