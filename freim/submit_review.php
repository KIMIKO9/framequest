<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['id']) && isset($_POST['photographer_id']) && isset($_POST['review_text'])) {
        $client_id = $_SESSION['id'];
        $photographer_id = mysqli_real_escape_string($conn, $_POST['photographer_id']);
        $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);

        $sql = "INSERT INTO reviews (client_id, photographer_id, review_text) VALUES ('$client_id', '$photographer_id', '$review_text')";

        if ($conn->query($sql) === TRUE) {
            header("Location: reviews.php?id=$photographer_id");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
} else {
    echo "Invalid request method";
}
?>
