<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <nav class="navbar">
        <div class="nav-buttons">
            <button class="nav-btn recipes-btn active"><a href="./dashboard.php">Home</a></button>
            <button class="nav-btn"><a href="./search.php">Recipes</a></button>

            <?php if (isset($_SESSION["user_id"])): ?>
                <a href="./cs.html" class="nav-btn" style="text-decoration:none;">
                    Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
                </a>
            <?php else: ?>
                <a href="../public/login.php" class="nav-btn" style="text-decoration:none;">Login</a>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION["user_id"])): ?>
            <button class="nav-btn"><a href="../auth/logout.php">Logout</a></button>
        <?php endif; ?>
    </nav>
</body>
</html>