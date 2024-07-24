<?php
session_start();
include_once 'Connection/Connect.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    unset($_SESSION['password']);
    unset($_SESSION['phone']);
    unset($_SESSION['email']);
    session_destroy();  
    header("location: Login.php");
    exit();
}


$user_id = $_SESSION['user_id'];
$current_session_id = session_id();

$stmt = $conn->prepare("SELECT `session_id` FROM `user_data` WHERE `sno` = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row || $row['session_id'] !== $current_session_id) {
    
   
    session_unset();
    session_destroy();
    header("location: Login.php");
    exit();
}
$user = $_GET['user']; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="CSS/Dashboard.css">
    <link rel="stylesheet" href="CSS/setting.css">
    <link rel="stylesheet" href="CSS/Responsive.css">
</head>

<body>

    <div class="container">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-danger sidebar">
                <div class="position-sticky">
                    <div class="logo">
                        <img src="secure_icon.jpg" alt="Logo">
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="Dashboard.php?user=<?php echo htmlspecialchars($user); ?>">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Profile.php?user=<?php echo htmlspecialchars($user); ?>">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="setting.php?user=<?php echo htmlspecialchars($user); ?>">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="col-md-8 offset-md-2">
                <div class="settings-content">
                    <div class="settings-header">
                        <h2 class="mb-3">Change Password</h2>
                    </div>

                    <form action="setting_update.php?user=<?php echo htmlspecialchars($user); ?>" method="POST">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                        </div>
                        <div class="submit-btn">
                            <button type="submit" class="btn btn-danger">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/noumanqamar450/alertbox@main/version/1.0.2/alertbox.min.js"></script>
    <script>
        
        <?php if (isset($_SESSION['alert']) && !empty($_SESSION['alert'])): ?>
            <?php if ($_SESSION['error'] === false): ?>
                alertbox.render({
                    alertIcon: 'success',
                    title: 'Success!',
                    message: '<?php echo $_SESSION['alert']; ?>',
                    btnTitle: 'Ok',
                    themeColor: '#000000',
                    btnColor: '#7CFC00',
                    btnColor: true
                });
            <?php else: ?>
                alertbox.render({
                    alertIcon: 'error',
                    title: 'Error!',
                    message: '<?php echo $_SESSION['alert']; ?>',
                    btnTitle: 'Ok',
                    themeColor: '#000000',
                    btnColor: '#FF6347',
                    btnColor: true
                });
            <?php endif; ?>
            <?php unset($_SESSION['alert'], $_SESSION['error']); ?>
        <?php endif; ?>
    </script>
</body>

</html>
