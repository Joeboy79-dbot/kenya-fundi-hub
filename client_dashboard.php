<?php
require_once 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'client') {
    header("Location: login.php");
    exit();
}

$full_name = $_SESSION['full_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Dashboard - Kenya Fundi Hub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        .welcome { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .menu-card { background: white; padding: 25px; border-radius: 10px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-decoration: none; color: #333; transition: transform 0.3s; display: block; }
        .menu-card:hover { transform: translateY(-5px); background: #f39c12; color: white; }
        .logout-btn { background: #e74c3c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kenya Fundi Hub</h1>
        <p>Client Dashboard</p>
    </div>
    
    <div class="container">
        <div class="welcome">
            <h2>Welcome, <?php echo htmlspecialchars($full_name); ?>!</h2>
            <p>Post a job and find trusted fundis near you.</p>
        </div>
        
        <div class="menu">
            <a href="post_job.php" class="menu-card">
                <h3>📝 Post a Job</h3>
                <p>Describe the work you need done</p>
            </a>
            <a href="my_jobs.php" class="menu-card">
                <h3>📋 My Jobs</h3>
                <p>View all your posted jobs</p>
            </a>
            <a href="search.php" class="menu-card">
                <h3>🔍 Find Fundis</h3>
                <p>Search for skilled labourers</p>
            </a>
        </div>
        
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</body>
</html>