<?php
// Database connection
$host = 'localhost';
$username = 'u983768004_joe69';
$password = 'Iloveconverge69';
$database = 'u983768004_data_details';

$conn = new mysqli($host, $username, $password, $database);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tracking_number'])) {
    $tracking_number = $conn->real_escape_string($_POST['tracking_number']);
    
    $sql = "SELECT * FROM repair_requests WHERE tracking_number = '$tracking_number'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $repair = $result->fetch_assoc();
        
        // Format dates
        $created_at = date('F j, Y g:i A', strtotime($repair['created_at']));
        $updated_at = date('F j, Y g:i A', strtotime($repair['updated_at']));
        
        echo json_encode([
            'success' => true,
            'data' => [
                'tracking_number' => $repair['tracking_number'],
                'full_name' => htmlspecialchars($repair['full_name']),
                'email' => htmlspecialchars($repair['email']),
                'phone' => htmlspecialchars($repair['phone'] ?? ''),
                'watch_brand_model' => htmlspecialchars($repair['watch_brand_model']),
                'issue_description' => htmlspecialchars($repair['issue_description']),
                'status' => $repair['status'],
                'repair_cost' => $repair['repair_cost'],
                'created_at' => $created_at,
                'updated_at' => $updated_at
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Tracking number not found. Please check your tracking number and try again.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No tracking number provided.'
    ]);
}

$conn->close();
?>