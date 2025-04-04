<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Recipe Page</title>

        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome -->

        <link rel="stylesheet" href="../assets/css/navbar_styles.css">
        <link rel="stylesheet" href="../assets/css/footer_styles.css">
        <link rel="stylesheet" href="../assets/css/feedback_styles.css">

        <script src="./js/feedback_validation.js"></script>
    </head>
<body>
    <nav class="navbar">
        <div class="nav-buttons">
            <button class="nav-btn"><a href="./home.html">Home</a></button>
            <button class="nav-btn"><a href="./recipe.html">Recipes</a></button>
        </div>
        <button class="login-btn"><a href="./cs.html">Signup</a></button>
    </nav>

    <div class="complaint-form-container">

        <form action="../comments/submit_complaint.php" method="POST" enctype="multipart/form-data" >
            <div class="intro">
                <h2>Recipe Complaint Form</h2>
                <p>If you encountered an issue with a recipe, please fill out this form.</p>
            </div>
            <hr>
            <p class="error" id="error-msg"></p>

            <div class="input-group">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name">
            </div>
            <div class="input-group">
                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="input-group">
                <label for="phone">Your Phone Number:</label>
                <input type="tel" id="phone" name="phone">
            </div>
            <div class="input-group">
                <label for="link">Link To The Recipe:</label>
                <input type="url" id="link" name="link">
            </div>
            <div class="input-group">
                <label for="issue">Describe the Issue:</label>
                <textarea id="issue" name="issue" rows="5"></textarea>
            </div>
            <div class="input-group">
                <label for="photo">Upload a Photo (Optional):</label>
                <input type="file" id="photo" name="photo" accept="image/*">
            </div>
            <button type="submit" class="feedback-btn" onclick="validateFeedback(event)">Submit Complaint</button>
        </form>
    </div>
</body>
</html>