<?php
$host = 'localhost';
$username = 'u983768004_joe69';
$password = 'Iloveconverge69';
$database = 'u983768004_data_details';

$conn = new mysqli($host, $username, $password, $database);

$id = $_GET['id'];
$conn->query("DELETE FROM repair_requests WHERE id=$id");

$conn->close();
header("Location: dashboard.php");
exit;
?>
