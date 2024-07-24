<?php
include_once 'Connection/Connect.php';


if (isset($_GET['id'])) {
    session_start();

    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    try {
      
        $sql = $conn->prepare("DELETE FROM `user_entries` WHERE `sno`=:id ");
        $sql->bindParam(':id', $id, PDO::PARAM_INT);
        $sql->execute();

      
        $rowsAffected = $sql->rowCount();
        if ($rowsAffected > 0) {
            $_SESSION['delete']="Entry Deleted Successfully";
            header("Location: Dashboard.php?user");
            exit();
        } else {
            // Handle case where no rows were deleted (e.g., entry not found)
            echo "No record found to delete.";
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle case where ID parameter is not provided
    echo "Error: Entry ID not provided.";
}
?>
