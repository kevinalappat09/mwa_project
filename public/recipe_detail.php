<?php
session_start();
require_once '../config/database.php';
if (!isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}
$recipe_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($recipe_id === 0) {
    die("Invalid recipe ID.");
}

// Fetch recipe info
$sql = "SELECT r.*, u.first_name, u.last_name 
        FROM recipes r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$result = $stmt->get_result();
$recipe = $result->fetch_assoc();

if (!$recipe) {
    die("Recipe not found.");
}

// Fetch ingredients
$ing_stmt = $con->prepare("SELECT name FROM ingredients WHERE recipe_id = ?");
$ing_stmt->bind_param("i", $recipe_id);
$ing_stmt->execute();
$ing_result = $ing_stmt->get_result();
$ingredients = [];
while ($row = $ing_result->fetch_assoc()) {
    $ingredients[] = $row['name'];
}

// Fetch steps
$steps_stmt = $con->prepare("SELECT step_number, description FROM steps WHERE recipe_id = ? ORDER BY step_number ASC");
$steps_stmt->bind_param("i", $recipe_id);
$steps_stmt->execute();
$steps_result = $steps_stmt->get_result();
$steps = [];
while ($row = $steps_result->fetch_assoc()) {
    $steps[] = $row;
}

// Fetch comments
$com_stmt = $con->prepare("SELECT c.comment, u.first_name FROM comments c JOIN users u ON c.user_id = u.id WHERE c.recipe_id = ?");
$com_stmt->bind_param("i", $recipe_id);
$com_stmt->execute();
$com_result = $com_stmt->get_result();
$comments = [];
while ($row = $com_result->fetch_assoc()) {
    $comments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($recipe['title']); ?> - Recipe</title>
    <link rel="stylesheet" href="../assets/css/navbar_styles.css">
    <link rel="stylesheet" href="../assets/css/footer_styles.css">
     <link rel="stylesheet" href="../assets/css/recipe_styles.css">
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


    <div class="recipe-container">
        <div class="recipe-header">
            <h1 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h1>
            <div class="recipe-meta">By <?php echo htmlspecialchars($recipe['first_name'] . ' ' . $recipe['last_name']); ?></div>
            
            <!-- Compact Stats Boxes -->
            <div class="stats-row">
                <div class="stat-box">
                    <div class="stat-label">Prep Time</div>
                    <div class="stat-value"><?php echo $recipe['prep_time']; ?></div>
                    <div class="stat-label">minutes</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Cook Time</div>
                    <div class="stat-value"><?php echo $recipe['cook_time']; ?></div>
                    <div class="stat-label">minutes</div>
                </div>
                <div class="stat-box">
                    <div class="stat-label">Servings</div>
                    <div class="stat-value"><?php echo $recipe['servings']; ?></div>
                    <div class="stat-label">people</div>
                </div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Left Column - Ingredients -->
            <div class="ingredients-section">
                <div class="info-card">
                    <h3 class="card-title">Ingredients</h3>
                    <ul class="ingredients-list">
                        <?php foreach ($ingredients as $item): ?>
                            <li><?php echo htmlspecialchars($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Right Column - Info Cards -->
            <div class="info-cards">
                
                <!-- Nutrition Info -->
                <?php if (!empty($recipe['nutrition'])): ?>
                <div class="info-card">
                    <h3 class="card-title">Nutrition Facts</h3>
                    <div class="nutrition-highlight">
                        <?php echo htmlspecialchars($recipe['nutrition']); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Allergy Info -->
                <?php if (!empty($recipe['allergy_info']) || !empty($recipe['contains_gluten']) || !empty($recipe['contains_dairy']) || !empty($recipe['contains_nuts'])): ?>
                <div class="info-card">
                    <h3 class="card-title">Allergy Information</h3>
                    <div class="allergy-warning">
                        <?php if (!empty($recipe['allergy_info'])): ?>
                            <?php echo htmlspecialchars($recipe['allergy_info']); ?>
                        <?php else: ?>
                            <?php if (!empty($recipe['contains_gluten'])): ?>• Contains Gluten<br><?php endif; ?>
                            <?php if (!empty($recipe['contains_dairy'])): ?>• Contains Dairy<br><?php endif; ?>
                            <?php if (!empty($recipe['contains_nuts'])): ?>• Contains Nuts<?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($recipe['contains_gluten']) || !empty($recipe['contains_dairy']) || !empty($recipe['contains_nuts'])): ?>
                    <div class="tags-container" style="margin-top: 1rem;">
                        <?php if (!empty($recipe['contains_gluten'])): ?>
                            <span class="tag allergy-tag">Gluten</span>
                        <?php endif; ?>
                        <?php if (!empty($recipe['contains_dairy'])): ?>
                            <span class="tag allergy-tag">Dairy</span>
                        <?php endif; ?>
                        <?php if (!empty($recipe['contains_nuts'])): ?>
                            <span class="tag allergy-tag">Nuts</span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Steps Section -->
            <div class="steps-section info-card">
                <h3 class="card-title">Preparation Steps</h3>
                <ol class="steps-list">
                    <?php foreach ($steps as $step): ?>
                        <li><?php echo htmlspecialchars($step['description']); ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>
            
            <!-- Comments Section -->
            <!-- Comments Section -->
            <div class="comments-section info-card">
                <h3 class="card-title">Comments</h3>
                <?php if (isset($_SESSION["user_id"])): ?>
                    <form action="../comments/add_comment.php" method="POST" class="comment-form">
                        <textarea name="comment" class="comment-input" placeholder="Write your comment here..." required></textarea>
                        <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
                        <button type="submit" class="comment-btn">Post Comment</button>
                    </form>
                <?php endif; ?>

                <?php if (count($comments) > 0): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <div class="comment-author"><?php echo htmlspecialchars($comment['first_name']); ?></div>
                            <p class="comment-text"><?php echo htmlspecialchars($comment['comment']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-comments">No comments yet. Be the first to comment!</p>
                <?php endif; ?>
                
            </div>

        </div>

        <div class="feedback-corner">
            <h1>Your Feedback Is Valuable</h1>
            <p>Have some concern about this recipe that you would like to share with us? Fill out this form and our customer service representative will reach out as soon as possible.</p>
            <button class="feedback-btn"><a href="./feedback.php">Feedback</a></button>
        </div>
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
