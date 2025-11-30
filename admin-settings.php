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

// Get current pricing
$pricing_sql = "SELECT * FROM pricing_settings WHERE id = 1";
$pricing_result = $conn->query($pricing_sql);
if ($pricing_result->num_rows > 0) {
    $pricing = $pricing_result->fetch_assoc();
} else {
    // Default prices if no settings found
    $pricing = [
        'basic_repair' => 500.00,
        'advanced_repair' => 1200.00,
        'battery_replacement' => 300.00,
        'waterproofing' => 800.00
    ];
}

// Update pricing if form submitted
if (isset($_POST['update_pricing'])) {
    $basic_repair = $_POST['basic_repair'];
    $advanced_repair = $_POST['advanced_repair'];
    $battery_replacement = $_POST['battery_replacement'];
    $waterproofing = $_POST['waterproofing'];
    
    // Check if settings exist
    $check_sql = "SELECT * FROM pricing_settings WHERE id = 1";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        $update_sql = "UPDATE pricing_settings SET 
                        basic_repair = '$basic_repair',
                        advanced_repair = '$advanced_repair', 
                        battery_replacement = '$battery_replacement',
                        waterproofing = '$waterproofing'
                        WHERE id = 1";
    } else {
        $update_sql = "INSERT INTO pricing_settings (id, basic_repair, advanced_repair, battery_replacement, waterproofing) 
                       VALUES (1, '$basic_repair', '$advanced_repair', '$battery_replacement', '$waterproofing')";
    }
    
    if ($conn->query($update_sql)) {
        header("Location: admin-settings.php?success=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing Settings - Zaheer Watch Repair</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            background: linear-gradient(135deg, #e0e0e0 0%, #c0c0c0 50%, #a8a8a8 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        .navbar {
            background: linear-gradient(45deg, #606060, #808080, #a0a0a0) !important;
            border-bottom: 3px solid #c0c0c0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }
        .navbar-brand { font-weight: bold; letter-spacing: 1px; font-size: 1.3rem; color: #ffffff !important; text-shadow: 1px 1px 2px rgba(0,0,0,0.5); }
        .nav-link { color: #f0f0f0 !important; transition: all 0.3s ease; font-weight: 500; }
        .nav-link:hover { color: #ffffff !important; text-shadow: 0 0 10px rgba(255,255,255,0.8); transform: translateY(-1px); }
        .settings-container { 
            background: rgba(255, 255, 255, 0.92); 
            backdrop-filter: blur(15px); 
            border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); 
            margin-top: 25px; 
            margin-bottom: 30px; 
            overflow: hidden; 
            border: 1px solid rgba(255,255,255,0.3); 
            padding: 30px;
        }
        .pricing-card {
            background: linear-gradient(135deg, #808080 0%, #606060 50%, #404040 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .form-control {
            background: rgba(255,255,255,0.9);
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1.1rem;
            font-weight: 500;
        }
        .form-control:focus {
            background: rgba(255,255,255,0.95);
            border-color: #d4af37;
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
        }
        .btn-gold {
            background: linear-gradient(45deg, #d4af37, #ffd700);
            color: black;
            font-weight: bold;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }
        .price-label {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: #f0f0f0;
        }
        .alert-success {
            background: linear-gradient(135deg, #66bb6a, #4caf50);
            color: white;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Zaheer Watch Repair - Pricing Settings</a>
        <div class="navbar-nav ml-auto">
            <a class="nav-link" href="dashboard.php"><i class="fas fa-home me-1"></i> Dashboard</a>
            <a class="nav-link" href="admin-settings.php"><i class="fas fa-cog me-1"></i> Settings</a>
            <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="settings-container">
        <h3 class="text-center mb-4"><i class="fas fa-money-bill-wave me-2"></i>Repair Pricing Settings</h3>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success text-center">
                <i class="fas fa-check-circle me-2"></i>Pricing updated successfully!
            </div>
        <?php endif; ?>
        
        <div class="pricing-card">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="price-label">
                            <i class="fas fa-tools me-2"></i>Basic Repair
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background: #d4af37; color: black; font-weight: bold;">₱</span>
                            </div>
                            <input type="number" name="basic_repair" class="form-control" value="<?= number_format($pricing['basic_repair'], 2) ?>" step="0.01" min="0">
                        </div>
                        <small class="text-light">Cleaning, adjustment, minor fixes</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="price-label">
                            <i class="fas fa-cogs me-2"></i>Advanced Repair
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background: #d4af37; color: black; font-weight: bold;">₱</span>
                            </div>
                            <input type="number" name="advanced_repair" class="form-control" value="<?= number_format($pricing['advanced_repair'], 2) ?>" step="0.01" min="0">
                        </div>
                        <small class="text-light">Movement repair, major components</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="price-label">
                            <i class="fas fa-battery-full me-2"></i>Battery Replacement
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background: #d4af37; color: black; font-weight: bold;">₱</span>
                            </div>
                            <input type="number" name="battery_replacement" class="form-control" value="<?= number_format($pricing['battery_replacement'], 2) ?>" step="0.01" min="0">
                        </div>
                        <small class="text-light">All watch battery types</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="price-label">
                            <i class="fas fa-tint me-2"></i>Waterproofing
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background: #d4af37; color: black; font-weight: bold;">₱</span>
                            </div>
                            <input type="number" name="waterproofing" class="form-control" value="<?= number_format($pricing['waterproofing'], 2) ?>" step="0.01" min="0">
                        </div>
                        <small class="text-light">Water resistance testing & sealing</small>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <button type="submit" name="update_pricing" class="btn btn-gold btn-lg">
                        <i class="fas fa-save me-2"></i>Update Pricing
                    </button>
                </div>
            </form>
        </div>
        
        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>