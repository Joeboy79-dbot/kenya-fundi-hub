<?php
require_once 'config.php';

if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Handle user deletion
if(isset($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
    header("Location: admin_users.php");
    exit();
}

$users_query = "SELECT * FROM users ORDER BY created_at DESC";
$users_result = mysqli_query($conn, $users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>👥 Manage Users</h1>
        <a href="admin_dashboard.php">← Back to Dashboard</a>
    </div>
    
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>User Type</th>
                    <th>Registered</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = mysqli_fetch_assoc($users_result)): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><?php echo ucfirst($user['user_type']); ?></td>
                        <td><?php echo $user['created_at']; ?></td>
                        <td><a href="?delete=<?php echo $user['id']; ?>" class="delete-btn" onclick="return confirm('Delete this user?')">Delete</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <a href="admin_dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>