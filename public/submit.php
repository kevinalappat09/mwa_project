<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and fetch inputs
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $prep_time = (int)$_POST["prep_time"];
    $cook_time = (int)$_POST["cook_time"];
    $servings = (int)$_POST["servings"];
    $nutrition = trim($_POST["nutrition"]);
    $allergy_info = trim($_POST["allergy_info"]);

    $ingredient_names = $_POST["ingredient_name"] ?? [];
    $ingredient_qtys = $_POST["ingredient_qty"] ?? [];
    $steps = $_POST["steps"] ?? [];

    // Flag fields
    $is_veg = isset($_POST["is_veg"]) ? 1 : 0;
    $contains_dairy = isset($_POST["contains_dairy"]) ? 1 : 0;
    $contains_nuts = isset($_POST["contains_nuts"]) ? 1 : 0;
    $contains_eggs = isset($_POST["contains_eggs"]) ? 1 : 0;
    $contains_gluten = isset($_POST["contains_gluten"]) ? 1 : 0;
    $is_jain = isset($_POST["is_jain"]) ? 1 : 0;

    // Basic validation
    if (empty($title) || empty($description) || !$prep_time || !$cook_time || !$servings) {
        $errors[] = "Please fill out all required fields.";
    }

    if (empty($ingredient_names) || empty($steps)) {
        $errors[] = "Please provide at least one ingredient and one step.";
    }

    if (empty($errors)) {
        $con->begin_transaction();

        try {
            // Insert into recipes
            $stmt = $con->prepare("INSERT INTO recipes (title, description, user_id, prep_time, cook_time, servings, nutrition, allergy_info)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssiiisss", $title, $description, $_SESSION["user_id"], $prep_time, $cook_time, $servings, $nutrition, $allergy_info);
            $stmt->execute();
            $recipe_id = $stmt->insert_id;
            $stmt->close();

            // Insert into flags
            $stmt = $con->prepare("INSERT INTO flags (recipe_id, is_veg, contains_dairy, contains_nuts, contains_eggs, contains_gluten, is_jain)
                                   VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiiiiii", $recipe_id, $is_veg, $contains_dairy, $contains_nuts, $contains_eggs, $contains_gluten, $is_jain);
            $stmt->execute();
            $stmt->close();

            // Insert ingredients
            $stmt = $con->prepare("INSERT INTO ingredients (recipe_id, name) VALUES (?, ?)");
            foreach ($ingredient_names as $index => $name) {
                $full_name = trim($name . " " . ($ingredient_qtys[$index] ?? ""));
                if (!empty($full_name)) {
                    $stmt->bind_param("is", $recipe_id, $full_name);
                    $stmt->execute();
                }
            }
            $stmt->close();

            // Insert steps
            $stmt = $con->prepare("INSERT INTO steps (recipe_id, step_number, description) VALUES (?, ?, ?)");
            foreach ($steps as $i => $step) {
                $step_text = trim($step);
                if (!empty($step_text)) {
                    $step_number = $i + 1;
                    $stmt->bind_param("iis", $recipe_id, $step_number, $step_text);
                    $stmt->execute();
                }
            }
            $stmt->close();

            $con->commit();
            $success = true;
        } catch (Exception $e) {
            $con->rollback();
            $errors[] = "Error submitting recipe: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Recipe</title>
    <link rel="stylesheet" href="../assets/css/search_styles.css"> <!-- Reuse styles -->
    <link rel="stylesheet" href="../assets/css/submit_styles.css">
</head>
<body>
<?php include '../components/navbar.php'; ?>

<main class="submit-main">
    <?php if ($success): ?>
        <p class="success-msg">Recipe submitted successfully!</p>
        <br>
    <?php elseif (!empty($errors)): ?>
        <ul class="error-msg">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <h2 class="submit-heading">Submit a New Recipe</h2>
    <form method="POST" class="submit-form">
        <label>Title<span class="required">*</span></label>
        <input type="text" name="title" required>

        <label>Description<span class="required">*</span></label>
        <textarea name="description" required></textarea>

        <label>Prep Time (minutes)<span class="required">*</span></label>
        <input type="number" name="prep_time" min="1" required>

        <label>Cook Time (minutes)<span class="required">*</span></label>
        <input type="number" name="cook_time" min="1" required>

        <label>Servings<span class="required">*</span></label>
        <input type="number" name="servings" min="1" required>

        <label>Nutrition Info</label>
        <textarea name="nutrition"></textarea>

        <label>Allergy Info</label>
        <textarea name="allergy_info"></textarea>

        <h3>Ingredients<span class="required">*</span></h3>
        <div id="ingredients-container">
            <div class="ingredient-group">
                <input type="text" name="ingredient_name[]" placeholder="Ingredient Name" required>
                <input type="text" name="ingredient_qty[]" placeholder="Quantity" required>
                <button type="button" onclick="removeElement(this)">−</button>
            </div>
        </div>
        <button type="button" onclick="addIngredient()">+ Add Ingredient</button>

        <h3>Steps<span class="required">*</span></h3>
        <div id="steps-container">
            <div class="step-group">
                <textarea name="steps[]" placeholder="Describe this step" required></textarea>
                <button type="button" onclick="removeElement(this)">−</button>
            </div>
        </div>
        <button type="button" onclick="addStep()">+ Add Step</button>

        <h3>Flags</h3>
        <label><input type="checkbox" name="is_veg"> Vegetarian</label><br>
        <label><input type="checkbox" name="contains_dairy"> Contains Dairy</label><br>
        <label><input type="checkbox" name="contains_nuts"> Contains Nuts</label><br>
        <label><input type="checkbox" name="contains_eggs"> Contains Eggs</label><br>
        <label><input type="checkbox" name="contains_gluten"> Contains Gluten</label><br>
        <label><input type="checkbox" name="is_jain"> Jain Friendly</label><br>
        
        <button type="submit" class="submit-btn">Submit Recipe</button>
    </form>
</main>

<script src="../assets/js/submit.js"></script>
<?php include '../components/footer.php'; ?>
</body>
</html>
