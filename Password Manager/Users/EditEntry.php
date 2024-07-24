<?php
// updateEntry.php

include_once 'Connection/Connect.php';
session_start();
$id=$_GET['user'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $editSno = $_POST['editSno'];
    $editUserName = $_POST['editUserName'];
    $editUserPassword = $_POST['editUserPassword'];
    $editUserURL = $_POST['editUserURL'];
    $editUserDescription = $_POST['editUserDescription'];

    // Perform update query
    $stmt = $conn->prepare("UPDATE `user_entries` SET `username` = :username, `password` = :password, `url` = :url, `desc1` = :description WHERE `sno` = :sno");
    $stmt->bindParam(':username', $editUserName);
    $stmt->bindParam(':password', $editUserPassword);
    $stmt->bindParam(':url', $editUserURL);
    $stmt->bindParam(':description', $editUserDescription);
    $stmt->bindParam(':sno', $editSno);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Entry updated successfully.";
       header("Location: dashboard.php?user");
       
        exit();
    } else {
       
        echo "Error updating entry.";
    }
}
?>
