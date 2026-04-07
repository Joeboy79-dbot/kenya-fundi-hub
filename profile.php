<?php
require_once 'config.php';

$fundi_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "SELECT u.id, u.full_name, u.phone, 
                 fp.profession, fp.location, fp.years_experience, 
                 fp.skills, fp.bio, fp.profile_image, fp.availability,
                 fp.phone_public, fp.whatsapp
          FROM users u 
          JOIN fundi_profiles fp ON u.id = fp.user_id 
          WHERE u.id = $fundi_id AND u.user_type = 'fundi'";

$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    header("Location: search.php");
    exit();
}

$fundi = mysqli_fetch_assoc($result);
$contact_phone = $fundi['phone_public'] ? $fundi['phone_public'] : $fundi['phone'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($fundi['full_name']); ?> - Kenya Fundi Hub</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; }
        
        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .header a {
            color: white;
            text-decoration: none;
        }
        
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 0 20px;
        }
        
        .profile-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .profile-header {
            background: #f39c12;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .profile-header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .profile-header .trade {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .profile-body {
            padding: 30px;
        }
        
        .info-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .info-section h3 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .info-row {
            margin-bottom: 12px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            width: 120px;
            display: inline-block;
        }
        
        .info-value {
            color: #333;
        }
        
        .contact-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn-call, .btn-whatsapp {
            flex: 1;
            padding: 15px;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
        }
        
        .btn-call {
            background: #2c3e50;
            color: white;
        }
        
        .btn-whatsapp {
            background: #25D366;
            color: white;
        }
        
        .availability {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
        
        .available { background: #d4edda; color: #155724; }
        .busy { background: #f8d7da; color: #721c24; }
        
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        
        .back-link a {
            color: #f39c12;
            text-decoration: none;
        }
        
        .footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container" style="margin: 0 auto;">
            <a href="index.php"><h1>Kenya Fundi Hub</h1></a>
            <p>Find trusted skilled labourers across Kenya</p>
        </div>
    </div>
    
    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <h1><?php echo htmlspecialchars($fundi['full_name']); ?></h1>
                <div class="trade"><?php echo htmlspecialchars($fundi['profession']); ?></div>
            </div>
            
            <div class="profile-body">
                <div class="info-section">
                    <h3>Contact Information</h3>
                    <div class="info-row">
                        <span class="info-label">📍 Location:</span>
                        <span class="info-value"><?php echo htmlspecialchars($fundi['location']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">📞 Phone:</span>
                        <span class="info-value"><?php echo htmlspecialchars($contact_phone); ?></span>
                    </div>
                    <?php if($fundi['whatsapp']): ?>
                    <div class="info-row">
                        <span class="info-label">💬 WhatsApp:</span>
                        <span class="info-value"><?php echo htmlspecialchars($fundi['whatsapp']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="info-section">
                    <h3>Professional Information</h3>
                    <div class="info-row">
                        <span class="info-label">📅 Experience:</span>
                        <span class="info-value"><?php echo $fundi['years_experience'] ? $fundi['years_experience'] . ' years' : 'Not specified'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">🔧 Skills:</span>
                        <span class="info-value"><?php echo nl2br(htmlspecialchars($fundi['skills'])); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">📊 Status:</span>
                        <span class="info-value">
                            <span class="availability <?php echo $fundi['availability'] == 'available' ? 'available' : 'busy'; ?>">
                                <?php echo ucfirst($fundi['availability']); ?>
                            </span>
                        </span>
                    </div>
                </div>
                
                <?php if($fundi['bio']): ?>
                <div class="info-section">
                    <h3>About Me</h3>
                    <p><?php echo nl2br(htmlspecialchars($fundi['bio'])); ?></p>
                </div>
                <?php endif; ?>
                
                <div class="contact-buttons">
                    <a href="tel:<?php echo $contact_phone; ?>" class="btn-call">📞 Call Now</a>
                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $fundi['whatsapp'] ? $fundi['whatsapp'] : $contact_phone); ?>" target="_blank" class="btn-whatsapp">💬 WhatsApp</a>
                </div>
            </div>
        </div>
        
        <div class="back-link">
            <a href="search.php">← Back to Search Results</a>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; 2026 Kenya Fundi Hub - Connecting Kenyans to skilled labourers</p>
    </div>
</body>
</html>