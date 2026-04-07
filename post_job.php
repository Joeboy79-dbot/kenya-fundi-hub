<?php
require_once 'config.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'client') {
    header("Location: login.php");
    exit();
}

$client_id = $_SESSION['user_id'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $budget = mysqli_real_escape_string($conn, $_POST['budget']);
    $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
    
    $insert_query = "INSERT INTO jobs (client_id, title, description, category, location, budget, deadline) 
                     VALUES ('$client_id', '$title', '$description', '$category', '$location', '$budget', '$deadline')";
    
    if(mysqli_query($conn, $insert_query)) {
        $success = "Job posted successfully!";
    } else {
        $error = "Error posting job: " . mysqli_error($conn);
    }
}

// Get categories for dropdown
$categories_query = "SELECT * FROM categories ORDER BY category_name";
$categories_result = mysqli_query($conn, $categories_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post a Job - Kenya Fundi Hub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 50px 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h1 { text-align: center; margin-bottom: 20px; color: #2c3e50; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        button { width: 100%; padding: 12px; background: #f39c12; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        button:hover { background: #e67e22; }
        .error { color: red; margin-bottom: 15px; padding: 10px; background: #f8d7da; border-radius: 5px; }
        .success { color: green; margin-bottom: 15px; padding: 10px; background: #d4edda; border-radius: 5px; }
        .back { text-align: center; margin-top: 20px; }
        .back a { color: #f39c12; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Post a Job</h1>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Job Title *</label>
                <input type="text" name="title" placeholder="e.g., Need plumber for bathroom repair" required>
            </div>
            
            <div class="form-group">
                <label>Job Description *</label>
                <textarea name="description" rows="5" placeholder="Describe the work needed in detail..." required></textarea>
            </div>
            
            <div class="form-group">
                <label>Category *</label>
                <select name="category" required>
                    <option value="">Select trade</option>
                    <?php while($cat = mysqli_fetch_assoc($categories_result)): ?>
                        <option value="<?php echo $cat['category_name']; ?>"><?php echo $cat['category_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Location *</label>
                <input type="text" name="location" placeholder="e.g., Nairobi, Westlands" required>
            </div>
            
            <div class="form-group">
                <label>Budget (KES)</label>
                <input type="text" name="budget" placeholder="e.g., 5000 - 10000">
            </div>
            
            <div class="form-group">
                <label>Deadline</label>
                <input type="date" name="deadline">
            </div>
            
            <button type="submit">Post Job</button>
        </form>
        
        <div class="back">
            <a href="client_dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>