<?php
require_once 'DbConnection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$error = '';
$success = '';

//Fetch current user data
try {
    $stmt = $conn->prepare("SELECT NameUser, EmailUser, Photo_profile FROM user WHERE Iduser = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();
} catch (Exception $e) {
    $error = "Error fetching user data: " . $e->getMessage();
}

//Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $currentImage = $_POST['current_image'] ?? 'default.png';

    try {
        // Validate inputs
        if (empty($name) || empty($email)) {
            throw new Exception("Name and email are required");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Handle file upload if a new image was provided
        $newImageName = $currentImage;
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
            // Validate file
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            
            if (!in_array($_FILES['profile_image']['type'], $allowedTypes)) {
                throw new Exception("Only JPG, PNG, and GIF files are allowed");
            }
            
            if ($_FILES['profile_image']['size'] > $maxSize) {
                throw new Exception("File size must be less than 2MB");
            }

            // Create directory if it doesn't exist
            if (!file_exists('profile_photos')) {
                mkdir('profile_photos', 0755, true);
            }

            // Generate unique filename
            $extension = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $newImageName = 'user_' . $userId . '_' . time() . '.' . $extension;
            $destination = 'profile_photos/' . $newImageName;

            // Move uploaded file
            if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $destination)) {
                throw new Exception("Failed to save uploaded file");
            }

            // Delete old image if it's not the default
            if ($currentImage !== 'default.png' && file_exists('profile_photos/' . $currentImage)) {
                unlink('profile_photos/' . $currentImage);
            }
        }

        // Update database
        $stmt = $conn->prepare("UPDATE user SET NameUser = ?, EmailUser = ?, Photo_profile = ? WHERE Iduser = ?");
        $stmt->bind_param("sssi", $name, $email, $newImageName, $userId);
        
        if ($stmt->execute()) {
            // Update session data
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['profile_image'] = $newImageName;
            $_SESSION['profile_image_timestamp'] = time(); // For cache busting
            
            $_SESSION['update_success'] = "Profile updated successfully!";
            $stmt->close();
            header("Location: Profile.php?updated=".time()); // Force refresh
            exit();
        } else {
            throw new Exception("Database update failed");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Manga</title>
    <link rel="stylesheet" href="Profile.css">
    <style>
        /* Edit Profile Specific Styles */
        .edit-profile-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background-color: #1a1a2e;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .edit-profile-title {
            color: red;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
        }

        .edit-profile-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .profile-image-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .current-image-container {
            position: relative;
            width: 200px;
            height: 200px;
            margin-bottom: 20px;
        }

        .current-image {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid red;
        }

        .file-upload-wrapper {
            position: relative;
            margin-bottom: 20px;
            width: 100%;
        }

        .file-upload-label {
            display: block;
            padding: 10px 15px;
            background-color: red;
            color: white;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload-label:hover {
            background-color: blue;
            transform: translateY(-2px);
        }

        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .form-section {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #e94560;
            font-weight: bold;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            background-color: #16213e;
            border: 1px solid #444;
            border-radius: 5px;
            color: white;
            font-size: 1rem;
        }

        .form-input:focus {
            outline: none;
            border-color: #e94560;
            box-shadow: 0 0 0 2px rgba(233, 69, 96, 0.3);
        }

        .form-actions {
            grid-column: span 2;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 20px;
        }

        .cancel-btn {
            background-color: #444;
            color: white;
        }

        .save-btn {
            background-color: red;
            color: white;
        }

        .error-message {
            color: #ff6b6b;
            padding: 10px;
            background-color: rgba(255, 107, 107, 0.1);
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .success-message {
            color: #51cf66;
            padding: 10px;
            background-color: rgba(81, 207, 102, 0.1);
            border-radius: 5px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .edit-profile-form {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body class="body">
    <nav class="Nav">
        <h2 onclick="navigateTo('homePage.html')">Manga</h2>
        <ul class="ul link-offset">
            <li onclick="navigateTo('homePage.html')">Home</li>
            <li onclick="navigateTo('Profile.php')">Back to Profile</li>
        </ul>
    </nav>

    <div class="container">
        <div class="edit-profile-container">
            <h1 class="edit-profile-title">Edit Profile</h1>
            
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success-message"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>

            <form class="edit-profile-form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($userData['Photo_profile'] ?? 'default.png'); ?>">
                
                <div class="profile-image-section">
                    <div class="current-image-container">
                        <img src="profile_photos/<?php echo htmlspecialchars($userData['Photo_profile'] ?? 'default.png'); ?>?<?php echo time(); ?>" 
                             class="current-image"
                             onerror="this.src='profile_photos/default.png?<?php echo time(); ?>'">
                    </div>
                    
                    <div class="file-upload-wrapper">
                        <label class="file-upload-label">
                            Change Profile Image
                            <input type="file" class="file-upload-input" id="profile_image" name="profile_image" 
                                   accept="image/jpeg, image/png, image/gif">
                        </label>
                    </div>
                </div>
                
                <div class="form-section">
                    <div class="form-group">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" id="name" name="name" class="form-input" 
                               value="<?php echo htmlspecialchars($userData['NameUser'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-input" 
                               value="<?php echo htmlspecialchars($userData['EmailUser'] ?? ''); ?>" required>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="button1 cancel-btn" onclick="window.location.href='Profile.php'">Cancel</button>
                    <button type="submit" class="button1 save-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function navigateTo(page) {
            window.location.href = page;
        }
        
        // Preview selected image before upload
        document.getElementById('profile_image').addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.querySelector('.current-image').src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>

</html>

