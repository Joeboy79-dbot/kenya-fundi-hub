<?php
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Get current password from database
    $query = "SELECT password FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    
    if(!password_verify($current_password, $user['password'])) {
        $error = "Current password is incorrect";
    } elseif($new_password != $confirm_password) {
        $error = "New passwords do not match";
    } elseif(strlen($new_password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_query = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";
        
        if(mysqli_query($conn, $update_query)) {
            $success = "Password changed successfully!";
        } else {
            $error = "Error changing password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password - Kenya Fundi Hub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 50px; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h1 { text-align: center; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 12px; background: #f39c12; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .error { color: red; margin-bottom: 15px; }
        .success { color: green; margin-bottom: 15px; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #f39c12; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Change Password</h1>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <input type="password" name="current_password" placeholder="Current Password" required>
            </div>
            <div class="form-group">
                <input type="password" name="new_password" placeholder="New Password (min 6 characters)" required>
            </div>
            <div class="form-group">
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
            </div>
            <button type="submit">Change Password</button>
        </form>
        
        <div class="back">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>