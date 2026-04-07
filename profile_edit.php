<?php
require_once 'config.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle photo upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_photo'])) {
    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($ext, $allowed)) {
            $new_filename = time() . "_" . $user_id . "." . $ext;
            $upload_path = "uploads/" . $new_filename;
            
            if(move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                $update_image = "UPDATE fundi_profiles SET profile_image = '$upload_path' WHERE user_id = $user_id";
                mysqli_query($conn, $update_image);
                $success = "Profile photo uploaded successfully!";
            } else {
                $error = "Error uploading file";
            }
        } else {
            $error = "Only JPG, PNG, and GIF files are allowed";
        }
    } else {
        $error = "Please select a file to upload";
    }
}
$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Get current profile data
$profile_query = "SELECT * FROM fundi_profiles WHERE user_id = $user_id";
$profile_result = mysqli_query($conn, $profile_query);
$profile = mysqli_fetch_assoc($profile_result);

// Get categories for dropdown
$categories_query = "SELECT * FROM categories ORDER BY category_name";
$categories_result = mysqli_query($conn, $categories_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $profession = mysqli_real_escape_string($conn, $_POST['profession']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $years_experience = intval($_POST['years_experience']);
    $skills = mysqli_real_escape_string($conn, $_POST['skills']);
    $bio = mysqli_real_escape_string($conn, $_POST['bio']);
    $whatsapp = mysqli_real_escape_string($conn, $_POST['whatsapp']);
    
    $update_query = "UPDATE fundi_profiles SET 
                     profession = '$profession',
                     location = '$location',
                     years_experience = $years_experience,
                     skills = '$skills',
                     bio = '$bio',
                     whatsapp = '$whatsapp'
                     WHERE user_id = $user_id";
    
    if(mysqli_query($conn, $update_query)) {
        $success = "Profile updated successfully!";
        // Refresh profile data
        $profile_result = mysqli_query($conn, $profile_query);
        $profile = mysqli_fetch_assoc($profile_result);
    } else {
        $error = "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Kenya Fundi Hub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; padding: 50px 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #2c3e50; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        textarea { resize: vertical; }
        button { width: 100%; padding: 12px; background: #f39c12; color: white; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; font-weight: bold; }
        button:hover { background: #e67e22; }
        .success { background: #d4edda; color: #155724; padding: 12px; border-radius: 5px; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #721c24; padding: 12px; border-radius: 5px; margin-bottom: 20px; }
        .back-link { text-align: center; margin-top: 20px; }
        .back-link a { color: #f39c12; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kenya Fundi Hub</h1>
        <div class="subtitle">Edit Your Profile</div>
        
        <?php if($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <!-- Photo Upload Section -->
<div style="text-align: center; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
    <?php 
    // Get current profile image
    $profile_query = "SELECT profile_image FROM fundi_profiles WHERE user_id = $user_id";
    $profile_result = mysqli_query($conn, $profile_query);
    $profile_data = mysqli_fetch_assoc($profile_result);
    ?>
    
    <?php if($profile_data && $profile_data['profile_image']): ?>
        <img src="<?php echo $profile_data['profile_image']; ?>" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; margin-bottom: 10px;">
    <?php else: ?>
        <div style="width: 120px; height: 120px; background: #ccc; border-radius: 50%; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center;">No Photo</div>
    <?php endif; ?>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="file" name="profile_image" accept="image/*" style="display: inline-block; width: auto; margin: 10px 0;">
        <button type="submit" name="upload_photo" style="background: #2c3e50; color: white; padding: 8px 20px; border: none; border-radius: 5px; cursor: pointer;">Upload Photo</button>
    </form>
    <?php if($error): ?>
        <p style="color: red; margin-top: 10px;"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if($success): ?>
        <p style="color: green; margin-top: 10px;"><?php echo $success; ?></p>
    <?php endif; ?>
</div>
<div class="form-group">
    <label>Availability Status</label>
    <select name="availability" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
        <option value="available" <?php echo ($profile['availability'] == 'available') ? 'selected' : ''; ?>>✅ Available for work</option>
        <option value="busy" <?php echo ($profile['availability'] == 'busy') ? 'selected' : ''; ?>>⚠️ Currently busy</option>
        <option value="unavailable" <?php echo ($profile['availability'] == 'unavailable') ? 'selected' : ''; ?>>❌ Not available</option>
    </select>
</div>
            <div class="form-group">
                <label>Your Trade/Profession *</label>
                <select name="profession" required>
                    <option value="">Select your trade</option>
                    <?php while($cat = mysqli_fetch_assoc($categories_result)): ?>
                        <option value="<?php echo $cat['category_name']; ?>" <?php echo ($profile['profession'] == $cat['category_name']) ? 'selected' : ''; ?>>
                            <?php echo $cat['category_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Location (e.g., Nairobi, Kisumu, Mombasa) *</label>
                <input type="text" name="location" value="<?php echo htmlspecialchars($profile['location']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Years of Experience</label>
                <input type="number" name="years_experience" value="<?php echo $profile['years_experience']; ?>">
            </div>
            
            <div class="form-group">
                <label>Skills (separate with commas)</label>
                <textarea name="skills" rows="3"><?php echo htmlspecialchars($profile['skills']); ?></textarea>
                <small>Example: Pipe installation, Water heater repair, Bathroom fitting</small>
            </div>
            
            <div class="form-group">
                <label>Bio / About Me</label>
                <textarea name="bio" rows="4"><?php echo htmlspecialchars($profile['bio']); ?></textarea>
                <small>Tell clients about your experience and work ethic</small>
            </div>
            
            <div class="form-group">
                <label>WhatsApp Number</label>
                <input type="tel" name="whatsapp" value="<?php echo htmlspecialchars($profile['whatsapp']); ?>">
                <small>Clients will contact you here</small>
            </div>
            
            <button type="submit">Save Profile</button>
        </form>
        
        <div class="back-link">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</body>
</html>