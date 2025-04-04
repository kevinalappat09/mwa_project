<?php
    $host = getenv("DB_HOST");
    $user = getenv("DB_USER");
    $password = getenv("DB_PASSWORD");
    $database = getenv("DB_NAME");

    $con = new mysqli($host, $user, $password, $database);

    if($con->connect_error) {
        die("Connection Failed: " . $con->connect_error);
    }
?>