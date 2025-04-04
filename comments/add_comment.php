<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['comment']) && !empty($_POST['recipe_id'])) {
    $user_id = $_SESSION['user_id'];
    $recipe_id = intval($_POST['recipe_id']);
    $comment = trim($_POST['comment']);

    if ($comment !== "") {
        $stmt = $con->prepare("INSERT INTO comments (user_id, recipe_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $recipe_id, $comment);
        $stmt->execute();
    }
}

header("Location: ../public/recipe_detail.php?id=" . $_POST['recipe_id']);
exit();
