


<?php
include_once 'C:\xampp\htdocs\Password Manager\Connection\Connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();

    // Validate and sanitize input values
    $newUserName = htmlspecialchars($_POST['newUserName']);
    $newUserPassword = htmlspecialchars($_POST['newUserPassword']);
    $url = htmlspecialchars($_POST['newUserURL']);
    $desc = htmlspecialchars($_POST['newUserDescription']);

    // Validate and sanitize user ID from $_GET
    $userid = isset($_GET['user']) ? intval($_GET['user']) : null;

    if (!$userid) {
        echo "Invalid user ID.";
        exit;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO `user_entries` (`user_id`, `username`, `password`, `url`, `desc1`) VALUES (:use, :name, :password, :url, :desc)");
        $stmt->bindParam(':use', $userid, PDO::PARAM_INT);
        $stmt->bindParam(':name', $newUserName, PDO::PARAM_STR);
        $stmt->bindParam(':password', $newUserPassword, PDO::PARAM_STR);
        $stmt->bindParam(':url', $url, PDO::PARAM_STR);
        $stmt->bindParam(':desc', $desc, PDO::PARAM_STR);
       

        $stmt->execute();
        $_SESSION['add_user']="Success";

      
        header("Location: Dashboard.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
