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
    <link rel="stylesheet" href="../assets/css/recipe_detail_styles.css">
    <style>
        /* Clean White Layout */
        :root {
            --primary: #FF6B6B;
            --secondary: #4ECDC4;
            --accent: #FFE66D;
            --dark: #292F36;
            --light: #FFFFFF;
            --warning: #FF6B6B;
            --info: #4ECDC4;
        }
        
        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            background: var(--light);
            color: var(--dark);
        }
        
        .recipe-container {
            max-width: 1100px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        
        /* Header Section */
        .recipe-header {
            margin-bottom: 2rem;
        }
        
        .recipe-title {
            font-size: 2.5rem;
            margin: 0 0 0.5rem 0;
            color: var(--dark);
            font-weight: 700;
        }
        
        .recipe-meta {
            color: #6d6d6d;
            margin-bottom: 1rem;
        }
        
        /* Recipe Image */
        .recipe-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin: 1rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        /* Compact Stats Row */
        .stats-row {
            display: flex;
            gap: 1rem;
            margin: 1.5rem 0;
            justify-content: center;
        }
        
        .stat-box {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            min-width: 100px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0.3rem 0;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: #6d6d6d;
            text-transform: uppercase;
        }
        
        /* Main Content Layout */
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        /* Left Column - Ingredients */
        .ingredients-section {
            grid-column: 1;
        }
        
        /* Right Column - Info Cards */
        .info-cards {
            grid-column: 2;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        /* Card Styles */
        .info-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
        }
        
        .card-title {
            font-size: 1.2rem;
            margin-top: 0;
            margin-bottom: 1rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Ingredients List */
        .ingredients-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 0.8rem;
        }
        
        .ingredients-list li {
            padding: 0.8rem 1rem;
            background: #f9f9f9;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .ingredients-list li:before {
            content: '•';
            color: var(--primary);
            font-size: 1.5rem;
        }
        
        /* Dietary Tags */
        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        
        .tag {
            padding: 0.4em 0.8em;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            background: #f0f0f0;
        }
        
        .allergy-tag {
            background-color: #FFEBEE;
            color: #C62828;
            border: 1px solid #EF9A9A;
        }
        
        /* Nutrition Highlight */
        .nutrition-highlight {
            background: #F5F9FF;
            padding: 1rem;
            border-radius: 6px;
            border-left: 3px solid var(--info);
        }
        
        /* Allergy Warning */
        .allergy-warning {
            background: #FFF5F5;
            padding: 1rem;
            border-radius: 6px;
            border-left: 3px solid var(--warning);
            color: #C62828;
        }
        
        /* Steps Section */
        .steps-section {
            grid-column: 1 / -1;
            margin-top: 2rem;
        }
        
        .steps-list {
            list-style: none;
            padding: 0;
            margin: 0;
            counter-reset: step-counter;
            display: grid;
            gap: 1.5rem;
        }
        
        .steps-list li {
            counter-increment: step-counter;
            padding-left: 3rem;
            position: relative;
        }
        
        .steps-list li:before {
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 0;
            background: var(--primary);
            color: white;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        /* Comments Section */
        .comments-section {
            grid-column: 1 / -1;
            margin-top: 3rem;
        }
        
        .comment {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border-left: 3px solid var(--accent);
        }
        
        .comment-author {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .comment-text {
            color: #555;
            line-height: 1.7;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .stats-row {
                flex-wrap: wrap;
            }
            
            .recipe-image {
                height: 300px;
            }
        }
    </style>
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
            
            <!-- Recipe Image -->
            <img src="/mwa_project/assets/images/garlic_mushroom.jpg" alt="<?php echo htmlspecialchars($recipe['title']); ?>" class="recipe-image">
            
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
                <!-- Dietary Info -->
                <div class="info-card">
                    <h3 class="card-title">Dietary Information</h3>
                    <div class="tags-container">
                        <?php if (!empty($recipe['is_veg'])): ?>
                            <span class="tag">Vegetarian</span>
                        <?php endif; ?>
                        <?php if (!empty($recipe['is_jain'])): ?>
                            <span class="tag">Jain</span>
                        <?php endif; ?>
                    </div>
                </div>
                
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
            <?php if (count($comments) > 0): ?>
            <div class="comments-section info-card">
                <h3 class="card-title">Comments</h3>
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <div class="comment-author"><?php echo htmlspecialchars($comment['first_name']); ?></div>
                        <p class="comment-text"><?php echo htmlspecialchars($comment['comment']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
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
