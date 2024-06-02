<?php
// Include database connection file
include 'db.php';

// Check if category_id is provided in the request
if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    // Fetch pictures belonging to the specified category along with user details
    $category_pictures_query = "SELECT p.picture_path, p.picture_name, u.firstname, u.lastname, u.profile_photo 
                                FROM pictures p 
                                JOIN users u ON p.username = u.username 
                                WHERE p.category_id = $category_id";
    $category_pictures_result = $conn->query($category_pictures_query);

    // Store the picture data in an array
    $pictures = [];
    if ($category_pictures_result->num_rows > 0) {
        while ($row = $category_pictures_result->fetch_assoc()) {
            $pictures[] = $row;
        }
    }

    // Return the picture data as JSON
    header('Content-Type: application/json');
    echo json_encode($pictures);
} else {
    // If category_id is not provided, return an error response
    http_response_code(400);
    echo json_encode(['error' => 'Category ID is required']);
}
?>
