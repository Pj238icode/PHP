<?php
include 'Connection/Connect.php';
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;

    }

    // Prepare and execute query
    $query = "SELECT * FROM `user_info` WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $res = $stmt->execute();

    if ($res && $stmt->rowCount() > 0) {
        session_start();
        $_SESSION['email'] = $email;
        header("Location: Reset.php");
        exit;
    } else {
        $error = true;
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
        <link rel="stylesheet" href="CSS/Forgot.css">

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
        <?php include_once 'C:\xampp\htdocs\Forum\Partials\header1.php'; ?>
    </header>

    <div class="login-container">
        <h2 class="text-center text-primary">Reset Password</h2>
        <form class="login_form" action="" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                    required>
            </div>

            <button type="submit" class="btn btn-primary mt-4 w-100" id="sign_in">Reset</button>
        </form>

        <div class="signup-prompt">
            <p>Remember Password? <a href="SignUp1.php">Login</a></p>
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
        });
    </script>

</body>

</html>
<?php
if ($error) {
    echo '<script>
    Swal.fire({
        position: "top",
        icon: "error",
        title: "Email Id does not exist!",
        showConfirmButton: false,
        timer: 1500
    });
</script>';
}
?>