<?php
include_once "../config/database.php";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST["fname"]);
    $last_name = trim($_POST["lname"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if (strlen($password) < 6) {
        die("Password must be at least 6 characters.");
    }

    $sql1 = "SELECT id FROM users WHERE email = '$email'";
    $res = mysqli_query($con, $sql1);

    if (mysqli_num_rows($res) > 0) {
        die("Email is already registered.");
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $sql2 = "INSERT INTO users (first_name, last_name, email, password) VALUES ('$first_name', '$last_name', '$email', '$hashed_password')"; 
    if (mysqli_query($con, $sql2)) {
        header("Location: ../public/success.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }

    $con->close();
} else {
    die("Invalid request");
}
?>