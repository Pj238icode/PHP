<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(90deg, rgba(131, 58, 180, 1) 0%, rgba(253, 29, 29, 1) 50%, rgba(184, 143, 118, 1) 72%, rgba(252, 176, 69, 1) 100%);

        }

        .container {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center">Reset Password</h2>
                        <form action=""  method="POST">
                            <div class="form-group">
                                <label for="otp">Enter OTP</label>
                                <input type="text" class="form-control" id="otp" name="otp" required>
                            </div>
                            <div class="form-group">
                                <label for="new_password">Enter New Password</label>
                                <input type="password" class="form-control " id="new_password" name="new_password"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Enter Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password"
                                    name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-danger btn-block">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>



<?php
// Include database connection and PHPMailer files
include_once 'Connection/Connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'mail/Exception.php';
require 'mail/PHPMailer.php';
require 'mail/SMTP.php';

// Start session
session_start();

// Validate session email
if (!isset($_SESSION['email'])) {
    header("location:Reset.php");
}

$email = filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL);

// Function to generate a random OTP
function generateOTP($length = 8)
{
    $otp = "";
    $characters = "0123456789";
    $charLength = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $otp .= $characters[rand(0, $charLength - 1)];
    }

    $expiry_time = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    // Store OTP and expiry time in session
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_expiry'] = $expiry_time;

    return $otp;
}
try {
    if(!isset($_SESSION['otp_sent'])){

    
    // Create PHPMailer instance
    $mail = new PHPMailer(true);

    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = '';
    $mail->Password = ''; // Replace with your Gmail password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Set sender and recipient
    $mail->setFrom('', '');
    $mail->addAddress($email);

    // Generate OTP
    $otp = generateOTP();

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Reset Password OTP';
    $mail->Body = 'Your OTP for password reset is <b>' . $otp . '</b>'.'The OTP is only valid for 10 minutes!';

    // Send email
    $mail->send();

    // Store OTP in session
    $_SESSION['otp'] = $otp;
    $_SESSION['otp_sent']=true;
    echo '<script>
    Swal.fire({
        position: "top",
        icon: "success",
        title: "OTP successfully to your email Id! ",
        showConfirmButton: false,
        timer: 1500
    });
</script>';
    }


} catch (Exception $e) {
    echo '<script>
    Swal.fire({
        position: "top",
        icon: "error",
        title: "Technical Error!",
        showConfirmButton: false,
        timer: 1500
    });
</script>';
    
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $otp = $_POST['otp'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate OTP
    if (!isset($_SESSION['otp']) || $_SESSION['otp'] !== $otp || strtotime($_SESSION['otp_expiry']) < time()) {
        echo '<script>
            Swal.fire({
                position: "top",
                icon: "error",
                title: "Invalid OTP or OTP expired",
                showConfirmButton: false,
                timer: 1500
            });
        </script>';
    } else {
        // Validate new password and confirm password
        if ($new_password !== $confirm_password) {
            echo '<script>
                Swal.fire({
                    position: "top",
                    icon: "error",
                    title: "Password Mismatch",
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>';
        } else {
            // Sanitize email
            $email = filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL);

            try {
              
                 $stmt = $conn->prepare("UPDATE `user_data` SET password = :password WHERE email = :email");
                 $stmt->bindParam(':email', $email);
                 $hash=password_hash($new_password, PASSWORD_DEFAULT);
                 $stmt->bindParam(':password', $hash);
                 $stmt->execute();

                
                unset($_SESSION['email']);
                unset($_SESSION['otp']);
                unset($_SESSION['otp_expiry']);

                echo '<script>
                    Swal.fire({
                        position: "top",
                        icon: "success",
                        title: "Password Reset Successful",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.href = "login.php"; 
                    });
                </script>';
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}



?>

