<?php
session_start();

// Check if user is logged in (using your universal login)
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php"); // Your universal login page
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

// If client accesses this page, they need to provide repair ID
$repair_id = $_GET['repair_id'] ?? $_SESSION['client_repair_id'] ?? null;

if (!$repair_id) {
    // Show repair ID input form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Enter Repair ID - Watch Repair Tracking</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            body {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
            }
            .repair-id-container {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 20px;
                box-shadow: 0 15px 35px rgba(0,0,0,0.2);
                padding: 40px;
                max-width: 500px;
                width: 100%;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="repair-id-container text-center">
                        <h2 class="mb-4"><i class="fas fa-search me-2"></i>Track Your Watch Repair</h2>
                        <p class="text-muted mb-4">Please enter your Repair ID to view your watch status</p>
                        
                        <form method="GET" action="client-dashboard.php">
                            <div class="form-group">
                                <label><strong>Repair ID:</strong></label>
                                <input type="text" class="form-control form-control-lg text-center" 
                                       name="repair_id" placeholder="e.g., 1, 2, 3..." required
                                       style="font-size: 1.5rem; font-weight: bold;">
                                <small class="form-text text-muted">
                                    You can find your Repair ID in your confirmation email or receipt
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <i class="fas fa-eye me-2"></i>View Repair Status
                            </button>
                        </form>
                        
                        <div class="mt-4">
                            <a href="dashboard.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Admin Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Fetch client's specific repair request
$stmt = $conn->prepare("SELECT * FROM repair_requests WHERE id = ?");
$stmt->bind_param("i", $repair_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("<div class='alert alert-danger text-center'>Repair ID not found! Please check your Repair ID.</div>");
}

$repair_data = $result->fetch_assoc();

// Store in session for future access
$_SESSION['client_repair_id'] = $repair_id;
$_SESSION['client_name'] = $repair_data['full_name'];

// Define progress steps
$progress_steps = [
    'Pending' => 1,
    'In Progress' => 2, 
    'Completed' => 3
];

$current_step = $progress_steps[$repair_data['status']] ?? 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Watch Repair - Client Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-header {
            background: linear-gradient(45deg, #2c3e50, #34495e);
            color: white;
            padding: 30px 0;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .progress-container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .progress-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin: 40px 0;
        }
        .progress-steps::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 4px;
            background: #e0e0e0;
            transform: translateY(-50%);
            z-index: 1;
        }
        .progress-bar {
            position: absolute;
            top: 50%;
            left: 0;
            height: 4px;
            background: #3498db;
            transform: translateY(-50%);
            z-index: 2;
            transition: width 0.5s ease;
        }
        .step {
            background: white;
            border: 3px solid #e0e0e0;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            position: relative;
            z-index: 3;
            transition: all 0.3s ease;
        }
        .step.active {
            border-color: #3498db;
            background: #3498db;
            color: white;
        }
        .step.completed {
            border-color: #27ae60;
            background: #27ae60;
            color: white;
        }
        .step-label {
            position: absolute;
            top: 50px;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .info-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border-left: 4px solid #3498db;
        }
        .receipt-section {
            background: linear-gradient(45deg, #27ae60, #2ecc71);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
        }
        .status-badge {
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 0.9rem;
        }
        .status-pending { background: #e74c3c; color: white; }
        .status-in-progress { background: #f39c12; color: white; }
        .status-completed { background: #27ae60; color: white; }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- HEADER -->
        <div class="dashboard-header text-center">
            <h1><i class="fas fa-tachometer-alt me-2"></i>My Watch Repair Dashboard</h1>
            <p class="lead mb-0">Welcome back, <strong><?= htmlspecialchars($_SESSION['client_name']) ?></strong></p>
            <small class="opacity-75">Repair ID: #<?= $repair_id ?></small>
        </div>

        <!-- PROGRESS TRACKER -->
        <div class="progress-container">
            <h3 class="text-center mb-4"><i class="fas fa-project-diagram me-2"></i>Repair Progress</h3>
            
            <div class="progress-steps">
                <div class="progress-bar" style="width: <?= (($current_step - 1) / 2) * 100 ?>%"></div>
                
                <div class="step <?= $current_step >= 1 ? 'completed' : ($current_step == 1 ? 'active' : '') ?>">
                    1
                    <div class="step-label">Pending</div>
                </div>
                <div class="step <?= $current_step >= 2 ? 'completed' : ($current_step == 2 ? 'active' : '') ?>">
                    2
                    <div class="step-label">In Progress</div>
                </div>
                <div class="step <?= $current_step >= 3 ? 'completed' : ($current_step == 3 ? 'active' : '') ?>">
                    3
                    <div class="step-label">Completed</div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $repair_data['status'])) ?>">
                    Current Status: <?= $repair_data['status'] ?>
                </span>
            </div>
        </div>

        <div class="row">
            <!-- LEFT COLUMN - Repair Details -->
            <div class="col-md-6">
                <div class="info-card">
                    <h4><i class="fas fa-info-circle me-2"></i>Repair Details</h4>
                    <hr>
                    <p><strong>Customer:</strong> <?= htmlspecialchars($repair_data['full_name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($repair_data['email']) ?></p>
                    <p><strong>Watch:</strong> <?= htmlspecialchars($repair_data['watch_brand_model']) ?></p>
                    <p><strong>Issue:</strong> <?= htmlspecialchars($repair_data['issue_description']) ?></p>
                    <p><strong>Submitted:</strong> <?= date('M j, Y', strtotime($repair_data['created_at'])) ?></p>
                </div>

                <div class="info-card">
                    <h4><i class="fas fa-receipt me-2"></i>Cost Information</h4>
                    <hr>
                    <?php if ($repair_data['repair_cost'] > 0): ?>
                        <h3 class="text-success">₱<?= number_format($repair_data['repair_cost'], 2) ?></h3>
                        <p class="text-muted">Final repair cost</p>
                    <?php else: ?>
                        <p class="text-warning"><i class="fas fa-clock me-1"></i>Cost estimation in progress</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RIGHT COLUMN - Photo & Receipt -->
            <div class="col-md-6">
                <?php if ($repair_data['photo_url']): ?>
                <div class="info-card">
                    <h4><i class="fas fa-camera me-2"></i>Watch Photo</h4>
                    <hr>
                    <img src="<?= $repair_data['photo_url'] ?>" class="img-fluid rounded" 
                         style="max-height: 200px; cursor: pointer;" 
                         onclick="window.open('<?= $repair_data['photo_url'] ?>', '_blank')">
                    <p class="text-muted mt-2"><small>Click image to view larger</small></p>
                </div>
                <?php endif; ?>

                <?php if ($repair_data['status'] == 'Completed'): ?>
                <div class="receipt-section">
                    <h4><i class="fas fa-check-circle me-2"></i>Repair Completed!</h4>
                    <p>Your watch repair has been completed successfully.</p>
                    <a href="generate_receipt.php?id=<?= $repair_id ?>&client=true" 
                       class="btn btn-light btn-lg" target="_blank">
                       <i class="fas fa-download me-2"></i>Download Receipt
                    </a>
                    <p class="mt-2 mb-0"><small>You can now pick up your watch</small></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- NAVIGATION BUTTONS -->
        <div class="text-center mt-4">
            <a href="client-dashboard.php?repair_id=<?= $repair_id ?>" class="btn btn-primary">
                <i class="fas fa-sync-alt me-2"></i>Refresh Status
            </a>
            <a href="client-dashboard.php" class="btn btn-outline-secondary">
                <i class="fas fa-search me-2"></i>Check Another Repair
            </a>
            <a href="dashboard.php" class="btn btn-outline-dark">
                <i class="fas fa-cog me-2"></i>Admin Dashboard
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>