<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="Regn.css">
    <link rel="stylesheet" href="login.css">

    <style>


    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="form-container">
            <h2 class="text-center mb-4 text-danger fs-4">LOGIN TO YOUR ACCOUNT</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label text-danger">Email address</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email" value="<?php
                    if(isset($_COOKIE['email'])){
                        echo $_COOKIE['email'];
                    }
                    ?>" name="email"
                        required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label text-danger">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" placeholder="Enter your phone number" value="<?php if(isset($_COOKIE['phone'])){
                        echo $_COOKIE['phone'];
                    }?>" name="phone"
                        required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-danger">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter your password" value="<?php if(isset($_COOKIE['password'])){
                        echo $_COOKIE['password'];
                    }?>"
                        name="password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-danger text-white fw-bold" id="submit" name="submit">Login</button>
            </form>
            <div class="register-section">
                <p class="text-center mt-4"><a href="Reset.php" class="text-danger fw-bold">Forgot Password</a> </p>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/noumanqamar450/alertbox@main/version/1.0.2/alertbox.min.js"></script>
</body>

</html>
<?php

include 'Connection/Connect.php';


session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $phone = htmlspecialchars($_POST['phone']);

    try {
        $stmt = $conn->prepare("SELECT * FROM `user_data` WHERE `phone` = :phone AND `email` = :email");
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $row_count = $stmt->rowCount();

        if ($row_count == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stored_password = $row['password'];

            if (password_verify($password, $stored_password)) {

                session_regenerate_id(true);

                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $row['sno'];
                $_SESSION['firstname'] = $row['firstname'];
                $_SESSION['lastname'] = $row['lastname'];
                if(isset($_POST['remember'])){
                    $remember=htmlspecialchars($_POST['remember']);
                    setcookie("email", $email, time() + (86400 * 1));
                    setcookie("password", $password, time() + (86400 * 1));
                    setcookie("phone", $phone, time() + (86400 * 1));

                }
                else{
                    setcookie("email", "", time() - (86400 * 1));
                    setcookie("password", "", time() - (86400 * 1));
                    setcookie("phone", "", time() - (86400 * 1  ));
                }


                $session_id = session_id();
                $update_stmt = $conn->prepare("UPDATE `user_data` SET `session_id` = :session_id WHERE `sno` = :user_id");
                $update_stmt->bindParam(':session_id', $session_id);
                $update_stmt->bindParam(':user_id', $row['sno']);
                $update_stmt->execute();

                header("location: Dashboard.php?user=" . $_SESSION['user_id']);
                exit();
            } else {
                echo "<script>alertbox.render({alertIcon: 'error', title: 'Error', message: 'Incorrect password!', btnTitle: 'Ok', themeColor: '#000000'});</script>";
            }
        } else {
            echo "<script>alertbox.render({alertIcon: 'error', title: 'Error', message: 'Invalid email or phone number!', btnTitle: 'Ok', themeColor: '#000000'});</script>";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>