<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: admin-login.php");
    exit;
}

// Database connection (SAME AS YOUR EXISTING CODE)
$host = 'localhost';
$username = 'u983768004_joe69';
$password = 'Iloveconverge69';
$database = 'u983768004_data_details';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['id']) && isset($_POST['repair_cost'])) {
    $id = $conn->real_escape_string($_POST['id']);
    $repair_cost = $conn->real_escape_string($_POST['repair_cost']);
    
    $update_sql = "UPDATE repair_requests SET repair_cost = '$repair_cost' WHERE id = '$id'";
    $conn->query($update_sql);
}

$conn->close();
header("Location: dashboard.php");
exit;
?>