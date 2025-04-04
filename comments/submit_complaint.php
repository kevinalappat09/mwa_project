<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $con->real_escape_string($_POST['name']);
    $email = $con->real_escape_string($_POST['email']);
    $phone = $con->real_escape_string($_POST['phone']);
    $link = $con->real_escape_string($_POST['link']);
    $issue = $con->real_escape_string($_POST['issue']);

    $photoPath = NULL;

    // Check and create uploads folder if it doesn't exist
    $uploadDir = __DIR__ . '/../uploads/'; // Use absolute path
    $relativeUploadPath = 'uploads/';      // For saving path in DB

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileName = basename($_FILES['photo']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($fileExt, $allowedExts)) {
            $newFileName = uniqid('photo_', true) . '.' . $fileExt;
            $destination = $uploadDir . $newFileName;
            $photoPath = $relativeUploadPath . $newFileName; // Relative path saved in DB

            if (!move_uploaded_file($fileTmpPath, $destination)) {
                $photoPath = NULL; // If upload fails
            }
        }
    }

    $stmt = $con->prepare("INSERT INTO recipe_complaints (name, email, phone, recipe_link, issue, photo_path) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $phone, $link, $issue, $photoPath);

    if ($stmt->execute()) {
        header("Location: ../public/complaint_success.php");
        exit;
    } else {
        header("Location: ../public/complaint_unsuccess.php");
        exit;
    }

    $stmt->close();
    $con->close();
} else {
    echo "Invalid request.";
}
