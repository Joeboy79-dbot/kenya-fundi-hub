<?php
require_once 'config.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Get statistics
$total_fundis = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE user_type = 'fundi'"))['count'];
$total_clients = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM users WHERE user_type = 'client'"))['count'];
$total_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM jobs"))['count'];
$total_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM applications"))['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Kenya Fundi Hub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; }
        .header { background: #2c3e50; color: white; padding: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; }
        .header h1 { font-size: 24px; }
        .header a { color: white; text-decoration: none; background: #e74c3c; padding: 8px 15px; border-radius: 5px; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .stat-card h3 { font-size: 36px; color: #f39c12; margin-bottom: 10px; }
        .stat-card p { color: #666; font-size: 16px; }
        .menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .menu-card { background: white; padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-decoration: none; color: #333; transition: transform 0.3s; display: block; }
        .menu-card:hover { transform: translateY(-5px); background: #2c3e50; color: white; }
        .logout-btn { background: #e74c3c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔧 Kenya Fundi Hub - Admin Dashboard</h1>
        <a href="admin_logout.php">Logout</a>
    </div>
    
    <div class="container">
        <div class="stats">
            <div class="stat-card">
                <h3><?php echo $total_fundis; ?></h3>
                <p>Total Fundis</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $total_clients; ?></h3>
                <p>Total Clients</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $total_jobs; ?></h3>
                <p>Jobs Posted</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $total_applications; ?></h3>
                <p>Applications</p>
            </div>
        </div>
        
        <div class="menu">
            <a href="admin_users.php" class="menu-card">
                <h3>👥 Manage Users</h3>
                <p>View, edit, or delete all fundis and clients</p>
            </a>
            <a href="admin_jobs.php" class="menu-card">
                <h3>📋 Manage Jobs</h3>
                <p>View and delete job postings</p>
            </a>
            <a href="admin_applications.php" class="menu-card">
                <h3>📝 Manage Applications</h3>
                <p>View all job applications</p>
            </a>
        </div>
    </div>
</body>
</html>