<?php
include_once "../config/database.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>

    <link rel="stylesheet" href="../assets/css/signup_styles.css">
    <link rel="stylesheet" href="../assets/css/navbar_styles.css">
    <link rel="stylesheet" href="../assets/css/footer_styles.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <nav class="navbar">
        <div class="nav-buttons">
            <button class="nav-btn recipes-btn active"><a href="./home.html">Home</a></button>
            <button class="nav-btn"><a href="./recipe.html">Recipes</a></button>

            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="./cs.html" class="nav-btn" style="text-decoration:none;">
                    Welcome, <?php echo htmlspecialchars($_SESSION["first_name"] . ' ' . $_SESSION["last_name"]); ?>
                </a>
            <?php else: ?>
                <a href="../auth/login.php" class="nav-btn" style="text-decoration:none;">Login</a>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION["user_id"])): ?>
            <button class="nav-btn"><a href="../auth/logout.php">Logout</a></button>
        <?php endif; ?>
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

        <form class="signup-form-container" action="../auth/register.php" method="post">
            <div class="signup-form">
                <h2>Sign Up</h2>
                <p class="error" id="error-msg">

                </p>
                <div class="input-group">
                    <label for="fname">First Name</label>
                    <input type="text" name="fname" id="fname-inp" placeholder="Enter your first name">
                </div>

                <div class="input-group">
                    <label for="lname">Last Name</label>
                    <input type="text" name="lname" id="lname-inp" placeholder="Enter your last name">
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email-inp" placeholder="Enter your email">
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                        <input type="password" name="password" id="password-inp" placeholder="Enter your password">
                </div>

                <button class="signup-btn">
                    <i class="fas fa-user-plus"></i> Sign Up
                </button>

                <div class="or">OR</div>

                <button class="social-btn meta">
                    <i class="fab fa-facebook"></i> Sign Up with Meta
                </button>
                <button class="social-btn google">
                    <i class="fab fa-google"></i> Sign Up with Google
                </button>

                <div class="login-link">
                    Account already made? <a href="./cl.html">Login</a>
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