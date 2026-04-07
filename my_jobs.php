<?php
require_once 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'client') {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['user_id'];

// Check if jobs table exists, if not create it
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'jobs'");
if(mysqli_num_rows($table_check) == 0) {
    mysqli_query($conn, "CREATE TABLE jobs (
        id INT NOT NULL AUTO_INCREMENT,
        client_id INT NOT NULL,
        title VARCHAR(200) NOT NULL,
        description TEXT NOT NULL,
        category VARCHAR(50),
        location VARCHAR(100),
        budget VARCHAR(50),
        deadline DATE,
        status VARCHAR(20) DEFAULT 'open',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    )");
}

$jobs_query = "SELECT * FROM jobs WHERE client_id = $client_id ORDER BY created_at DESC";
$jobs_result = mysqli_query($conn, $jobs_query);

if(!$jobs_result) {
    die("Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Jobs - Kenya Fundi Hub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        .job-card { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .job-title { font-size: 20px; font-weight: bold; color: #2c3e50; margin-bottom: 10px; }
        .job-detail { margin: 8px 0; color: #666; }
        .status { display: inline-block; padding: 5px 10px; border-radius: 5px; font-size: 12px; }
        .status-open { background: #d4edda; color: #155724; }
        .back-link { text-align: center; margin-top: 30px; }
        .back-link a { color: #f39c12; text-decoration: none; }
        .error-box { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kenya Fundi Hub</h1>
        <p>My Posted Jobs</p>
    </div>
    
    <div class="container">
        <?php if(mysqli_num_rows($jobs_result) > 0): ?>
            <?php while($job = mysqli_fetch_assoc($jobs_result)): ?>
                <div class="job-card">
                    <div class="job-title"><?php echo htmlspecialchars($job['title']); ?></div>
                    <div class="job-detail">📂 Category: <?php echo htmlspecialchars($job['category']); ?></div>
                    <div class="job-detail">📍 Location: <?php echo htmlspecialchars($job['location']); ?></div>
                    <div class="job-detail">💰 Budget: <?php echo htmlspecialchars($job['budget']); ?></div>
                    <div class="job-detail">📅 Posted: <?php echo $job['created_at']; ?></div>
                    <div class="job-detail">
                        Status: <span class="status status-open"><?php echo ucfirst($job['status']); ?></span>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 50px; background: white; border-radius: 10px;">
                <p>You haven't posted any jobs yet.</p>
                <p style="margin-top: 20px;"><a href="post_job.php" style="color: #f39c12;">Post your first job</a></p>
            </div>
        <?php endif; ?>
        
        <div class="back-link">
            <a href="client_dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>