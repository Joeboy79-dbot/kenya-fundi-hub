<?php
require_once 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'fundi') {
    header("Location: login.php");
    exit();
}

// Get all open jobs
$jobs_query = "SELECT * FROM jobs WHERE status = 'open' ORDER BY created_at DESC";
$jobs_result = mysqli_query($conn, $jobs_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Available Jobs - Kenya Fundi Hub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 20px; text-align: center; }
        .container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
        .job-card { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .job-title { font-size: 20px; font-weight: bold; color: #2c3e50; margin-bottom: 10px; }
        .job-detail { margin: 8px 0; color: #666; }
        .apply-btn { background: #f39c12; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin-top: 10px; }
        .apply-btn:hover { background: #e67e22; }
        .no-jobs { text-align: center; padding: 50px; background: white; border-radius: 10px; }
        .back-link { text-align: center; margin-top: 30px; }
        .back-link a { color: #f39c12; text-decoration: none; }
        .status { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 12px; background: #d4edda; color: #155724; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kenya Fundi Hub</h1>
        <p>Available Jobs Near You</p>
    </div>
    
    <div class="container">
        <h2 style="margin-bottom: 20px;">📋 Jobs Available</h2>
        
        <?php if(mysqli_num_rows($jobs_result) > 0): ?>
            <?php while($job = mysqli_fetch_assoc($jobs_result)): ?>
                <div class="job-card">
                    <div class="job-title"><?php echo htmlspecialchars($job['title']); ?></div>
                    <div class="job-detail">📂 Category: <?php echo htmlspecialchars($job['category']); ?></div>
                    <div class="job-detail">📍 Location: <?php echo htmlspecialchars($job['location']); ?></div>
                    <div class="job-detail">💰 Budget: KES <?php echo htmlspecialchars($job['budget']); ?></div>
                    <div class="job-detail">📅 Posted: <?php echo $job['created_at']; ?></div>
                    <div class="job-detail">📋 Status: <span class="status"><?php echo ucfirst($job['status']); ?></span></div>
                    <button class="apply-btn" onclick="showApplyForm(<?php echo $job['id']; ?>, '<?php echo addslashes($job['title']); ?>')">Apply for this Job</button>
                    
                    <div id="applyForm-<?php echo $job['id']; ?>" style="display: none; margin-top: 15px; padding: 15px; background: #f9f9f9; border-radius: 5px;">
                        <h4>Submit Your Application</h4>
                        <form method="POST" action="apply_job.php">
                            <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                            <textarea name="proposal" rows="3" placeholder="Tell the client why you are qualified for this job..." style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px;" required></textarea>
                            <input type="text" name="phone" placeholder="Your phone number" style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px;" required>
                            <button type="submit" style="background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Submit Application</button>
                            <button type="button" onclick="hideApplyForm(<?php echo $job['id']; ?>)" style="background: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Cancel</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-jobs">
                <p>📭 No jobs available at the moment.</p>
                <p>Check back later for new opportunities!</p>
            </div>
        <?php endif; ?>
        
        <div class="back-link">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
    
    <script>
        function showApplyForm(jobId, jobTitle) {
            document.getElementById('applyForm-' + jobId).style.display = 'block';
        }
        
        function hideApplyForm(jobId) {
            document.getElementById('applyForm-' + jobId).style.display = 'none';
        }
    </script>
</body>
</html>