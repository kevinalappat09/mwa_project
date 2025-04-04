<?php
session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../assets/css/login_styles.css">
    <link rel="stylesheet" href="../assets/css/navbar_styles.css">
    <link rel="stylesheet" href="../assets/css/footer_styles.css">


    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome -->

</head>
<body>
    <nav class="navbar">
        <div class="nav-buttons">
            <button class="nav-btn recipes-btn active"><a href="./dashboard.php">Home</a></button>
        </div>
    </nav>

    <div class="container">
        <div class="sidebar">
            <div class="rectangle-container">
                <div class="rectangle small"></div>
                <div class="rectangle medium"></div>
                <div class="rectangle large"></div>
                <div class="rectangle xlarge"></div>
                <div class="rectangle large"></div>
                <div class="rectangle medium"></div>
                <div class="rectangle small"></div>
            </div>
        </div>

        <form class="login-form-container" action="../auth/login.php" method="post">
            <div class="login-form">
                <h2>Login</h2>
                <p class="error" id="error-msg"></p>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email-inp" name="email" placeholder="Enter your email">
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                        <input type="password" id="password-inp" name="password" placeholder="Enter your password">
                </div>

                <div class="options">
                    <div class="cbox">
                        <input type="checkbox">Remember Me
                    </div>
                    <a href="#">Forgot Password?</a>
                </div>

                <button class="login-btn-form" onclick="validate_login(event)">
                    <i class="fas fa-sign-in-alt"></i> LOGIN
                </button>

                <div class="or">OR</div>

                <button class="social-btn meta">
                    <i class="fab fa-facebook"></i> Login With Meta
                </button>
                <button class="social-btn google">
                    <i class="fab fa-google"></i> Login With Google
                </button>

                <div class="signup-link">
                    Don't have an account? <a href="./cs.html">SignUp</a>
                </div>
            </div>
        </form>
    </div>

    <footer class="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>About</h3>
                <p>We provide healthy, nutritious, and allergen-conscious recipes for everyone.</p>
            </div>
            <div class="footer-section">
                <h3>Legal</h3>
                <ul>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Cookie Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <ul>
                    <li>Email: <a href="mailto:support@foodforall.com">support@foodforall.com</a></li>
                    <li>Phone: <a href="tel:+1234567890">+1 234 567 890</a></li>
                    <li><a href="#">Customer Support</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <ul class="social-links">
                    <li><a href="#" target="_blank">Facebook</a></li>
                    <li><a href="#" target="_blank">Twitter</a></li>
                    <li><a href="#" target="_blank">Instagram</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>