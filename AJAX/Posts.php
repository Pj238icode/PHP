<?php
include 'Connection/Connect.php';

try {

    $q = $conn->prepare("SELECT `comment` FROM `comments`");

    $q->execute();


    $count = $q->rowCount();


    echo json_encode(array("status" => true, "count" => $count));
} catch (PDOException $e) {

    echo json_encode(array("status" => false, "error" => $e->getMessage()));
}

$conn = null;
?>