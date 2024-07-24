        <?php
        // Include database connection
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






        // Assuming $_GET['user'] contains the user ID
        $user = isset($_GET['user']) ? $_GET['user'] : null;

        if (!$user) {
            // Handle case where user ID is not provided
            die("User ID not provided.");
        }

        $query = $conn->prepare("SELECT * FROM `user_data` WHERE `sno`=:user");
        $query->bindParam(':user', $user, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            // Handle case where no user data found
            die("User data not found.");
        }

        $firstname = $result['firstname'];
        $lastname = $result['lastname'];
        $email = $result['email'];
        $phone = $result['phone'];
        $country = $result['country'];
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>User Profile</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            <link rel="stylesheet" href="CSS/Dashboard.css">
            <link rel="stylesheet" href="CSS/profile.css">
            <link rel="stylesheet" href="CSS/Responsive.css">
          

        </head>
      

        <body>
           
            <div class="container">
                <div class="row">
                    <!-- Sidebar -->
                    <nav id="sidebar" class="col-sm-3 col-lg-2 d--block bg-danger sidebar">
                        <div class="position-sticky">
                            <div class="logo">
                                <img src="secure_icon.jpg" alt="Logo">
                            </div>
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="Dashboard.php?user=<?php echo $user; ?>">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="profile.php?user=<?php echo $user; ?>">
                                        <i class="fas fa-user"></i> Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="setting.php?user=<?php echo $user; ?>">
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

                    <!-- Main Content -->
                    <div class="col-md-9">
                        <div class="profile-content">
                            <div class="profile-header">
                                <h2>User Profile</h2>
                            </div>
                            <div class="profile-details">
                                <div class="row">
                                    <div class="col-sm-3  text-danger">Name:</div>
                                    <div class="col-sm-9"><?php echo $firstname . " " . $lastname; ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3  text-danger">Email:</div>
                                    <div class="col-sm-9"><?php echo $email; ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3  text-danger">Phone:</div>
                                    <div class="col-sm-9"><?php echo $phone; ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 text-danger">Country:</div>
                                    <div class="col-sm-9"><?php echo $country; ?></div>
                                </div>
                            </div>
                            <div class="edit-profile-btn">
                                <a class="btn btn-danger btn-sm" id="editProfileBtn">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            include_once 'Modal_Profile.php';
            ?>
            <!-- Bootstrap Bundle with Popper -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
            <!-- Font Awesome JS -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
            <script src="profile.js"></script>



        </body>

        </html>