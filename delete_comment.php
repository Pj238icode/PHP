<?php
include 'Connection/Connect.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if required parameters are set
if (isset($_GET['id']) && isset($_GET['forum']) && isset($_GET['fid']) && isset($_GET['user'])) {
    // Retrieve parameters
    $forum = $_GET['forum'];
    $fid = $_GET['fid'];
    $user = $_GET['user'];
    $id = (int) $_GET['id']; 

    try {
        // Prepare the delete query
        $query = $conn->prepare("DELETE FROM `comments` WHERE `sno` = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Execute the query and check if successful
        if ($query->execute()) {
            // Redirect to the comments page
            header("Location: comments.php?forum={$forum}&id={$fid}&user={$user}");
            exit();
        } else {
            // Query failed
            echo "Failed to execute query.";
        }
    } catch (PDOException $e) {
        // Handle and display database errors
        echo "Database error: " . $e->getMessage();
    }
} else {
    // Required parameters are missing
    echo "Required parameters not set.";
}
?>
