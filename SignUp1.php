<?php
include 'Connection/Connect.php';
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
?>
<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
    <link rel="stylesheet" href="CSS/SignUp.css">
    <title>Sign Up</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
   
    <header>
        <?php include_once 'Partials/header1.php'; ?>
    </header>

    <div class="container">
        <div class="spinner-container" id="spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <main>
            <div class="signup-container">
                <h2 class="signup-heading text-primary">Sign Up</h2>
                <form class="signup-form" action="Signup.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="username" class="form-label text-primary">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                            placeholder="Enter your username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label text-primary">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-primary">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter your password" required>
                    </div>
                    <div class="mb-3">
                        <label for="profile-picture" class="form-label text-primary">Profile Picture</label>
                        <input type="file" class="form-control" name="image" id="profile-picture" accept="image/*">
                    </div>
                    <div class="g-recaptcha" data-sitekey="6Lf3IhoqAAAAAE-aACMHiA1TIa2H-pr3N6cDDOfH"></div>
                    <button type="submit" class="btn btn-primary mt-4">Sign Up</button>
                </form>
                <div class="sign-in-link">
                    <p>Already a registered user? <a href="login1.php" id="sign-in" class="text-primary">Sign In</a>
                    </p>
                </div>
            </div>
        </main>
    </div>

    <div class="container-">
        <?php include_once 'Partials/Footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script
        src="https://www.google.com/recaptcha/enterprise.js?render=6Lf3IhoqAAAAAE-aACMHiA1TIa2H-pr3N6cDDOfH"></script>
    <script src="JS/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#spinner').fadeOut();
            $('.signup-form').on('submit', function (event) {
                event.preventDefault();
                const recaptchaResponse = grecaptcha.getResponse();
                if (recaptchaResponse.length === 0) {
                    Swal.fire({
                        icon: "error",
                        title: "Captcha not verified!",
                        text: "Please complete the reCAPTCHA.",
                        showConfirmButton: true,
                    });
                    return;
                }
                var formData = new FormData(this);
                formData.append('g-recaptcha-response', recaptchaResponse);
                $.ajax({
                    url: 'AJAX/signup.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        try {
                            const response = JSON.parse(data);
                            if (response.success) {
                                Swal.fire({
                                    position: "top-end",
                                    icon: "success",
                                    title: response.message || 'Registered Successfully',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                $('.signup-form')[0].reset();
                                var id = response.user_id;
                                setTimeout(() => {
                                    window.location.href = "Dashboard.php?user=" + id;
                                }, 1500);
                            } else {
                                $('.signup-form')[0].reset();
                                grecaptcha.reset();
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: response.message || 'An unknown error occurred. Please try again.',
                                    showConfirmButton: true,
                                });
                            }
                        } catch (error) {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: 'An error occurred while processing your request. Please try again.',
                                showConfirmButton: true,
                            });
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: "error",
                            title: "Submission Error",
                            text: "An error occurred. Please try again.",
                            showConfirmButton: true,
                        });
                    }
                });
            });
            window.onpageshow = function (event) {
                if (event.persisted) {
                    window.location.reload();
                }
            };
        });
    </script>

</body>

</html>
<?php
?>
