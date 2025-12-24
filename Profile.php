<?php 
require_once 'DbConnection.php';
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables
$userData = [];
$profileImage = "default.png"; // Default image
$userId = $_SESSION['user_id'];
$error = '';

try {
    // Prepare SQL statement to fetch user data
    $stmt = $conn->prepare("SELECT NameUser, EmailUser, Photo_profile FROM user WHERE Iduser = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        
        // Set profile image if it exists in database
        if (!empty($userData['Photo_profile'])) {
            $profileImage = $userData['Photo_profile'];
        }
    } else {
        $error = "User data not found";
    }
    $stmt->close();
} catch (Exception $e) {
    // Handle error
    error_log("Database error: " . $e->getMessage());
    $error = "Error fetching user data";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manga Viewer</title>
    <link rel="stylesheet" href="Profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card img {
            width: 100%;
            border-radius: 10px;
            max-height: 300px;
            object-fit: cover;
        }

        .card h3 {
            margin-top: 10px;
            font-size: 18px;
        }

        .card p {
            font-size: 14px;
        }
    </style>
</head>

<body class="body">
    <nav class="Nav">
        <h2 onclick="navigateTo('homePage.html')">Manga</h2>
        <ul class="ul link-offset">
            <li onclick="navigateTo('homePage.html')">Home</li>
            <li onclick="navigateTo('Manga.html')">All Manga</li>
            <li onclick="navigateTo('Latest.html')">Latest</li>
            <li onclick="navigateTo('Review.html')">Review</li>
            <li onclick="navigateTo('contact.php')">Contact</li>
        </ul>
        <form action="logout.php" method="post" style="display: inline;">
            <button type="submit" class="button1">Log Out</button>
        </form>
    </nav>

    <!-- Profile Section -->
    <div class="container">
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="main-grid">
            <div>
                <h1>Personal Informations :</h1>
                <table>
                    <tbody>
                        <tr>
                            <td class="label">Name :</td>
                            <td><?php echo htmlspecialchars($userData['NameUser'] ?? 'Not available'); ?></td>
                        </tr>
                        <tr>
                            <td class="label">Email :</td>
                            <td><?php echo htmlspecialchars($userData['EmailUser'] ?? 'Not available'); ?></td>
                        </tr>
                    </tbody>
                </table>
                <button class="edit-btn" onclick="window.location.href='EditProfile.php'">Edit Information</button>
            </div>
            <div class="card2">
    <form method="post" enctype="multipart/form-data" action="EditProfile.php" id="profileForm">
        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($profileImage); ?>">
        <label for="profileUpload">
            <img id="profileImage" src="profile_photos/<?php echo htmlspecialchars($profileImage); ?>" 
                 alt="Profile Picture" class="imagee" 
                 style="cursor: pointer; width: 200px; height: 200px; object-fit: cover;"
                 onerror="this.src='profile_photos/default.png'">
        </label>
        <input type="file" id="profileUpload" name="profile_image" 
               accept="image/jpeg, image/png, image/gif" 
               style="display: none;" 
               onchange="document.getElementById('profileForm').submit()">
    </form>
    <?php if (!empty($_SESSION['upload_error'])): ?>
        <div class="error-message"><?php echo htmlspecialchars($_SESSION['upload_error']); ?></div>
        <?php unset($_SESSION['upload_error']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['upload_success'])): ?>
        <div class="success-message"><?php echo htmlspecialchars($_SESSION['upload_success']); ?></div>
        <?php unset($_SESSION['upload_success']); ?>
    <?php endif; ?>
</div>
        </div>

        <div class="bookmarked-section">
            <h1>My Favorite Manga :</h1> <br>
            <div class="card-grid" id="favorites"></div>
        </div>
    </div>

    <script>
        function navigateTo(page) {
            window.location.href = page;
        }

        function logout() {
            alert("Logging out...");
        }
    function loadFavorites() {
        const favoritesContainer = document.getElementById('favorites');
        const savedMangas = JSON.parse(localStorage.getItem('savedManga')) || [];
        
        if (savedMangas.length === 0) {
            favoritesContainer.innerHTML = '<p>No favorite manga added yet.</p>';
            return;
        }

        favoritesContainer.innerHTML = ''; // Clear existing content

        savedMangas.forEach(manga => {
            const card = document.createElement('div');
            card.className = 'card';
            const mangaData = encodeURIComponent(JSON.stringify(manga));
            card.innerHTML = `
                <div style="position: relative;">
                    <i class="fas fa-times unsave-icon" 
                       style="position: absolute; top: 10px; right: 10px; cursor: pointer; color: red; font-size: 20px;"
                       title="Remove from favorites"
                       onclick="unsaveManga('${mangaData}')"></i>
                    <img src="${manga.thumb || 'https://via.placeholder.com/300x450?text=No+Image'}" alt="${manga.title}">
                    <h3>${manga.title}</h3>
                    <p><strong>Genres:</strong> ${manga.genres?.join(', ') || 'Unknown'}</p>
                    <p><strong>Status:</strong> ${manga.status || 'Unknown'}</p>
                    <p><strong>Chapters:</strong> ${manga.total_chapter || '0'}</p>
                </div>
            `;
            favoritesContainer.appendChild(card);
        });
    }

    function unsaveManga(encodedManga) {
        const manga = JSON.parse(decodeURIComponent(encodedManga));
        let saved = JSON.parse(localStorage.getItem('savedManga')) || [];

        const index = saved.findIndex(m => m.id === manga.id);
        if (index !== -1) {
            saved.splice(index, 1);
            localStorage.setItem('savedManga', JSON.stringify(saved));
            alert(`Removed "${manga.title}" from favorites.`);
            loadFavorites(); // Refresh the favorites display
        } else {
            alert(`"${manga.title}" was not found in favorites.`);
        }
    }   
    window.onload = loadFavorites;
    </script>
</body>
</html>