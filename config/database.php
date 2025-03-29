<?php
    $con = new mysqli("localhost","root","","recipe");
    if($con->connect_error) {
        die("Connection Failed : " . $con->connect_error);
    }
?>