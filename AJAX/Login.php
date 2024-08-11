<?php
include 'Connection/Connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize inputs
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']); // No htmlspecialchars on the password

    try {
        // Prepare the SQL statement
        $stmt = $conn->prepare("SELECT * FROM `user_info` WHERE `email` = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Count rows returned
        $row_count = $stmt->rowCount();

        if ($row_count == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stored_password = $row['password'];

            // Verify the password
            if (password_verify($password, $stored_password)) {
                session_start();
                session_regenerate_id(true); // Prevent session fixation

                // Set session variables
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $row['sno'];
                $_SESSION['username'] = $row['username'];

                // Handle "Remember Me"
                if (isset($_POST['remember'])) {
                    setcookie("email", $email, time() + (86400 * 1), "/");
                    setcookie("password", $password, time() + (86400 * 1), "/");

                } else {
                    setcookie("email", "", time() - 3600, "/");
                    setcookie("password", "", time() - 3600, "/"); // Remove cookie
                }

                // Update session ID in the database
                $session_id = session_id();
                $update_stmt = $conn->prepare("UPDATE `user_info` SET `session_id` = :session_id WHERE `sno` = :user_id");
                $update_stmt->bindParam(':session_id', $session_id);
                $update_stmt->bindParam(':user_id', $row['sno']);
                $update_stmt->execute();

                // Redirect to Dashboard with user ID
                echo json_encode(['success' => true, 'redirect' => 'Dashboard.php?user=' . $_SESSION['user_id']]);
                exit();

            } else {
                // Password incorrect
                echo json_encode(['success' => false, 'message' => 'Incorrect Password! Please try Again.']);
            }
        } else {
            // User not found
            echo json_encode(['success' => false, 'message' => 'User Not Found!']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>