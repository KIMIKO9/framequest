<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frame Quest</title>
</head>
<body>


<div class="loading-spinner-container">
    <img src="uploads/icons/loaderphoto.png" class="spinner-image" alt="Loading Spinner">
</div>


<script>
    // Simulate loading delay
    setTimeout(function() {
        // Hide the loading spinner
        document.querySelector('.loading-spinner-container').style.display = 'none';
        // Show the main content
        document.getElementById('main-content').style.display = 'block';
    }, 700); // Delay time (in milliseconds)
</script>


</body>
</html>
