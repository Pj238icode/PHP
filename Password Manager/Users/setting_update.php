<?php
include_once 'Connection/Connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user = $_GET['user'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];


    if ($newPassword != $confirmPassword) {
        $_SESSION['alert'] = "New passwords do not match.";
        $_SESSION['error']=true;
        header("Location: setting.php?user=$user");
        exit;
    }


    if ($currentPassword == $newPassword) {
        $_SESSION['alert'] = "New password should not be the same as current password.";
        $_SESSION['error']=true;
        header("Location: setting.php?user=$user");
        exit;
    }


    $query = $conn->prepare("SELECT `password` FROM `user_data` WHERE `sno`=:user");
    $query->bindParam(':user', $user, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        $_SESSION['alert'] = "User not found or database error.";
        $_SESSION['error']=true;
        header("Location: setting.php?user=$user");
        exit;
    }

    $storedPasswordHash = $result['password'];


    if (!password_verify($currentPassword, $storedPasswordHash)) {
        $_SESSION['alert'] = "Current password is incorrect.";
        $_SESSION['error']=true;
        header("Location: setting.php?user=$user");
        exit;
    }


    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);


    $updateQuery = $conn->prepare("UPDATE `user_data` SET `password`=:hashedPassword WHERE `sno`=:user");
    $updateQuery->bindParam(':hashedPassword', $hashedPassword, PDO::PARAM_STR);
    $updateQuery->bindParam(':user', $user, PDO::PARAM_INT);
    $updateQuery->execute();

    if ($updateQuery->rowCount() > 0) {
        $_SESSION['alert'] = "Password updated successfully.";
        $_SESSION['error']=false;
    } else {
        $_SESSION['alert'] = "Failed to update password. Please try again.";
        $_SESSION['error']=true;
    }

    header("Location: setting.php?user=$user");
    exit;
} else {

    header("Location: setting.php?user=$user");
    exit;
}
?>