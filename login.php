<?php
session_start();
require 'DbConnection.php';

if (isset($_SESSION['user_id'])) {
    header("Location: Profile.php");
    exit();
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $error = "Email and password are required";
    } else {
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format";
        } else {
            try {
                $stmt = $conn->prepare("SELECT Iduser, NameUser, EmailUser, PasswordUser, Photo_profile FROM user WHERE EmailUser = ?");
                if (!$stmt) {
                    throw new Exception("Database error. Please try again later.");
                }
                
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    
                    if (password_verify($password, $user['PasswordUser'])) {
                        $_SESSION['user_id'] = $user['Iduser'];
                        $_SESSION['user_email'] = $user['EmailUser'];
                        $_SESSION['user_name'] = $user['NameUser'];
                        $_SESSION['profile_image'] = $user['Photo_profile'] ?? 'default.jpg';
                        session_regenerate_id(true);
                        header("Location: Profile.php");
                        exit();
                    } else {
                        $error = "Invalid email or password";
                    }
                } else {
                    $error = "Invalid email or password";
                }
                $stmt->close();
            } catch (Exception $e) {
                error_log("Login error: " . $e->getMessage());
                $error = "A system error occurred. Please try again later.";
            }
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Manga</title>
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
        
        /* === Login Container === */
        .login-container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #1a1a1a;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            border: 1px solid #333;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header h2 {
            color: #e60e19;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }
        
        .login-header p {
            color: #aaa;
        }
        
        /* === Form Styles === */
        .login-form {
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
        
        .login-btn {
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
        
        .login-btn:hover {
            background-color: #c00c16;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        
        /* === Footer Links === */
        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #aaa;
        }
        
        .login-footer a {
            color: #e60e19;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .login-footer a:hover {
            text-decoration: underline;
            color: #ff3333;
        }
        
        /* === Error Message === */
        .error-message {
            color: #ff6b6b;
            padding: 10px;
            background-color: rgba(255, 107, 107, 0.1);
            border-radius: 5px;
            margin-bottom: 1rem;
            text-align: center;
            border: 1px solid rgba(255, 107, 107, 0.3);
        }
        
        /* === Responsive Design === */
        @media (max-width: 768px) {
            .Nav {
                padding: 10px 15px;
            }
            
            .Nav h2 {
                margin-left: 5%;
            }
            
            .login-container {
                margin: 2rem 1rem;
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 1rem;
            }
            
            .login-header h2 {
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
    
    <!-- Login Form -->
    <div class="login-container">
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p>Sign in to access your manga collection</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form class="login-form" method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="login-btn">Sign In</button>
        </form>
        
        <div class="login-footer">
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
            <p><a href="forgot_password.php">Forgot password?</a></p>
        </div>
    </div>

    <script>
        // Basic client-side validation
        document.querySelector('.login-form').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if (!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields');
                return false;
            }
            
            if (!email.includes('@')) {
                e.preventDefault();
                alert('Please enter a valid email address');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>