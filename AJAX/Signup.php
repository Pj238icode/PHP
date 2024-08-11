<?php
include 'Connection/Connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $secret_key = ""; // Replace with your actual secret key
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $captcha_response = $_POST['g-recaptcha-response']; // Get the reCAPTCHA response

    // Verify the reCAPTCHA response
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha_response");
    $response_keys = json_decode($response, true);

    // Check if the CAPTCHA was successfully verified
    if (!$response_keys['success']) {
        echo json_encode(['success' => false, 'message' => 'CAPTCHA verification failed. Please try again.']);
        exit();
    }

    // Handle file upload and validations...
    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_error = $_FILES['image']['error'];
    $file_size = $_FILES['image']['size'];

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif']; // Add more types as needed
    $max_size = 2 * 1024 * 1024; // 2MB

    // Validate image upload
    if ($file_error !== UPLOAD_ERR_OK) {
        echo '<script>
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Error in uploading file",
                showConfirmButton: false,
                timer: 1500
            });
        </script>';
        exit();
    }

    if (!in_array($_FILES['image']['type'], $allowed_types)) {
        echo '<script>
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Invalid file type. Only JPG, PNG, and GIF files are allowed.",
                showConfirmButton: false,
                timer: 1500
            });
        </script>';
        exit();
    }

    if ($file_size > $max_size) {
        echo '<script>
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "File size exceeds the maximum limit of 2MB.",
                showConfirmButton: false,
                timer: 1500
            });
        </script>';
        exit();
    }

    $folder = "C:/xampp/htdocs/Forum/Images/" . basename($file_name);
    if (!move_uploaded_file($file_tmp, $folder)) {
        echo '<script>
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Error in uploading file.",
                showConfirmButton: false,
                timer: 1500
            });
        </script>';
        exit();
    }

    // Check if the email already exists
    $query = $conn->prepare("SELECT COUNT(*) FROM `user_info` WHERE email = :email");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $count = $query->fetchColumn();

    if ($count > 0) {
        echo json_encode(['success' => false, 'message' => 'This email is already registered.']);
        exit();
    }


    // Prepare the SQL query
    $query = $conn->prepare("INSERT INTO `user_info` (username, email, password, image) VALUES (:username, :email, :password, :image)");
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $query->bindParam(':password', $hash, PDO::PARAM_STR);
    $query->bindParam(':image', $file_name, PDO::PARAM_STR);
  

    // Execute the query
    $res = $query->execute();
    $userId = $conn->lastInsertId();
    session_start();
    $_SESSION['user_id'] = $userId;



    if ($res) {
        echo json_encode(['success' => true, 'message' => 'Registration successful.','user_id' => $userId]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error during registration.']);
    }
}
?>
