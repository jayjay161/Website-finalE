<?php
session_start();

// Redirect if NOT logged in
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

// Fetch repair requests
$sql = "SELECT * FROM repair_requests ORDER BY id DESC";
$result = $conn->query($sql);

// STATISTICS CALCULATION
$total_requests = $result->num_rows;
$pending_count = 0;
$inprogress_count = 0;
$completed_count = 0;
$total_revenue = 0;

// Reset pointer for statistics
$result->data_seek(0);
while ($row = $result->fetch_assoc()) {
    switch ($row['status']) {
        case 'Pending': $pending_count++; break;
        case 'In Progress': $inprogress_count++; break;
        case 'Completed': 
            $completed_count++; 
            $total_revenue += $row['repair_cost'] ?? 0;
            break;
    }
}

// Calculate average repair cost
$average_revenue = $completed_count > 0 ? $total_revenue / $completed_count : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Zaheer Watch Repair</title>
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
        .dashboard-container { background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(15px); border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.2); margin-top: 25px; margin-bottom: 30px; overflow: hidden; border: 1px solid rgba(255,255,255,0.3); }
        .stats-container { background: linear-gradient(135deg, #808080 0%, #606060 50%, #404040 100%); color: white; padding: 30px; border-radius: 18px; margin-bottom: 30px; box-shadow: 0 8px 25px rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.2); }
        .stat-card { background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.05)); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; text-align: center; border: 1px solid rgba(255,255,255,0.3); transition: all 0.3s ease; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
        .stat-card:hover { transform: translateY(-8px); box-shadow: 0 12px 25px rgba(0,0,0,0.3); background: linear-gradient(135deg, rgba(255,255,255,0.2), rgba(255,255,255,0.1)); }
        .stat-number { font-size: 2.8rem; font-weight: bold; margin-bottom: 8px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); color: #ffffff; }
        .stat-label { font-size: 0.95rem; opacity: 0.9; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 600; }
        .table-container { background: linear-gradient(135deg, #ffffff 0%, #f8f8f8 100%); padding: 30px; border-radius: 18px; box-shadow: 0 8px 25px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.5); margin-bottom: 25px; }
        .table { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .thead-dark { background: linear-gradient(45deg, #606060, #808080) !important; }
        .table img { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; transition: 0.3s ease; cursor: pointer; border: 2px solid #e0e0e0; }
        .status-badge { padding: 8px 16px; border-radius: 25px; font-weight: bold; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
        .status-pending { background: linear-gradient(45deg, #ff6b6b, #ee5a52); color: white; }
        .status-in-progress { background: linear-gradient(45deg, #ffa726, #ff9800); color: white; }
        .status-completed { background: linear-gradient(45deg, #66bb6a, #4caf50); color: white; }
        .action-buttons a { width: 120px; margin: 4px auto; display: block; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
        .btn-gold { background: linear-gradient(45deg, #d4af37, #ffd700); color: black; font-weight: bold; border: none; }
        .revenue-stats { background: linear-gradient(135deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%); }
        /* NEW STYLES FOR PRICE INPUT */
        .price-input-form { margin: 5px 0; }
        .price-input-form .input-group { width: 150px; margin: 0 auto; }
        .price-input-form .form-control { font-size: 12px; padding: 4px 8px; height: 30px; }
        .price-input-form .btn { font-size: 12px; padding: 4px 8px; height: 30px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Zaheer Watch Repair - Admin Dashboard</a>
        <div class="navbar-nav ml-auto">
            <a class="nav-link" href="dashboard.php"><i class="fas fa-home me-1"></i> Home</a>
            <a class="nav-link" href="admin-settings.php"><i class="fas fa-cog me-1"></i> Settings</a>
            <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
        </div>
    </div>
</nav>

<div class="container dashboard-container">
    <!-- REVENUE STATISTICS -->
    <div class="stats-container revenue-stats">
        <h3 class="text-center mb-4"><i class="fas fa-chart-line me-2"></i>Revenue & Sales Statistics</h3>
        <div class="row text-center">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-number">₱<?= number_format($total_revenue, 2) ?></div>
                    <div class="stat-label">Total Revenue</div>
                    <i class="fas fa-money-bill-wave mt-3" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-number">₱<?= number_format($average_revenue, 2) ?></div>
                    <div class="stat-label">Average per Repair</div>
                    <i class="fas fa-calculator mt-3" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-number"><?= $completed_count ?></div>
                    <div class="stat-label">Completed Jobs</div>
                    <i class="fas fa-check-circle mt-3" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="stat-number"><?= $total_requests ?></div>
                    <div class="stat-label">Total Requests</div>
                    <i class="fas fa-list-alt mt-3" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- REPAIR STATISTICS -->
    <div class="stats-container">
        <h3 class="text-center mb-4"><i class="fas fa-tools me-2"></i>Repair Status Overview</h3>
        <div class="row text-center">
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-number"><?= $pending_count ?></div>
                    <div class="stat-label">Pending</div>
                    <i class="fas fa-clock mt-3" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-number"><?= $inprogress_count ?></div>
                    <div class="stat-label">In Progress</div>
                    <i class="fas fa-tools mt-3" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-number"><?= $completed_count ?></div>
                    <div class="stat-label">Completed</div>
                    <i class="fas fa-check-circle mt-3" style="font-size: 2.5rem; opacity: 0.9;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TABLE -->
    <div class="table-container">
        <h3 class="text-center mb-4"><i class="fas fa-tools me-2"></i>Customer Repair Requests</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark text-center">
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Watch</th>
                        <th>Description</th>
                        <th>Photo</th>
                        <th>Repair Cost</th>
                        <th>Status</th>
                        <th width="250">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $result->data_seek(0);
                    while ($row = $result->fetch_assoc()):
                    ?>
                    <tr class="text-center">
                        <td><?= htmlspecialchars($row['full_name']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['watch_brand_model']); ?></td>
                        <td><?= htmlspecialchars($row['issue_description']); ?></td>
                        <td>
                            <?php if ($row['photo_url']): ?>
                                <img src="<?= $row['photo_url']; ?>" onclick="window.open('<?= $row['photo_url']; ?>','_blank')">
                            <?php else: ?>
                                <span class="text-muted">No Photo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['repair_cost'] > 0): ?>
                                <strong>₱<?= number_format($row['repair_cost'], 2) ?></strong>
                            <?php else: ?>
                                <span class="text-muted">To be calculated</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge <?= strtolower(str_replace(' ', '-', $row['status'])); ?>">
                                <?= $row['status']; ?>
                            </span>
                        </td>
                        <td class="action-buttons">
                            <!-- STATUS BUTTONS -->
                            <a href="update_status.php?id=<?= $row['id']; ?>&status=Pending" class="btn btn-secondary btn-sm">Pending</a>
                            <a href="update_status.php?id=<?= $row['id']; ?>&status=In Progress" class="btn btn-warning btn-sm">In Progress</a>
                            <a href="update_status.php?id=<?= $row['id']; ?>&status=Completed" class="btn btn-success btn-sm">Completed</a>
                            
                            <!-- PRICE INPUT FORM (NEW ADDITION) -->
                            <form method="POST" action="update_price.php" class="price-input-form">
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <div class="input-group input-group-sm">
                                    <input type="number" name="repair_cost" class="form-control" 
                                           value="<?= $row['repair_cost'] > 0 ? $row['repair_cost'] : '' ?>" 
                                           placeholder="Price" step="0.01" min="0">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="submit">Set</button>
                                    </div>
                                </div>
                            </form>
                            
                            <!-- EXISTING BUTTONS -->
                            <?php if($row['status']=='Completed'): ?>
                                <a href="generate_receipt.php?id=<?= $row['id']; ?>" class="btn btn-gold btn-sm" target="_blank">
                                    <i class="fas fa-receipt me-1"></i>Receipt
                                </a>
                            <?php endif; ?>
                            <a href="delete_request.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this request?');">
                                <i class="fas fa-trash me-1"></i>Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
setTimeout(function(){ location.reload(); }, 30000);
</script>

</body>
</html>

<?php $conn->close(); ?>

