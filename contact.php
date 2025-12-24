<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Manga Platform</title>
    <style>
        /* === Global Styles === */
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Movies', sans-serif;
        }
        
        body {
            background-color: #0b0a0a;
            color: white;
            min-height: 100vh;
            padding-top: 80px; /* For fixed nav */
        }
        
        /* === Navigation === */
        .Nav {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 30px;
            background-color: black;
            color: red;
            height: 60px;
            border-bottom: 1px solid #e60e19;
        }
        
        .Nav h2 {
            cursor: pointer;
            margin-left: 10%;
            color: #e60e19;
        }
        
        .ul {
            display: flex;
            justify-content: space-between;
            list-style: none;
            width: 30%;
            margin-right: -30%;
            color: white;
            font-weight: bold;
            margin-top: 7px;
            cursor: pointer;
        }
        
        .ul li:hover {
            color: red;
            transition: .3s;
        }
        
        .button1 {
            border: none;
            background: red;
            padding: 12px 30px;
            border-radius: 30px;
            color: white;
            font-size: 15px;
            transition: .4s;
        }
        
        .button1:hover {
            transform: scale(1.1);
            cursor: pointer;
        }
        
        /* === Contact Container === */
        .contact-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .contact-title {
            color: #e60e19;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
        }
        
        /* === Contact Form === */
        .contact-form {
            background-color: #1a1a1a;
            padding: 30px;
            border-radius: 10px;
            border: 1px solid #333;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #e60e19;
            font-weight: bold;
        }
        
        .form-input, .form-textarea {
            width: 100%;
            padding: 12px;
            background-color: #2a2a2a;
            border: 1px solid #444;
            border-radius: 5px;
            color: white;
            font-size: 1rem;
        }
        
        .form-textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: #e60e19;
            box-shadow: 0 0 0 2px rgba(230, 14, 25, 0.3);
        }
        
        .submit-btn {
            background-color: #e60e19;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }
        
        .submit-btn:hover {
            background-color: #c00c16;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        
        /* === Success Message === */
        .success-message {
            color: #4CAF50;
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            font-weight: bold;
        }
        
        /* === Error Message === */
        .error-message {
            color: #f44336;
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }
        
        /* === Responsive Design === */
        @media (max-width: 768px) {
            .ul {
                width: 50%;
                margin-right: 0;
            }
            
            .contact-container {
                padding: 20px 15px;
            }
            
            .contact-title {
                font-size: 2rem;
            }
        }
        
        @media (max-width: 480px) {
            .Nav {
                padding: 10px 15px;
            }
            
            .Nav h2 {
                margin-left: 5%;
                font-size: 1.5rem;
            }
            
            .ul {
                display: none; /* Will be shown in mobile menu */
            }
            
            .contact-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="Nav">
        <h2 onclick="navigateTo('homePage.html')">Manga</h2>
        <ul class="ul link-offset">
            <li onclick="navigateTo('homePage.html')">Home</li>
            <li onclick="navigateTo('Manga.html')">All Manga</li>
            <li onclick="navigateTo('latest.html')">Latest</li>
            <li onclick="navigateTo('Review.html')">Review</li>
            <li onclick="navigateTo('profile.php')">Profile</li>
        </ul>
        <form action="logout.php" method="post" style="display: inline;">
            <button type="submit" class="button1">Sign Up</button>
        </form>
    </nav>
    
    <!-- Main Content -->
    <div class="contact-container">
        <h1 class="contact-title">Contact Us</h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                ya3tik sa7a 3l message ! tw njawbouk 3la 9rib.
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                Error: <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        
        <form class="contact-form" action="contact_submit.php" method="post">
            <div class="form-group">
                <label for="name" class="form-label">Your Name</label>
                <input type="text" id="name" name="name" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" id="subject" name="subject" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label for="message" class="form-label">Message</label>
                <textarea id="message" name="message" class="form-textarea" required></textarea>
            </div>
            
            <?php if (isset($_SESSION['Iduser'])): ?>
                <input type="hidden" name="user_Iduser" value="<?php echo $_SESSION['Iduser']; ?>">
            <?php endif; ?>
            
            <button type="submit" class="submit-btn">Send Message</button>
        </form>
    </div>

    <script>
        function navigateTo(page) {
            window.location.href = page;
        }
    </script>
</body>
</html>