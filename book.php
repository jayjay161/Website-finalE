<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book a Repair - Zaheer Watch Repair</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <link href="https://fonts.cdnfonts.com/css/cairo-play" rel="stylesheet">              
  <link href="https://fonts.cdnfonts.com/css/noto-sans-vai" rel="stylesheet">
  <link href="https://fonts.cdnfonts.com/css/br-sonoma" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.cdnfonts.com/css/konkhmer-sleokchher" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    

</head>
<body>

<?php
// Initialize message variable
$message = '';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database credentials
    $host = 'localhost';
    $username = 'u983768004_joe69';
    $password = 'Iloveconverge69';
    $database = 'u983768004_data_details';

    // Create database connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Collect form data and escape for security
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $watch_brand_model = $conn->real_escape_string($_POST['watch_brand_model']);
    $issue_description = $conn->real_escape_string($_POST['issue_description']);

    // Generate unique tracking number
    $tracking_number = 'ZWR' . date('Ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

    // Handle file upload
    $photo_url = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true); // Create folder if it doesn't exist
        }
        
        // Generate unique filename to avoid conflicts
        $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $filename = $tracking_number . '_' . time() . '.' . $file_extension;
        $photo_url = $upload_dir . $filename;
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo_url);
    }

    // Insert data into database
    $sql = "INSERT INTO repair_requests (full_name, email, phone, watch_brand_model, issue_description, photo_url, tracking_number) 
            VALUES ('$full_name', '$email', '$phone', '$watch_brand_model', '$issue_description', '$photo_url', '$tracking_number')";

    if ($conn->query($sql) === TRUE) {
        // Send confirmation email
        $email_sent = sendConfirmationEmail($full_name, $email, $tracking_number, $watch_brand_model, $issue_description);
        
        $email_status = $email_sent ? "Confirmation email sent to $email." : "Booking successful but email notification failed.";
        
        $message = "<div class='alert alert-success'>
                        <h4><i class='fas fa-check-circle'></i> Repair Request Submitted Successfully!</h4>
                        <p><strong>Tracking Number:</strong> $tracking_number</p>
                        <p><strong>Customer Name:</strong> $full_name</p>
                        <p><strong>Watch:</strong> $watch_brand_model</p>
                        <p>$email_status</p>
                        <p>You can track your repair status using the tracking number above.</p>
                        <a href='track.php?tracking=$tracking_number' class='btn btn-primary btn-sm'>
                            <i class='fas fa-search'></i> Track My Repair Now
                        </a>
                    </div>";
    } else {
        $message = "<div class='alert alert-danger'>
                        <h4><i class='fas fa-exclamation-triangle'></i> Error</h4>
                        <p>There was an error submitting your request. Please try again.</p>
                        <small>Error: " . $conn->error . "</small>
                    </div>";
    }

    $conn->close();
}

// Function to send confirmation email
function sendConfirmationEmail($name, $email, $tracking_number, $watch, $issue) {
    $subject = "Zaheer Watch Repair - Booking Confirmation #$tracking_number";
    
    $message = "
    <html>
    <head>
        <title>Repair Booking Confirmation</title>
        <style>
        background: 
        linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
        url('backg.jpg') center/cover no-repeat fixed;
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #f8f9fa; padding: 20px; text-align: center; }
            .content { padding: 20px; }
            .tracking-box { background: #007bff; color: white; padding: 15px; text-align: center; border-radius: 5px; margin: 20px 0; }
            .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Zaheer Watch Repair</h2>
                <p>Professional Watch Repair Services</p>
            </div>
            
            <div class='content'>
                <h3>Thank you for your booking, $name!</h3>
                
                <div class='tracking-box'>
                    <h4>Your Tracking Number</h4>
                    <h2>$tracking_number</h2>
                </div>
                
                <p><strong>Booking Details:</strong></p>
                <ul>
                    <li><strong>Watch:</strong> $watch</li>
                    <li><strong>Issue:</strong> $issue</li>
                    <li><strong>Booking Date:</strong> " . date('F j, Y') . "</li>
                </ul>
                
                <p>You can track the status of your repair anytime using your tracking number:</p>
                <p style='text-align: center;'>
                    <a href='http://yourwebsite.com/track.php?tracking=$tracking_number' 
                       style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                       Track My Repair Status
                    </a>
                </p>
                
                <p>We will notify you when we start working on your watch and when it's completed.</p>
            </div>
            
            <div class='footer'>
                <p>If you have any questions, please contact us at support@zaheerwatchrepair.com</p>
                <p>&copy; 2025 Zaheer Watch Repair. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Zaheer Watch Repair <noreply@zaheerwatchrepair.com>" . "\r\n";
    
    return mail($email, $subject, $message, $headers);
}
?>

  <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: rgba(0,0,0,0.63);">
    <a class="navbar-brand font-weight-bold" href="main.php">Zaheer Watch Repair</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="service.php">Services</a></li>
        <li class="nav-item active"><a class="nav-link text-center" href="book.php">Book Repair</a></li>
        <li class="nav-item"><a class="nav-link text-center" href="track.php">Track Repair</a></li>
        <li class="nav-item"><a class="nav-link text-center" href="admin-login.php">Login</a></li>
      </ul>
    </div>
  </nav>

  <section id="book" class="pt-5 mt-5">
    <div class="container">
      <h2 class="mb-5 text-center font-weight-bold" style="font-family: 'BR Sonoma', sans-serif;">Book a Repair</h2>

      <?php echo $message; ?>

      <form class="mx-auto" style="max-width: 600px;" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label>Full Name *</label>
          <input type="text" class="form-control" name="full_name" placeholder="Enter your full name" required>
        </div>
      
        <div class="form-group">
          <label>Email Address *</label>
          <input type="email" class="form-control" name="email" placeholder="Enter your email address" required>
        </div>

        <div class="form-group">
          <label>Phone Number</label>
          <input type="tel" class="form-control" name="phone" placeholder="Enter your phone number (optional)">
        </div>
      
        <div class="form-group">
          <label>Watch Brand & Model *</label>
          <input type="text" class="form-control" name="watch_brand_model" placeholder="e.g., Rolex Submariner, Seiko 5, etc." required>
        </div>
      
        <div class="form-group">
          <label>Issue Description *</label>
          <textarea class="form-control" name="issue_description" rows="4" placeholder="Please describe the problem with your watch in detail..." required></textarea>
        </div>
      
        <div class="form-group">
          <label>Upload Photo of Watch</label>
          <input type="file" class="form-control-file" name="photo" accept="image/*">
          <small class="form-text text-muted">Upload a clear photo of your watch (optional but recommended)</small>
        </div>
        
        <button type="submit" class="btn btn-gold btn-block">
          <i class="fas fa-paper-plane"></i> Submit Repair Request
        </button>
        
        <div class="mt-3 text-center">
          <small class="text-muted">
            After submitting, you will receive a tracking number to monitor your repair status.
          </small>
        </div>
      </form>
    </div>
  </section>

  <footer class="mt-5">
    <div class="container text-center">
      <p>&copy; 2025 Zaheer. All rights reserved.</p>
    </div>
  </footer>

</body>
</html>