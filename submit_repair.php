<?php
// Database credentials
$host = 'localhost';
$username = 'u983768004_joe69';
$password = 'Iloveconverge69';
$database = 'u983768004_data_details';

// Create database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$watch_brand_model = $_POST['watch_brand_model'];
$issue_description = $_POST['issue_description'];

// Handle file upload (photo)
$photo = $_FILES['photo'];
$photo_url = '';  // Default value

if ($photo['error'] === UPLOAD_ERR_OK) {
    // Move the uploaded file to a folder
    $upload_dir = 'uploads/';
    $photo_url = $upload_dir . basename($photo['name']);
    move_uploaded_file($photo['tmp_name'], $photo_url);
}

// Insert data into database
$sql = "INSERT INTO repair_requests (full_name, email, watch_brand_model, issue_description, photo_url) 
        VALUES ('$full_name', '$email', '$watch_brand_model', '$issue_description', '$photo_url')";

if ($conn->query($sql) === TRUE) {
    echo "Repair request submitted successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
