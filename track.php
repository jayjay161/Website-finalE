<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Track Repair - Zaheer Watch Repair</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: rgba(0,0,0,0.63);">
    <a class="navbar-brand font-weight-bold" href="main.php">Zaheer Watch Repair</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">

        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>

        <li class="nav-item"><a class="nav-link" href="service.php">Services</a></li>

        <li class="nav-item"><a class="nav-link text-center" href="book.php">Book Repair</a></li>

        <li class="nav-item active"><a class="nav-link text-center" href="track.php">Track Repair</a></li>
        
        <li class="nav-item"><a class="nav-link text-center" href="admin-login.php">Login</a></li>

      </ul>
    </div>
  </nav>

  <!-- TRACK FORM -->
  <section id="track" class="pt-5 mt-5 text-center">
    <div class="container">
      <h2 class="mb-5 font-weight-bold">Track Your Repair</h2>

      <form id="tracking-form" class="mx-auto" style="max-width: 400px;">
        <div class="form-group">
          <input type="text" id="tracking-number" class="form-control" placeholder="Enter Tracking Number" required>
        </div>

        <button type="submit" class="btn btn-gold btn-block">Check Status</button>
      </form>

      <!-- Loading Spinner -->
      <div id="loading" class="mt-3" style="display: none;">
        <div class="spinner-border text-primary" role="status">
          <span class="sr-only">Loading...</span>
        </div>
        <p class="mt-2">Searching for your repair...</p>
      </div>

      <!-- Results will appear here -->
      <div id="tracking-result" class="mt-4"></div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer class="mt-5">
    <div class="container text-center">
      <p>&copy; 2025 Zaheer. All rights reserved.</p>
    </div>
  </footer>

  <script>
  $(document).ready(function() {
    // Handle form submission with AJAX
    $('#tracking-form').submit(function(e) {
      e.preventDefault();
      
      var trackingNumber = $('#tracking-number').val().trim();
      
      if (trackingNumber === '') {
        $('#tracking-result').html('<div class="alert alert-warning">Please enter a tracking number</div>');
        return;
      }

      // Show loading
      $('#loading').show();
      $('#tracking-result').html('');

      // AJAX request
      $.ajax({
        url: 'ajax_track_status.php',
        method: 'POST',
        data: { tracking_number: trackingNumber },
        dataType: 'json',
        success: function(response) {
          $('#loading').hide();
          
          if (response.success) {
            displayTrackingInfo(response.data);
          } else {
            $('#tracking-result').html(
              '<div class="alert alert-danger">' + response.message + '</div>'
            );
          }
        },
        error: function() {
          $('#loading').hide();
          $('#tracking-result').html(
            '<div class="alert alert-danger">Error connecting to server. Please try again.</div>'
          );
        }
      });
    });

    // Function to display tracking information
    function displayTrackingInfo(data) {
      var html = `
        <div class="card mx-auto" style="max-width: 600px;">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Repair Tracking: ${data.tracking_number}</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <h5>Customer Information</h5>
                <p><strong>Name:</strong> ${data.full_name}</p>
                <p><strong>Email:</strong> ${data.email}</p>
                <p><strong>Phone:</strong> ${data.phone || 'Not provided'}</p>
              </div>
              <div class="col-md-6">
                <h5>Watch Details</h5>
                <p><strong>Watch:</strong> ${data.watch_brand_model}</p>
                <p><strong>Issue:</strong> ${data.issue_description}</p>
                ${data.repair_cost > 0 ? `<p><strong>Repair Cost:</strong> ₱${parseFloat(data.repair_cost).toFixed(2)}</p>` : ''}
              </div>
            </div>
            
            <div class="mt-3 p-3 bg-light rounded">
              <h5 class="text-center">Current Status</h5>
              <h4 class="text-center text-primary">${data.status}</h4>
              <p class="text-center mb-0"><small>Last updated: ${data.updated_at}</small></p>
            </div>
          </div>
        </div>
      `;
      
      $('#tracking-result').html(html);
    }

    // Check if there's a tracking number in URL
    const urlParams = new URLSearchParams(window.location.search);
    const trackingFromUrl = urlParams.get('tracking');
    if (trackingFromUrl) {
      $('#tracking-number').val(trackingFromUrl);
      $('#tracking-form').submit();
    }
  });
  </script>

</body>
</html>