<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FoodForAll</title>

    <link rel="stylesheet" href="../assets/css/home_styles.css">
    <link rel="stylesheet" href="../assets/css/footer_styles.css">

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include '../components/navbar.php'; ?>



    <section class="hero">
        <div class="hero-left">
            <h2>Fuel Your Health</h2>
            <p>Discover delicious, nutritious recipes designed for fitness and flavor lovers alike.</p>
            <button><a href="./recipe.html" class="hero-btn">Explore Recipes</a></button>
        </div>
        <div class="hero-right">
            <img src="../assets/images/food.svg" alt="Healthy Food Illustration">
        </div>
    </section>

    <section class="cards">
        <div class="card">
            <img src="../assets/images/tiffin.svg" alt="School Tiffins">
            <div class="card-content">
                <h3>School Tiffins</h3>
                <p>Packed with both health and taste, these snacks keep kids energized through the day. Easy to make and fun to eat!</p>
                <a href="./recipe.html">Find Recipes</a>
            </div>
        </div>
        <div class="card">
            <img src="../assets/images/fitness.svg" alt="Health">
            <div class="card-content">
                <h3>Health</h3>
                <p>Nutritious, protein-packed meals designed to fuel your fitness journey. Eat well, feel great, and stay strong!</p>
                <a href="./recipe.html">Find Recipes</a>
            </div>
        </div>
        <div class="card">
            <img src="../assets/images/allergy.svg" alt="Allergy-Specific Foods">
            <div class="card-content">
                <h3>Allergy-Specific Foods</h3>
                <p>Delicious recipes carefully crafted to avoid common allergens. Safe, tasty, and worry-free meals for everyone.</p>
                <a href="./recipe.html">Find Recipes</a>
            </div>
        </div>
    </section>

    <hr class="section-divider">

    <section class="nutrition-info">
        <div class="nutrition-left">
            <h2>Know What You Eat</h2>
            <p>We provide detailed nutritional breakdowns for every recipe. Stay informed about calories, protein, fats, and more before you cook!</p>
            <button class="browse-btn"><a href="./recipe.html" class="hero-btn">Explore Recipes</a></button>
        </div>
        <div class="nutrition-right">
            <div class="nutrition-card">
                <h3>Avocado Toast</h3>
                <div class="nutrition-details">
                    <div>
                        <p><strong>Calories:</strong> 250 kcal</p>
                        <p><strong>Protein:</strong> 5g</p>
                        <p><strong>Carbs:</strong> 30g</p>
                    </div>
                    <div>
                        <p><strong>Fats:</strong> 12g</p>
                        <p><strong>Fiber:</strong> 6g</p>
                        <p><strong>Sugar:</strong> 2g</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <hr class="section-divider">

    <section class="allergy-alerts">
        <div class="allergy-left">
            <div class="allergy-card">
                <h3>Allergy Alert</h3>
                <ul class="allergy-list">
                    <li>Contains traces of nuts</li>
                    <li>May include gluten-based ingredients</li>
                    <li>Includes soy derivatives</li>
                </ul>
            </div>
        </div>
        <div class="allergy-right">
            <h2>Food Safety First</h2>
            <p>We provide clear allergy warnings on all recipes, helping you make informed choices. Check ingredients before trying a recipe.</p>
            <button class="browse-btn"><a href="./recipe.html" class="hero-btn">Explore Recipes</a></button>
        </div>
    </section>

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