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
$forums_stmt = $conn->prepare("SELECT * FROM `forums`");
$forums_stmt->execute();
$forums = $forums_stmt->fetchAll(PDO::FETCH_ASSOC);



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="CSS/Dashnav.css">
    <link rel="stylesheet" href="CSS/Dashboard.css">
    <title>Dashboard</title>
    <style>
        .spinner-container {
            position: fixed;
            background: #fff;
            color: blue;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100vw;
        }
    </style>
</head>

<body>
    <?php include_once 'Partials/Dashnav.php'; ?>

    <div class="content">
        <div class="row">
            <?php foreach ($forums as $forum): ?>
                <div class="col-md-4">
                    <div class="card text-center">
                        <a href="comments.php?forum=<?php echo urlencode($forum['name']); ?>&id=<?php echo urlencode($forum['sno']); ?>&user=<?php echo $id; ?>">
                            <img src="Images/<?php echo htmlspecialchars($forum['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($forum['name']); ?>" height="250rem">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="comments.php?forum=<?php echo urlencode($forum['name']); ?>&id=<?php echo urlencode($forum['sno']); ?>&user=<?php echo $id; ?>">
                                    <?php echo htmlspecialchars($forum['name']); ?>
                                </a>
                            </h5>
                         
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container">
        <div class="spinner-container" id="spinner">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
      
        <script>
            $(document).ready(function () {
                $("#spinner").fadeOut();
            });
        </script>
    </div>
</body>

</html>
