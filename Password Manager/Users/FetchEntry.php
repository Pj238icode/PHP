<?php
// fetchEntry.php

include_once 'Connection/Connect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['entry_id'])) {
    $entryId = $_GET['entry_id'];

    // Query to fetch entry details
    $stmt = $conn->prepare("SELECT * FROM `user_entries` WHERE `sno` = :entry_id");
    $stmt->bindParam(':entry_id', $entryId);
    $stmt->execute();
    $entry = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($entry) {
        // Return entry details as JSON response
        header('Content-Type: application/json');
        echo json_encode($entry);
    } else {
        // Entry not found
        http_response_code(404);
        echo json_encode(['error' => 'Entry not found']);
    }
} else {
    // Invalid request
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>
