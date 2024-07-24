<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Reset.css">
</head>

<body>

    <div class="container">
        <h2 class="text-center mb-4 text-danger">Reset Password</h2>
        <form  method="post">
            <div class="form-group">
                <label for="email" class="text-danger">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="btn btn-danger btn-send-otp">Send OTP</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
<?php

include_once 'Connection/Connect.php';
   
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $sql =$conn->prepare( "SELECT * FROM `user_data` WHERE email = :email");
    $sql->bindParam(':email', $email,PDO::PARAM_STR);
    $sql->execute();
    $result = $sql->fetch(PDO::FETCH_ASSOC);
    if($result>0){
        session_start();
        $_SESSION['email'] = $email;
        header("Location:Validate_Login.php");

    }
    else{
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
    
  

   }

  
?>
