<?php
try {
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "users_info";
    $name = "mysql:host=$host;dbanme=$db;";
    $conn = new PDO("mysql:host=$host;dbname=$db;", $user, $password);
 
} catch (PDOException $e) {
    die("Connection Error");

}


?>