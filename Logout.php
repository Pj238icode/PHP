<?php
include 'Connection/Connect.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if ($user_id) {
  $clear_session_stmt = $conn->prepare("UPDATE `user_info` SET `session_id` = NULL WHERE `sno` = :user_id");
  $clear_session_stmt->bindParam(':user_id', $user_id);
  $clear_session_stmt->execute();
}

session_destroy();
session_unset();
header("location:login1.php")
  ?>