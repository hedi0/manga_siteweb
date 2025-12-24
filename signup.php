<?php
session_start();
require 'DbConnection.php';

// Handle form submission
$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Validate inputs
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        // Check if username/email already exists
        $stmt = $conn->prepare("SELECT Iduser FROM user WHERE NameUser = ? OR EmailUser = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username or email already exists!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $insert_stmt = $conn->prepare("INSERT INTO user (NameUser, EmailUser, PasswordUser) VALUES (?, ?, ?)");
            $insert_stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($insert_stmt->execute()) {
                // Registration successful, redirect to login
                $_SESSION['signup_success'] = true;
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed. Please try again!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Manga</title>
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
        
        /* === Signup Container === */
        .signup-container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #1a1a1a;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            border: 1px solid #333;
        }
        
        .signup-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .signup-header h2 {
            color: #e60e19;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        
        .signup-header p {
            color: #aaa;
        }
        
        /* === Form Styles === */
        .signup-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .form-group label {
            color: #e60e19;
            font-weight: bold;
        }
        
        .form-group input {
            padding: 12px 15px;
            background-color: #2a2a2a;
            border: 1px solid #444;
            border-radius: 5px;
            color: white;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #e60e19;
            box-shadow: 0 0 0 2px rgba(230, 14, 25, 0.3);
        }
        
        .signup-btn {
            background-color: #e60e19;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .signup-btn:hover {
            background-color: #c00c16;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        
        /* === Footer Links === */
        .signup-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #aaa;
        }
        
        .signup-footer a {
            color: #e60e19;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .signup-footer a:hover {
            text-decoration: underline;
            color: #ff3333;
        }
        
        /* === Messages === */
        .error-message {
            color: #ff6b6b;
            padding: 10px;
            background-color: rgba(255, 107, 107, 0.1);
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
            border: 1px solid rgba(255, 107, 107, 0.3);
        }
        
        .success-message {
            color: #51cf66;
            padding: 10px;
            background-color: rgba(81, 207, 102, 0.1);
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
            border: 1px solid rgba(81, 207, 102, 0.3);
        }
        
        /* === Responsive Design === */
        @media (max-width: 768px) {
            .Nav {
                padding: 10px 15px;
            }
            
            .Nav h2 {
                margin-left: 5%;
            }
            
            .signup-container {
                margin: 2rem 1rem;
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 480px) {
            .signup-container {
                padding: 1rem;
            }
            
            .signup-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="Nav">
        <h2 onclick="window.location.href='homePage.html'">Manga</h2>
        <ul class="ul link-offset">
            <li onclick="window.location.href='homePage.html'">Home</li>
            <li onclick="window.location.href='Manga.html'">All Manga</li>
            <li onclick="window.location.href='Review.html'">Review</li>
            <li onclick="window.location.href='contact.php'">Contact</li>

        </ul>
        <button class="button1" onclick="window.location.href='signup.php'">Sign up</button>
    </nav>
    <style>
        .ul {
    display: flex;
    justify-content: space-between;
    width: 30%;
    margin-right: -30%;
    color: white;
    cursor: pointer;
    list-style: none;
    font-weight: bold;
    margin-top: 7px;
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

button:hover {
    transform: scale(1.1);
    cursor: pointer;
}
    </style>
    
    <!-- Signup Form -->
    <div class="signup-container">
        <div class="signup-header">
            <h2>Create an Account</h2>
            <p>Join our manga community today</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['signup_success'])): ?>
            <?php unset($_SESSION['signup_success']); ?>
            <div class="success-message">Registration successful! You can now <a href="login.php">login</a>.</div>
        <?php endif; ?>
        
        <form class="signup-form" method="POST" action="signup.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password (min 6 characters)</label>
                <input type="password" id="password" name="password" minlength="6" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" minlength="6" required>
            </div>
            
            <button type="submit" class="signup-btn">Sign Up</button>
        </form>
        
        <div class="signup-footer">
            <p>Already have an account? <a href="login.php">Log in</a></p>
        </div>
    </div>

    <script>
        // Password match validation
        document.querySelector('.signup-form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters!');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>