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
    <link rel="stylesheet" href="../assets/css/footer_styles.css">


    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome -->

</head>
<body>
    <?php include '../components/navbar.php'; ?>

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
                    Don't have an account? <a href="./signup.php">SignUp</a>
                </div>
            </div>
        </form>
    </div>

    <?php include '../components/footer.php'; ?>

</body>
</html>