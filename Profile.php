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

$id = $_GET['user'] ?? 0;

// Fetch user information
$q = $conn->prepare("SELECT * FROM `user_info` WHERE `sno` = :id");
$q->bindParam(':id', $id, PDO::PARAM_INT);
$q->execute();

$row = $q->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    die("User not found");
}

$name = htmlspecialchars($row['username']);
$email = htmlspecialchars($row['email']);
$joined = htmlspecialchars($row['TimeStamp']);
$image = htmlspecialchars($row['image']);
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
    <link rel="stylesheet" href="CSS/Profile.css">
    <title>User Profile</title>
    <style>
        
    </style>
</head>
<body>
    <?php include_once 'C:\xampp\htdocs\Forum\Partials\Dashnav.php'; ?>

    <!-- Profile Content -->
    <div class="content">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Single Large Profile Card -->
                <div class="card profile-card">
                    <div class="card-body text-center">
                        <img src="Images/<?php echo $image; ?>" alt="Profile Picture" class="profile-img">
                        <h1 class="card-title text-primary"><?php echo $name; ?></h1>
                        <p class="card-text text-secondary"><strong>Email:</strong>
                            <?php echo htmlspecialchars($email); ?></p>
                        <p class="card-text text-secondary"><strong>Joined:</strong>
                            <?php echo substr($joined, 0, 10); ?></p>

                        <a class="btn btn-secondary btn-custom" id="modal_edit">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <?php include_once 'Profile_Modal.php'; ?>

    <div class="container">
        <div class="spinner-container" id="spinner">
            <div class="spinner-border text-primary" role="status"></div>
        </div>

        <!-- JavaScript Libraries -->
        <script src="JS/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
    $(window).on('load', function () {
        $("#spinner").fadeOut();
    });

    document.getElementById('modal_edit').addEventListener('click', function (e) {
        e.preventDefault();
        $('#editProfileModal').modal('show');
    });

    <?php if (isset($_SESSION['upload']) || isset($_SESSION['email_err'])): ?>
        var uploadSuccess = <?php echo json_encode($_SESSION['upload']); ?>;
        var emailError = <?php echo json_encode($_SESSION['email_err'] ?? false); ?>;
        <?php unset($_SESSION['upload']); ?>
        <?php unset($_SESSION['email_err']); ?>

        if (uploadSuccess) {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Your Profile has been updated Successfully!",
                showConfirmButton: false,
                timer: 1500
            });
        } else if (emailError) {
            Swal.fire({
                position: "top-end",
                icon: "info",
                title: "Email Id already exists!",
                showConfirmButton: false,
                timer: 1500
            });
        } else {
            Swal.fire({
                position: "top-end",
                icon: "error",
                title: "Error in Updating Profile",
                showConfirmButton: false,
                timer: 1500
            });
        }
    <?php endif; ?>
</script>

    </div>
</body>
</html>
