<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link  href="CSS/Regn.css" rel="stylesheet">


</head>

<body>

    <div class="container mt-5">
        <div class="form-container">
            <h2 class="text-center mb-4 text-danger fs-4">CREATE ACCOUNT</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="row mb-3">
                    <div class="col">
                        <label for="firstName" class="form-label text-danger">First Name</label>
                        <input type="text" class="form-control" id="firstName" placeholder="Enter your first name" name="firstname" required>
                    </div>
                    <div class="col">
                        <label for="lastName" class="form-label text-danger">Last Name</label>
                        <input type="text" class="form-control" id="lastName" placeholder="Enter your last name" name="lastname" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label text-danger">Email address</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-danger">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter your password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="Confirm Password" class="form-label text-danger">Confirm Password</label>
                    <input type="password" class="form-control" id="cpassword" placeholder="Confirm your password" name="cpassword" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label text-danger">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" placeholder="Enter your phone number" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="country" class="form-label text-danger">Country</label>
                    <select class="form-select" id="country" name="country" required>
                        <option value="">Select your country</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-danger text-white fw-bold" id="submit" name="submit">Submit</button>
            </form>
            <div class="login-section">
                <p class="text-center mt-4">Already a registered user? <a href="Login.php">Login to continue</a></p>
            </div>
        </div>
    </div>
    <script src="JS/country.js"></script>
    <script src="JS/Regn.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/noumanqamar450/alertbox@main/version/1.0.2/alertbox.min.js"></script>


    <script>

    </script>

</body>

</html>
<?php
include 'Connection/Connect.php';
    

header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $phone = htmlspecialchars($_POST['phone']);
    $country = htmlspecialchars($_POST['country']);
    $password = htmlspecialchars($_POST['password']);
    $cpassword = htmlspecialchars($_POST['cpassword']);
    $email = htmlspecialchars($_POST['email']);

   
    $stmt = $conn->prepare("SELECT * FROM `user_data` WHERE `phone` = :phone");
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    $num = $stmt->rowCount(); 
    
   
    $stmt = $conn->prepare("SELECT * FROM `user_data` WHERE `email` = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $num1 = $stmt->rowCount(); 
    
    if ($num > 0 && $num1 > 0) {
        echo "<script>alertbox.render({
            alertIcon: 'error',
            title: 'Error',
            message: 'Email And Phone Already Exist!',
            btnTitle: 'Ok',
            themeColor: '#000000'
        });</script>";
    } elseif ($num > 0) {
        echo "<script>alertbox.render({
            alertIcon: 'error',
            title: 'Error',
            message: 'Phone Number Already Exists!',
            btnTitle: 'Ok',
            themeColor: '#000000'
        });</script>";
    } elseif ($num1 > 0) {
        echo "<script>alertbox.render({
            alertIcon: 'error',
            title: 'Error',
            message: 'Email Already Exists!',
            btnTitle: 'Ok',
            themeColor: '#000000'
        });</script>";
    } else {
        
        if ($password == $cpassword) {
           
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
          
            $stmt = $conn->prepare("INSERT INTO `user_data` (firstname, lastname, email, phone, country, password) VALUES (:firstname, :lastname, :email, :phone, :country, :password)");
            $stmt->bindParam(':firstname', $firstname);
            $stmt->bindParam(':lastname', $lastname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':country', $country);
            $stmt->bindParam(':password', $hashed_password); 
            
            if ($stmt->execute()) {
                echo "<script>alertbox.render({
                    alertIcon: 'success',
                    title: 'Thank You!',
                    message: 'Registration Successful',
                    btnTitle: 'Ok',
                    themeColor: '#000000'
                });</script>";
            }
        } else {
            echo "<script>alertbox.render({
                alertIcon: 'error',
                title: 'Error',
                message: 'Passwords did not Match!',
                btnTitle: 'Ok',
                themeColor: '#000000'
            });</script>";
        }
    }
}

?>