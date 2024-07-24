<?php
// update_profile.php

// Include database connection
include_once 'Connection/Connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $editFirstName = $_POST['editFirstName'];
    $editLastName = $_POST['editLastName'];
    $editEmail = $_POST['editEmail'];
    $editPhone = $_POST['editPhone'];
    $editCountry = $_POST['editCountry'];

    // Check if the new email already exists for another user
    $queryEmail = $conn->prepare("SELECT * FROM `user_data` WHERE `email` = :email AND `sno` != :user_id");
    $queryEmail->bindParam(':email', $editEmail, PDO::PARAM_STR);
    $queryEmail->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $queryEmail->execute();
    $existingEmail = $queryEmail->fetch(PDO::FETCH_ASSOC);

    // Check if the new phone number already exists for another user
    $queryPhone = $conn->prepare("SELECT * FROM `user_data` WHERE `phone` = :phone AND `sno` != :user_id");
    $queryPhone->bindParam(':phone', $editPhone, PDO::PARAM_STR);
    $queryPhone->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $queryPhone->execute();
    $existingPhone = $queryPhone->fetch(PDO::FETCH_ASSOC);

    // Check if email or phone already exists
    if ($existingEmail || $existingPhone) {
        $errorMsg = "";
        if ($existingEmail) {
            $errorMsg .= "Email already exists. ";
        }
        if ($existingPhone) {
            $errorMsg .= "Phone number already exists. ";
        }
        echo "<script>alert('$errorMsg Please change your email and phone number.')</script>";
    } else {
        // Update query
        $query = $conn->prepare("UPDATE `user_data` SET `firstname` = :firstname, `lastname` = :lastname, `email` = :email, `phone` = :phone, `country` = :country WHERE `sno` = :user_id");
        $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $query->bindParam(':firstname', $editFirstName, PDO::PARAM_STR);
        $query->bindParam(':lastname', $editLastName, PDO::PARAM_STR);
        $query->bindParam(':email', $editEmail, PDO::PARAM_STR);
        $query->bindParam(':phone', $editPhone, PDO::PARAM_STR);
        $query->bindParam(':country', $editCountry, PDO::PARAM_STR);

        if ($query->execute()) {
            // Success
            header('Location: profile.php?user=' . $user_id);
            exit;
        } else {
            // Error
            die("Failed to update profile.");
        }
    }
}
?>
