<?php
session_start();
require 'database.php';

// Redirect if already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: " . ($_SESSION['user_type'] === 'admin' ? 'dashboard.php' : 'client-dashboard.php'));
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $user_type = $_POST['user_type']; // 'admin' or 'client'

    if ($user_type === 'admin') {
        // ADMIN LOGIN
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = 'admin';
            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "Invalid admin credentials.";
        }
    } else {
        // CLIENT LOGIN
        $stmt = $conn->prepare("SELECT * FROM repair_requests WHERE email = ?");
        $stmt->execute([$username]); // Using email as username for clients
        $client = $stmt->fetch();

        if ($client) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_type'] = 'client';
            $_SESSION['client_email'] = $client['email'];
            $_SESSION['client_name'] = $client['full_name'];
            header("Location: client-dashboard.php");
            exit;
        } else {
            $error_message = "Email not found in our repair records.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Zaheer Watch Repair - Login</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<link href="https://fonts.cdnfonts.com/css/noto-sans" rel="stylesheet">

<style>
body {
    margin: 0;
    padding: 0;
    font-family: 'Noto Sans', sans-serif;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #eaeaea;
    
    /* BACKGROUND IMAGE WITH OVERLAY */
    background: 
        linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
        url('backg.jpg') center/cover no-repeat fixed;
    
    /* Alternative watch repair background images - choose one: */
    /* 
    background: 
        linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
        url('https://images.unsplash.com/photo-1508057190964-aa1357d13c9a?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover no-repeat fixed;
    */
    
    /* 
    background: 
        linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
        url('https://images.unsplash.com/photo-1523170335258-f5ed11844a49?ixlib=rb-4.0.3&auto=format&fit=crop&w=2080&q=80') center/cover no-repeat fixed;
    */
}

.login-box {
    background: rgba(255,255,255,0.08);
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.1);
    backdrop-filter: blur(12px);
    border-radius: 16px;
    padding: 40px;
    width: 420px;
    animation: fadeIn 0.8s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

.btn-metal {
    background: linear-gradient(135deg, #cfcfcf, #9e9e9e);
    border: none;
    color: black;
    font-weight: bold;
    transition: all 0.3s ease;
    padding: 12px;
    border-radius: 8px;
}
.btn-metal:hover {
    background: linear-gradient(135deg, #ffffff, #b8b8b8);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.form-control {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: white;
    padding: 12px 15px;
    border-radius: 8px;
    transition: all 0.3s ease;
}
.form-control:focus {
    background: rgba(255,255,255,0.15);
    color: white;
    box-shadow: 0 0 0 2px rgba(207, 207, 207, 0.3);
    border-color: rgba(207, 207, 207, 0.5);
}
.form-control::placeholder {
    color: rgba(255,255,255,0.6);
}

.user-type-btn {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.8);
    transition: all 0.3s ease;
    padding: 12px;
    cursor: pointer;
    border-radius: 8px;
}
.user-type-btn.active {
    background: linear-gradient(135deg, #cfcfcf, #9e9e9e);
    color: black;
    border-color: rgba(207, 207, 207, 0.5);
    font-weight: bold;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
}
.user-type-btn:hover:not(.active) {
    background: rgba(255,255,255,0.15);
    color: white;
}

.alert-danger {
    background: rgba(220, 53, 69, 0.9);
    border: none;
    border-radius: 8px;
    color: white;
    backdrop-filter: blur(10px);
}

/* Logo styling */
.brand-logo {
    text-align: center;
    margin-bottom: 10px;
}
.brand-logo i {
    font-size: 2.5rem;
    background: linear-gradient(135deg, #cfcfcf, #9e9e9e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-bottom: 10px;
}

/* Responsive design */
@media (max-width: 480px) {
    .login-box {
        width: 90%;
        margin: 20px;
        padding: 30px 25px;
    }
    
    body {
        background-attachment: scroll;
    }
}
</style>

</head>
<body>

<div class="login-box">
    
    <h3 class="text-center mb-2" style="color: white;">ZAHEER WATCH REPAIR</h3>
    <p class="text-center mb-4" style="color: rgba(255,255,255,0.7);">Login to your account</p>

    <?php if ($error_message): ?>
        <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST" action="" id="loginForm">
        <!-- User Type Selection -->
        <div class="form-group text-center mb-4">
            <div class="btn-group w-100" role="group">
                <button type="button" class="btn user-type-btn active" data-type="client">
                    <i class="fas fa-user me-2"></i>Client Login
                </button>
                <button type="button" class="btn user-type-btn" data-type="admin">
                    <i class="fas fa-cog me-2"></i>Admin Login
                </button>
            </div>
            <input type="hidden" name="user_type" id="user_type" value="client">
        </div>

        <!-- Username/Email Field -->
        <div class="form-group">
            <label id="username-label" style="color: white; font-weight: 500;">Email Address</label>
            <input type="text" name="username" class="form-control" required autofocus 
                   placeholder="Enter your email" id="username-input">
        </div>

        <!-- Password Field (shown only for admin) -->
        <div class="form-group" id="password-field" style="display: none;">
            <label style="color: white; font-weight: 500;">Password</label>
            <input type="password" name="password" class="form-control" 
                   placeholder="Enter your password" id="password-input">
        </div>

        <button type="submit" class="btn btn-metal btn-block mt-4">
            <i class="fas fa-sign-in-alt me-2"></i>Login
        </button>

        <div class="text-center mt-4">
            <small style="color: rgba(255,255,255,0.6);" id="login-info">
                Enter the email you used for repair request
            </small>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script>
$(document).ready(function() {
    // User type selection
    $('.user-type-btn').click(function() {
        $('.user-type-btn').removeClass('active');
        $(this).addClass('active');
        
        var userType = $(this).data('type');
        $('#user_type').val(userType);
        
        if (userType === 'admin') {
            $('#username-label').text('Username');
            $('#username-input').attr('placeholder', 'Enter your username');
            $('#password-field').show();
            $('#password-input').prop('required', true);
            $('#login-info').text('Enter your admin credentials');
        } else {
            $('#username-label').text('Email Address');
            $('#username-input').attr('placeholder', 'Enter your email');
            $('#password-field').hide();
            $('#password-input').prop('required', false);
            $('#login-info').text('Enter the email you used for repair request');
        }
    });

    // Form submission
    $('#loginForm').submit(function(e) {
        var userType = $('#user_type').val();
        var username = $('#username-input').val().trim();
        
        if (!username) {
            alert('Please enter your ' + (userType === 'admin' ? 'username' : 'email'));
            e.preventDefault();
            return false;
        }
        
        if (userType === 'admin' && !$('#password-input').val().trim()) {
            alert('Please enter your password');
            e.preventDefault();
            return false;
        }
        
        return true;
    });
});
</script>

</body>
</html>