<?php
session_start();
include 'Connection/Connect.php';


$user_id = $_SESSION['user_id'] ?? null;
if ($user_id) {
    $clear_session_stmt = $conn->prepare("UPDATE `user_data` SET `session_id` = NULL WHERE `sno` = :user_id");
    $clear_session_stmt->bindParam(':user_id', $user_id);
    $clear_session_stmt->execute();
}


$_SESSION = array();
session_destroy();

header("location: Login.php");
exit();
?>
