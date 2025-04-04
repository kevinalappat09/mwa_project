<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint Successful</title>
    <link rel="stylesheet" href="../assets/css/success.css">
</head>
<body>
    <?php include '../components/navbar.php'; ?>
    <main>
        <h2>Complaint Successful</h2>
        <p>Your complaint has been recorded successfully.</p>
        <a href="./dashboard.php">Go to Home</a>
    </main>
    <?php include '../components/footer.php'; ?>

</body>
</html>
