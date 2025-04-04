<?php
// Make sure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link rel="stylesheet" href="../assets/css/navbar_styles.css">


<nav class="navbar">
    <div class="nav-buttons">
        <?php if (isset($_SESSION["user_id"])): ?>
            <button class="nav-btn"><a href="../public/dashboard.php">Home</a></button>
            <button class="nav-btn"><a href="../public/search.php">Recipes</a></button>
            <a href="../public/dashboard.php" class="nav-btn" style="text-decoration: none;">
                Welcome, <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
            </a>
        <?php else: ?>
            <button class="nav-btn"><a href="../public/dashboard.php">Home</a></button>
            <a href="../public/login.php" class="nav-btn" style="text-decoration: none;">Login</a>
        <?php endif; ?>
    </div>

    <?php if (isset($_SESSION["user_id"])): ?>
        <button class="nav-btn"><a href="../auth/logout.php">Logout</a></button>
    <?php endif; ?>
</nav>
