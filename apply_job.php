<?php
require_once 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'fundi') {
    header("Location: login.php");
    exit();
}

$fundi_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $job_id = mysqli_real_escape_string($conn, $_POST['job_id']);
    $proposal = mysqli_real_escape_string($conn, $_POST['proposal']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // Check if already applied
    $check_query = "SELECT id FROM applications WHERE job_id = $job_id AND fundi_id = $fundi_id";
    $check_result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($check_result) > 0) {
        $error = "You have already applied for this job.";
    } else {
        $insert_query = "INSERT INTO applications (job_id, fundi_id, proposal, phone) 
                        VALUES ('$job_id', '$fundi_id', '$proposal', '$phone')";
        
        if(mysqli_query($conn, $insert_query)) {
            $success = "Application submitted successfully! The client will contact you if selected.";
        } else {
            $error = "Error submitting application: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Application Status - Kenya Fundi Hub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 50px 20px; }
        .container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center; }
        .success { color: green; margin-bottom: 20px; }
        .error { color: red; margin-bottom: 20px; }
        .btn { background: #f39c12; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <?php if($success): ?>
            <div class="success">
                <h2>✅ <?php echo $success; ?></h2>
            </div>
        <?php elseif($error): ?>
            <div class="error">
                <h2>❌ <?php echo $error; ?></h2>
            </div>
        <?php endif; ?>
        
        <a href="jobs_available.php" class="btn">← Browse More Jobs</a>
        <a href="dashboard.php" class="btn" style="background: #2c3e50;">Go to Dashboard</a>
    </div>
</body>
</html>