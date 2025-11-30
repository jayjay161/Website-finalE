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

if (isset($_GET['id']) && isset($_GET['cost'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $cost = $conn->real_escape_string($_GET['cost']);
    
    $sql = "UPDATE repair_requests SET repair_cost = '$cost' WHERE id = '$id'";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error updating cost: " . $conn->error;
    }
}

$conn->close();
?>