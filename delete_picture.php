<?php
// Include database connection file
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_picture'])) {
    $picture_id = $_POST['picture_id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Fetch the picture path from the database
        $fetch_path_query = "SELECT picture_path FROM pictures WHERE id = ?";
        $fetch_path_stmt = $conn->prepare($fetch_path_query);
        $fetch_path_stmt->bind_param("i", $picture_id);
        $fetch_path_stmt->execute();
        $fetch_path_result = $fetch_path_stmt->get_result();

        if ($fetch_path_result->num_rows === 1) {
            $picture_row = $fetch_path_result->fetch_assoc();
            $picture_path = $picture_row['picture_path'];

            // Check if the file exists and delete it
            if (file_exists($picture_path)) {
                if (!unlink($picture_path)) {
                    throw new Exception("Error: Unable to delete the file.");
                }
            }

            // Delete the picture from the database
            $delete_picture_query = "DELETE FROM pictures WHERE id = ?";
            $delete_picture_stmt = $conn->prepare($delete_picture_query);
            $delete_picture_stmt->bind_param("i", $picture_id);
            $delete_picture_stmt->execute();

            if ($delete_picture_stmt->affected_rows > 0) {
                // Commit the transaction
                $conn->commit();
                echo "Picture deleted successfully!";
            } else {
                throw new Exception("Error deleting picture.");
            }
        } else {
            throw new Exception("Error: Picture not found in the database.");
        }
    } catch (Exception $e) {
        // Rollback the transaction if there was an error
        $conn->rollback();
        echo $e->getMessage();
    }
}
?>
