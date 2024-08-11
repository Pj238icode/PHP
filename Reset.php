<?php
// Include database connection and PHPMailer files
include 'Connection/Connect.php';

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
    header("location:Forgot.php");
    exit;
}

$email = filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL);

function generateOTP($length = 6) {
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
    $value = "";
    if (!isset($_SESSION['otp_sent'])) {
        // Create PHPMailer instance
        $mail = new PHPMailer(true);

        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '';
        $mail->Password = ''; // Add your SMTP password
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
        $mail->Body = 'Your OTP for password reset is <b>' . $otp . '</b>.<br>The OTP is only valid for 10 minutes!';

        // Send email
        $mail->send();

        // Store OTP in session
        $_SESSION['otp_sent'] = true;
        $value = "success";
    }
} catch (Exception $e) {
    $value = "error";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $otp = $_POST['otp'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['cpassword'];

    // Validate OTP
    if (!isset($_SESSION['otp']) || $_SESSION['otp'] !== $otp || strtotime($_SESSION['otp_expiry']) < time()) {
        $value = "invalid";
    } else {
        // Validate new password and confirm password
        if ($new_password !== $confirm_password) {
            $value = "errorpass";
        } else {
            // Sanitize email
            $email = filter_var($_SESSION['email'], FILTER_SANITIZE_EMAIL);

            try {
                $stmt = $conn->prepare("UPDATE `user_info` SET password = :password WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $hash = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt->bindParam(':password', $hash);
                $stmt->execute();

                unset($_SESSION['email']);
                unset($_SESSION['otp']);
                unset($_SESSION['otp_expiry']);
                header("location:login1.php");
                exit;
            } catch (PDOException $e) {
                $value = "error";
            }
        }
    }
}
?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
        <link rel="stylesheet" href="CSS/Reset.css">

    <title>Reset Password</title>

    <style>
      
    </style>
</head>
<body>
    <!-- Spinner HTML -->
    <div class="spinner-container" id="spinner">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    <header>
        <?php include_once 'Partials/header1.php'; ?>
    </header>

    <div class="login-container">
        <h2 class="text-center text-primary">Reset Password</h2>
        <form class="login_form" action="" method="post">
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your Password" required>
                <label for="cpassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm your Password" required>
                <label for="otp" class="form-label">OTP</label>
                <input type="number" class="form-control" id="otp" name="otp" placeholder="Enter your OTP" required>
            </div>
            <button type="submit" class="btn btn-primary mt-4 w-100" id="sign_in">Reset</button>
        </form>
        <div class="signup-prompt">
            <p>Remember Password? <a href="login1.php">Login</a></p>
        </div>
    </div>

    <footer>
        <?php include_once 'C:\xampp\htdocs\Forum\Partials\Footer.php'; ?>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- jQuery (optional, if used) -->
    <script src="JS/jquery-3.7.1.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('#spinner').fadeOut();

            <?php if (!empty($value)): ?>
                let alertOptions = {
                    position: 'top',
                    showConfirmButton: false,
                    timer: 1500
                };

                switch ('<?php echo $value; ?>') {
                    case 'success':
                        Swal.fire({
                            ...alertOptions,
                            icon: 'success',
                            title: 'OTP Sent Successfully!'
                        });
                        break;
                    case 'invalid':
                        Swal.fire({
                            ...alertOptions,
                            icon: 'error',
                            title: 'Invalid OTP!',
                            text: 'The OTP you entered is either incorrect or expired.'
                        });
                        break;
                    case 'errorpass':
                        Swal.fire({
                            ...alertOptions,
                            icon: 'error',
                            title: 'Password Mismatch!',
                            text: 'The new password and confirmation password do not match.'
                        });
                        break;
                    case 'error':
                        Swal.fire({
                            ...alertOptions,
                            icon: 'error',
                            title: 'Technical Error!',
                            text: 'Something went wrong. Please try again.'
                        });
                        break;
                    default:
                        break;
                }
            <?php endif; ?>
        });
    </script>
</body>
</html>
