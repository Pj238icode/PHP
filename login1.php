<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
        <link rel="stylesheet" href="CSS/login1.css">
   
    <title>Login</title>

  
</head>

<body>
    <!-- Spinner HTML -->
        

    <header>
        <?php include_once 'Partials/header1.php'; ?>
    </header>
    <div class="spinner-container" id="spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
  
    <div class="login-container">
        <h2 class="text-center text-primary">Login</h2>
        <form class="login_form" action="Login.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                    required value="<?php
                    if (isset($_COOKIE['email'])) {
                        echo $_COOKIE['email'];
                    }
                    ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Enter your password" value="<?php
                    if (isset($_COOKIE['password'])) {
                        echo $_COOKIE['password'];
                    }
                    ?>" required>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                <label class="form-check-label" for="rememberMe">Remember Me</label>
            </div>

            <div class="forgot-password">
                <a href="Forgot.php">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary mt-4 w-100" id="sign_in">Sign In</button>
        </form>

        <div class="signup-prompt">
            <p>Not a registered user? <a href="SignUp1.php">Sign up here</a></p>
        </div>
    </div>

    <footer>
        <?php include_once 'Partials/Footer.php'; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="JS/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="JS/login1.js"></script>

    <script>
       
    </script>

</body>

</html>
