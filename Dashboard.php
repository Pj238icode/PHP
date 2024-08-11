<?php
include 'Connection/Connect.php';
session_start();
$id = $_SESSION['user_id'];
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);

    session_destroy();
    header("location: login1.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$current_session_id = session_id();
$stmt = $conn->prepare("SELECT `session_id` FROM `user_info` WHERE `sno` = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row || $row['session_id'] !== $current_session_id) {
    session_unset();
    session_destroy();
    header("location: login1.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="Dashnav.css">
    <link rel="stylesheet" href="Dashboard.css">
    <title>Dashboard</title>

</head>

<body>
    <?php
    include_once 'Partials/Dashnav.php';
    ?>
    <style>
        .spinner-container {
            position: fixed;
            background: #fff;
            color: blue;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            /* Bootstrap modal z-index is 1050 */
            display: flex;
            justify-content: center;
            align-items: center;
            /* Initially hidden */
            height: 250vh;
            width: 250vh;

        }
    </style>





    <!-- Content area for demonstration -->
    <div class="content">
        <h1 class="text-primary fw-bold">Welcome to the Code Forum</h1>
        <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nulla velit mollitia molestiae, nisi magni
            accusamus cumque ipsam distinctio in hic maiores libero, nam provident laudantium inventore. Atque id libero
            animi delectus nam consequuntur unde beatae ipsum rerum. Excepturi facere in illum doloremque quaerat.
            Ipsum, architecto!</p>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum finibus, nisi vel sollicitudin tristique,
            dolor arcu dapibus nisl, vitae tristique elit magna ac nisi.</p>

        <!-- Bootstrap Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <img src="visitor.jpg" class="card-img-top" alt="Number of Users">
                    <div class="card-body">
                        <h5 class="card-title">Number of Registered Users</h5>
                        <p class="card-text text-secondary fw-bolder" id="totaluser">0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <img src="post.jpg" class="card-img-top" alt="Number of Users">
                    <div class="card-body">
                        <h5 class="card-title">Number of Posts</h5>
                        <p class="card-text text-secondary fw-bolder" id="posts">0</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <img src="online.avif" class="card-img-top" alt="Number of Users" height="220rem">
                    <div class="card-body">
                        <h5 class="card-title">Number of Users Online</h5>
                        <p class="card-text text-secondary fw-bolder" id="onlineUsersCount">0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="spinner-container" id="spinner">
            <div class="spinner-border text-primary" role="status">

            </div>
        </div>

        <script src="JS/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="JS/Dashboard.js"></script>
       








</body>

</html>