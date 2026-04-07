<?php
require_once 'config.php';

if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Handle application deletion
if(isset($_GET['delete'])) {
    $app_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM applications WHERE id = $app_id");
    header("Location: admin_applications.php");
    exit();
}

$apps_query = "SELECT a.*, u.full_name as fundi_name, j.title as job_title 
               FROM applications a 
               JOIN users u ON a.fundi_id = u.id 
               JOIN jobs j ON a.job_id = j.id 
               ORDER BY a.applied_at DESC";
$apps_result = mysqli_query($conn, $apps_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Applications - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f0f2f5; }
        .header { background: #2c3e50; color: white; padding: 20px; display: flex; justify-content: space-between; align-items: center; }
        .header a { color: white; text-decoration: none; background: #e74c3c; padding: 8px 15px; border-radius: 5px; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        table { width: 100%; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f39c12; color: white; }
        .delete-btn { background: #e74c3c; color: white; padding: 5px 10px; text-decoration: none; border-radius: 3px; font-size: 12px; }
        .back-link { display: inline-block; margin-top: 20px; color: #f39c12; text-decoration: none; }
        .proposal { max-width: 250px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📝 Manage Applications</h1>
        <a href="admin_dashboard.php">← Back to Dashboard</a>
    </div>
    
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fundi</th>
                    <th>Job Title</th>
                    <th>Proposal</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Applied</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($app = mysqli_fetch_assoc($apps_result)): ?>
                    <tr>
                        <td><?php echo $app['id']; ?></td>
                        <td><?php echo htmlspecialchars($app['fundi_name']); ?></td>
                        <td><?php echo htmlspecialchars($app['job_title']); ?></td>
                        <td class="proposal"><?php echo htmlspecialchars(substr($app['proposal'], 0, 50)); ?>...</td>
                        <td><?php echo htmlspecialchars($app['phone']); ?></td>
                        <td><?php echo ucfirst($app['status']); ?></td>
                        <td><?php echo $app['applied_at']; ?></td>
                        <td><a href="?delete=<?php echo $app['id']; ?>" class="delete-btn" onclick="return confirm('Delete this application?')">Delete</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <a href="admin_dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>