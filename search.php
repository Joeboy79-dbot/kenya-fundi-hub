<?php
require_once 'config.php';

// Get search parameters
$search_trade = isset($_GET['trade']) ? mysqli_real_escape_string($conn, $_GET['trade']) : '';
$search_location = isset($_GET['location']) ? mysqli_real_escape_string($conn, $_GET['location']) : '';

// Build the query
$query = "SELECT u.id, u.full_name, u.phone, 
                 fp.profession, fp.location, fp.years_experience, 
                 fp.skills, fp.bio, fp.profile_image, fp.availability,
                 fp.phone_public, fp.whatsapp
          FROM users u 
          JOIN fundi_profiles fp ON u.id = fp.user_id 
          WHERE u.user_type = 'fundi'";

if(!empty($search_trade)) {
    $query .= " AND fp.profession = '$search_trade'";
}

if(!empty($search_location)) {
    $query .= " AND fp.location LIKE '%$search_location%'";
}

$query .= " ORDER BY fp.created_at DESC";
$result = mysqli_query($conn, $query);

// Get all trades for dropdown
$trades_query = "SELECT DISTINCT profession FROM fundi_profiles WHERE profession != '' ORDER BY profession";
$trades_result = mysqli_query($conn, $trades_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Fundis - Kenya Fundi Hub</title>
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
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
        .search-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .search-form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .search-form select, .search-form input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .search-form button {
            padding: 12px 25px;
            background: #f39c12;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .search-form button:hover {
            background: #e67e22;
        }
        
        .results-count {
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .fundi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }
        
        .fundi-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .fundi-card:hover {
            transform: translateY(-5px);
        }
        
        .fundi-card h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .trade {
            display: inline-block;
            background: #f39c12;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            margin-bottom: 10px;
        }
        
        .location {
            color: #666;
            margin-bottom: 10px;
        }
        
        .experience {
            color: #666;
            margin-bottom: 10px;
        }
        
        .skills {
            margin: 10px 0;
            font-size: 14px;
            color: #555;
        }
        
        .availability {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            margin-bottom: 15px;
        }
        
        .available { background: #d4edda; color: #155724; }
        .busy { background: #f8d7da; color: #721c24; }
        
        .contact-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn-call, .btn-whatsapp, .btn-profile {
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            flex: 1;
        }
        
        .btn-call {
            background: #2c3e50;
            color: white;
        }
        
        .btn-whatsapp {
            background: #25D366;
            color: white;
        }
        
        .btn-profile {
            background: #f39c12;
            color: white;
        }
        
        .no-results {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 10px;
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
        <div class="search-box">
            <form method="GET" action="search.php" class="search-form">
                <select name="trade">
                    <option value="">All Trades</option>
                    <?php while($trade = mysqli_fetch_assoc($trades_result)): ?>
                        <option value="<?php echo $trade['profession']; ?>" <?php echo ($search_trade == $trade['profession']) ? 'selected' : ''; ?>>
                            <?php echo $trade['profession']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                
                <input type="text" name="location" placeholder="Enter location (e.g., Nairobi, Mombasa, Kisumu)" value="<?php echo htmlspecialchars($search_location); ?>">
                
                <button type="submit">Search Fundis</button>
            </form>
        </div>
        
        <div class="results-count">
            Found <?php echo mysqli_num_rows($result); ?> fundi(s)
        </div>
        
        <div class="fundi-grid">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($fundi = mysqli_fetch_assoc($result)): ?>
                    <div class="fundi-card">
                        <h3><?php echo htmlspecialchars($fundi['full_name']); ?></h3>
                        <div class="trade"><?php echo htmlspecialchars($fundi['profession']); ?></div>
                        <div class="location">📍 <?php echo htmlspecialchars($fundi['location']); ?></div>
                        <div class="experience">📅 Experience: <?php echo $fundi['years_experience'] ? $fundi['years_experience'] . ' years' : 'Not specified'; ?></div>
                        
                        <?php if($fundi['skills']): ?>
                            <div class="skills">🔧 Skills: <?php echo htmlspecialchars(substr($fundi['skills'], 0, 80)); ?>...</div>
                        <?php endif; ?>
                        
                        <div class="availability <?php echo $fundi['availability'] == 'available' ? 'available' : 'busy'; ?>">
                            <?php echo ucfirst($fundi['availability']); ?>
                        </div>
                        
                        <?php 
                        $contact_phone = $fundi['phone_public'] ? $fundi['phone_public'] : $fundi['phone'];
                        ?>
                        <div class="contact-buttons">
                            <a href="tel:<?php echo $contact_phone; ?>" class="btn-call">📞 Call</a>
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $fundi['whatsapp'] ? $fundi['whatsapp'] : $contact_phone); ?>" target="_blank" class="btn-whatsapp">💬 WhatsApp</a>
                            <a href="profile.php?id=<?php echo $fundi['id']; ?>" class="btn-profile">View Profile</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-results">
                    <h3>No fundis found</h3>
                    <p>Try a different trade or location</p>
                    <p style="margin-top: 20px;">Are you a fundi? <a href="register.php">Register here</a> to get hired!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; 2026 Kenya Fundi Hub - Connecting Kenyans to skilled labourers</p>
    </div>
</body>
</html>