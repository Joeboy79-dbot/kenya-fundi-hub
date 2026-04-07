<?php
require_once 'config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$full_name = $_SESSION['full_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kenya Fundi Hub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        .welcome { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .menu-card { background: white; padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-decoration: none; color: #333; transition: transform 0.3s; display: block; }
        .menu-card:hover { transform: translateY(-5px); background: #f39c12; color: white; }
        .password-section { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .password-section h3 { margin-bottom: 15px; color: #2c3e50; }
        input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; margin-bottom: 10px; }
        button { background: #f39c12; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #e67e22; }
        .logout-btn { background: #e74c3c; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px; display: inline-block; text-align: center; }
        .logout-btn:hover { background: #c0392b; }
        .footer { text-align: center; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kenya Fundi Hub</h1>
        <p>Fundi Dashboard</p>
    </div>
    
    <div class="container">
        <div class="welcome">
            <h2>Welcome, <?php echo htmlspecialchars($full_name); ?>!</h2>
            <p>Manage your profile and find jobs near you.</p>
        </div>
        
        <div class="menu">
            <a href="profile_edit.php" class="menu-card">
                <h3>✏️ Edit Profile</h3>
                <p>Update your information, skills, and location</p>
            </a>
            <a href="jobs_available.php" class="menu-card">
                <h3>🔍 Find Jobs</h3>
                <p>Browse available jobs near you</p>
            </a>
            <a href="search.php" class="menu-card">
                <h3>👁️ View Public Profile</h3>
                <p>See how clients see you</p>
            </a>
        </div>
        
        <div class="password-section">
            <h3>Change Password</h3>
            <form method="POST" action="change_password.php">
                <input type="password" name="current_password" placeholder="Current Password" required>
                <input type="password" name="new_password" placeholder="New Password (min 6 characters)" required>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                <button type="submit">Change Password</button>
            </form>
        </div>
        
        <div class="footer">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>