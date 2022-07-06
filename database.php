<?php
    // ob_start();
    $host = "Host";
    $user = "User";
    $database = "Database";
    $password = "Password";
    try {
        $db = new PDO("mysql:host=$host;dbname=$database", "$user", "$password");
    } catch(PDOException $ex) {
        echo "<div style='color: red'>DB Connection Error.</div> $ex";
    }
?>
