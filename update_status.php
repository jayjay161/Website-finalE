<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: admin-login.php");
    exit;
}

// Database connection
$host = 'localhost';
$username = 'u983768004_joe69'; 
$password = 'Iloveconverge69';
$database = 'u983768004_data_details';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SECURITY FIX: Validate and sanitize inputs
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $status = $conn->real_escape_string($_GET['status']);
    
    // Validate status value
    $allowed_statuses = ['Pending', 'In Progress', 'Completed'];
    if (in_array($status, $allowed_statuses)) {
        // Use prepared statement for maximum security
        $stmt = $conn->prepare("UPDATE repair_requests SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Status updated successfully!";
        } else {
            $_SESSION['error'] = "Error updating status: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Invalid status value!";
    }
} else {
    $_SESSION['error'] = "Missing parameters!";
}

$conn->close();
header("Location: dashboard.php");
exit;
?>