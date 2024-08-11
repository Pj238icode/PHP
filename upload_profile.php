<?php
include 'Connection/Connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = htmlspecialchars($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $id = $_SESSION['user_id'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['upload'] = false;
        header("Location: Profile.php?user=$id");
        exit();
    }

    $image = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageName = $_FILES['image']['name'];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageSize = $_FILES['image']['size'];
        $imageType = $_FILES['image']['type'];

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2 MB

        if (in_array($imageType, $allowedTypes) && $imageSize <= $maxSize) {
            $imageExtension = pathinfo($imageName, PATHINFO_EXTENSION);
            $newImageName = uniqid('', true) . '.' . $imageExtension;
            $imageDestination = 'Images/' . $newImageName;

            if (move_uploaded_file($imageTmpName, $imageDestination)) {
                $image = $newImageName;
            } else {
                $_SESSION['upload'] = false;
                header("Location: Profile.php?user=$id");
                exit();
            }
        } else {
            $_SESSION['upload'] = false;
            header("Location: Profile.php?user=$id");
            exit();
        }
    }

    $sql1 = "SELECT * FROM `user_info` WHERE `email` = :email AND `sno` != :id";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt1->execute();

    if ($stmt1->rowCount() > 0) {
        $_SESSION['upload'] = false;
        $_SESSION['email_err'] = true;
        header("Location: Profile.php?user=$id");
        exit();
    }

    $sql = "UPDATE `user_info` SET `username` = :username, `email` = :email, `image` = :image WHERE `sno` = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $_SESSION['upload'] = true;
    } else {
        $_SESSION['upload'] = false;
    }

    header("Location: Profile.php?user=$id");
    exit();
}
?>