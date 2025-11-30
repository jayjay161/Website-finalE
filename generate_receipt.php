<?php
session_start();

// Database connection
$host = 'localhost';
$username = 'u983768004_joe69';
$password = 'Iloveconverge69';
$database = 'u983768004_data_details';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SECURITY: Check if client access
if (isset($_GET['client']) && $_GET['client'] == 'true') {
    if (!isset($_SESSION['client_logged_in']) || $_SESSION['client_logged_in'] !== true) {
        header("Location: client-login.php");
        exit;
    }
    // Verify the repair ID belongs to this client
    if ($_SESSION['client_repair_id'] != $_GET['id']) {
        die("Unauthorized access!");
    }
    $id = $conn->real_escape_string($_GET['id']);
} 
// SECURITY: Check if admin access
else {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header("Location: admin-login.php");
        exit;
    }
    $id = $conn->real_escape_string($_GET['id']);
}

// Fetch repair data
$sql = "SELECT * FROM repair_requests WHERE id = '$id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Repair record not found!");
}

$row = $result->fetch_assoc();

// Calculate receipt details
$repair_cost = $row['repair_cost'] ?? 0;
$tax_rate = 0.12; // 12% VAT
$tax_amount = $repair_cost * $tax_rate;
$total_amount = $repair_cost + $tax_amount;

// Format dates
$completion_date = ($row['status'] == 'Completed') ? date('F j, Y') : 'In Progress';
$request_date = date('F j, Y', strtotime($row['created_at']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Receipt - Zaheer Watch Repair</title>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .receipt-container { 
            max-width: 400px; 
            margin: 20px auto; 
            background: white; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            border: 2px solid #e0e0e0;
        }
        .header { 
            text-align: center; 
            border-bottom: 3px double #333; 
            padding-bottom: 20px; 
            margin-bottom: 25px; 
        }
        .header h1 { 
            margin: 0; 
            color: #2c3e50; 
            font-size: 28px; 
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header p { 
            margin: 5px 0; 
            color: #7f8c8d; 
            font-size: 14px;
        }
        .receipt-details { 
            margin-bottom: 25px; 
        }
        .receipt-details .row { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 10px; 
            padding: 8px 0; 
            border-bottom: 1px dashed #ecf0f1;
        }
        .receipt-details .row.total { 
            border-top: 3px double #2c3e50; 
            border-bottom: none;
            font-weight: bold; 
            font-size: 20px; 
            margin-top: 20px; 
            padding-top: 20px; 
            color: #27ae60;
        }
        .receipt-details .row.subtotal {
            border-bottom: 2px solid #bdc3c7;
            font-weight: 600;
        }
        .footer { 
            text-align: center; 
            margin-top: 30px; 
            color: #7f8c8d; 
            font-size: 12px; 
            border-top: 1px solid #ddd; 
            padding-top: 20px; 
        }
        .barcode { 
            text-align: center; 
            margin: 25px 0; 
            font-family: 'Libre Barcode 128', cursive; 
            font-size: 50px; 
            color: #2c3e50;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            background: <?= $row['status'] == 'Completed' ? '#27ae60' : ($row['status'] == 'In Progress' ? '#f39c12' : '#e74c3c') ?>;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .stamp {
            text-align: center;
            margin: 20px 0;
            color: #e74c3c;
            font-weight: bold;
            font-size: 16px;
            border: 2px solid #e74c3c;
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
        }
        @media print {
            body { 
                background: white; 
                padding: 0;
                display: block;
            }
            .receipt-container { 
                box-shadow: none; 
                margin: 0; 
                border: none;
                max-width: 100%;
            }
            .no-print { display: none; }
        }
        .watermark {
            opacity: 0.1;
            position: absolute;
            font-size: 80px;
            transform: rotate(-45deg);
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="watermark">ZAHEER WATCH REPAIR</div>
    
    <div class="receipt-container">
        <div class="header">
            <h1>ZAHEER WATCH REPAIR</h1>
            <p>Professional Watch Repair Services</p>
            <p><strong>OFFICIAL RECEIPT</strong></p>
            <p>Receipt #: ZWR-<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?></p>
            <p>Date: <?= date('F j, Y') ?></p>
        </div>
        
        <div class="receipt-details">
            <div class="row">
                <span><strong>Customer Name:</strong></span>
                <span><?= htmlspecialchars($row['full_name']) ?></span>
            </div>
            <div class="row">
                <span><strong>Email:</strong></span>
                <span><?= htmlspecialchars($row['email']) ?></span>
            </div>
            <div class="row">
                <span><strong>Watch Model:</strong></span>
                <span><?= htmlspecialchars($row['watch_brand_model']) ?></span>
            </div>
            <div class="row">
                <span><strong>Issue Description:</strong></span>
                <span><?= htmlspecialchars($row['issue_description']) ?></span>
            </div>
            <div class="row">
                <span><strong>Request Date:</strong></span>
                <span><?= $request_date ?></span>
            </div>
            <div class="row">
                <span><strong>Completion Date:</strong></span>
                <span><?= $completion_date ?></span>
            </div>
            <div class="row">
                <span><strong>Status:</strong></span>
                <span class="status-badge"><?= $row['status'] ?></span>
            </div>
            
            <div style="height: 2px; background: linear-gradient(to right, transparent, #3498db, transparent); margin: 20px 0;"></div>
            
            <div class="row">
                <span>Repair Cost:</span>
                <span>₱<?= number_format($repair_cost, 2) ?></span>
            </div>
            <div class="row">
                <span>VAT (12%):</span>
                <span>₱<?= number_format($tax_amount, 2) ?></span>
            </div>
            <div class="row subtotal">
                <span>Subtotal:</span>
                <span>₱<?= number_format($repair_cost + $tax_amount, 2) ?></span>
            </div>
            <div class="row total">
                <span>TOTAL AMOUNT:</span>
                <span>₱<?= number_format($total_amount, 2) ?></span>
            </div>
        </div>

        <?php if($row['status'] == 'Completed'): ?>
        <div style="text-align: center; margin: 20px 0;">
            <div class="stamp">PAID</div>
        </div>
        <?php endif; ?>
        
        <div class="barcode">
            *ZWR<?= str_pad($row['id'], 5, '0', STR_PAD_LEFT) ?>*
        </div>
        
        <div class="footer">
            <p><strong>Thank you for choosing Zaheer Watch Repair!</strong></p>
            <p>For inquiries: contact@zaheerwatchrepair.com | Phone: (02) 1234-5678</p>
            <p>This is an official computer-generated receipt.</p>
            <p><em>"Precision in Every Tick"</em></p>
        </div>
        
        <div class="no-print" style="text-align: center; margin-top: 25px;">
            <button onclick="window.print()" style="padding: 12px 25px; background: linear-gradient(45deg, #3498db, #2980b9); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin: 5px;">
                <i class="fas fa-print"></i> Print Receipt
            </button>
            <button onclick="window.close()" style="padding: 12px 25px; background: linear-gradient(45deg, #e74c3c, #c0392b); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin: 5px;">
                <i class="fas fa-times"></i> Close Window
            </button>
            <?php if(isset($_GET['client']) && $_GET['client'] == 'true'): ?>
            <button onclick="window.location.href='client-dashboard.php'" style="padding: 12px 25px; background: linear-gradient(45deg, #27ae60, #219a52); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin: 5px;">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </button>
            <?php else: ?>
            <button onclick="window.location.href='dashboard.php'" style="padding: 12px 25px; background: linear-gradient(45deg, #27ae60, #219a52); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin: 5px;">
                <i class="fas fa-arrow-left"></i> Back to Admin
            </button>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script>
        // Auto-print if coming from client dashboard
        <?php if(isset($_GET['autoprint']) && $_GET['autoprint'] == 'true'): ?>
        window.onload = function() {
            window.print();
        }
        <?php endif; ?>
    </script>
</body>
</html>

<?php $conn->close(); ?>