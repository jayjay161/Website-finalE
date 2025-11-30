<?php
$host = "localhost";
$user = "u983768004_harley69"; 
$pass = "Iloveconverge69"; 
$db   = "u983768004_login_database"; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
