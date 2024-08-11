<?php
include 'Connection/Connect.php';

try {
  
    $q = $conn->prepare("SELECT `session_id` FROM `user_info` WHERE `session_id` IS NOT NULL");

    $q->execute();

  
    $count = $q->rowCount();

   
    echo json_encode(array("status" => true, "count" => $count));
} catch (PDOException $e) {
  
    echo json_encode(array("status" => false, "error" => $e->getMessage()));
}

$conn = null;
?>
