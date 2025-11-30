<?php
require 'database.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role     = trim($_POST['role']);

    if (!empty($username) && !empty($password)) {

        // Check if user already exists
        $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $check->execute([$username]);

        if ($check->rowCount() > 0) {
            $message = "Username already taken.";
        } else {
            // hash password
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // insert user
            $stmt = $conn->prepare("INSERT INTO users(username, password, role) VALUES(?,?,?)");
            $stmt->execute([$username, $hashed, $role]);

            $message = "Account created successfully!";
        }
    } else {
        $message = "Please fill out all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Account</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

<style>
body {
    background: linear-gradient(135deg, #1a1a1a, #2b2b2b, #3a3a3a);
    background-size: 400% 400%;
    animation: metallic 10s ease infinite;
    font-family: 'Noto Sans', sans-serif;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #eaeaea;
}

@keyframes metallic {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.box {
    background: rgba(255,255,255,0.04);
    box-shadow: 0 0 20px rgba(0,0,0,0.4);
    border: 1px solid rgba(255,255,255,0.08);
    backdrop-filter: blur(6px);
    border-radius: 12px;
    padding: 30px;
    width: 400px;
}

.btn-metal {
    background: linear-gradient(135deg, #cfcfcf, #9e9e9e);
    border: none;
    color: black;
    font-weight: bold;
    transition: 0.3s;
}
.btn-metal:hover {
    background: linear-gradient(135deg, #ffffff, #b8b8b8);
}

.form-control {
    background: #1f1f1f;
    border: none;
    color: white;
}
.form-control:focus {
    background: #2a2a2a;
    color: white;
}
</style>
</head>
<body>

<div class="box">
    <h3 class="text-center mb-3">Create Account</h3>

    <?php if ($message !== ""): ?>
        <div class="alert alert-info text-center">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label>Username</label>
        <input type="text" class="form-control mb-2" name="username" required>

        <label>Password</label>
        <input type="password" class="form-control mb-2" name="password" required>

       

        <button class="btn btn-metal btn-block">Register</button>

        <p class="text-center mt-3">
            Already have an account? <a href="admin-login.php" style="color:#cfcfcf;">Login</a>
        </p>
    </form>
</div>

</body>
</html>
