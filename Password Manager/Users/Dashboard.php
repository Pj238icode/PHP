<?php
include_once 'Connection/Connect.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    unset($_SESSION['password']);
    unset($_SESSION['phone']);
    unset($_SESSION['email']);
    session_destroy();  

    header("location: Login.php");
    exit();
}
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Unset to prevent displaying again on refresh
}
if (isset($_SESSION['delete'])) {
    $delete = $_SESSION['delete'];
    unset($_SESSION['delete']); // Unset to prevent displaying again on refresh
}
if (isset($_SESSION['add_user'])) {
    $add_user = $_SESSION['add_user'];
    unset($_SESSION['add_user']); // Unset to prevent displaying again on refresh
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

$user = $_SESSION['user_id'];
$firstname = $_SESSION['firstname'];
$lastname = $_SESSION['lastname'];

// Pagination logic
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$results_per_page = 5; // Number of records to show per page
$offset = ($page - 1) * $results_per_page;

// Query to fetch data with pagination
$stmt = $conn->prepare("SELECT * FROM `user_entries` WHERE `user_id` = :user_id LIMIT :offset, :results_per_page");
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':results_per_page', $results_per_page, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total records
$total_records_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM `user_entries` WHERE `user_id` = :user_id");
$total_records_stmt->bindParam(':user_id', $user_id);
$total_records_stmt->execute();
$total_records = $total_records_stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Calculate total pages
$total_pages = ceil($total_records / $results_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="CSS/Dashboard.css">
    <link rel="stylesheet" href="CSS/sidebar.css">
    <link rel="stylesheet" href="CSS/Responsive.css">

</head>

<body>

    <div id="sidebar">
        <div class="logo">
            <img src="secure_icon.jpg" alt="Logo">
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="Dashboard.php?user=<?php echo $user; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="profile.php?user=<?php echo $user; ?>">
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

    <div id="main-content">
        <div class="dashboard-content">
            <div class="dashboard-header d-flex justify-content-between align-items-center">
                <h2 class="dashboard-title text-danger">User Dashboard</h2>
                <a class="btn btn-danger add-button" id="openAddUserModal"><i class="fas fa-plus"></i> Add
                    User</a>
            </div>
            <div class="table-container">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Sno</th>
                            <th scope="col">Name</th>
                            <th scope="col">Password</th>
                            <th scope="col">URL</th>
                            <th scope="col">Description</th>
                            <th scope="col">Edit/Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?php echo $row['sno']; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td>
                                    <input type="password" class="form-control password-input"
                                        value="<?php echo htmlspecialchars($row['password']); ?>" disabled>
                                    <span class="toggle-password" onclick="togglePasswordVisibility(this)">
                                        <i class="fas fa-eye" style="cursor: pointer;"></i>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($row['url']); ?></td>
                                <td><?php echo htmlspecialchars($row['desc1']); ?></td>
                                <td>
                                    <a href="#" class="btn btn-warning btn-sm edit-entry" data-bs-toggle="modal"
                                        data-bs-target="#editUserModal"
                                        data-id="<?php echo htmlspecialchars($row['sno']) ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="#" class="btn btn-danger btn-sm delete-entry"
                                        data-id="<?php echo htmlspecialchars($row['sno']) ?>" onclick="confirmDelete()">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo ($page <= 1) ? '#' : '?page=' . ($page - 1); ?>"
                            aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                            <a class="page-link"
                                href="<?php echo ($page == $i) ? '#' : '?page=' . $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                        <a class="page-link"
                            href="<?php echo ($page >= $total_pages) ? '#' : '?page=' . ($page + 1); ?>"
                            aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>


    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom JavaScript -->
    <script src="Dashboard.js"></script>
    <!--Add User Modal-->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" action="addUser.php?user=<?php
                    echo $user;
                    ?>" method="post">
                        <div class="mb-3">
                            <label for="newUserName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="newUserName" name="newUserName" required>
                        </div>
                        <div class="mb-3">
                            <label for="newUserPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="newUserPassword" name="newUserPassword"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="newUserURL" class="form-label">URL</label>
                            <input type="text" class="form-control" id="newUserURL" name="newUserURL">
                        </div>
                        <div class="mb-3">
                            <label for="newUserDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="newUserDescription" name="newUserDescription"
                                rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger" id="add_data">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" action="EditEntry.php?user=<?php $user_id ?>" method="post">
                        <input type="hidden" id="editSno" name="editSno" value="<?php echo $row['sno']; ?>">
                        <div class="mb-3">
                            <label for="editUserName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editUserName" name="editUserName"
                                value="<?php echo htmlspecialchars($row['username']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="editUserPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="editUserPassword" name="editUserPassword"
                                value="<?php echo htmlspecialchars($row['password']); ?>">

                        </div>
                        <div class="mb-3">
                            <label for="editUserURL" class="form-label">URL</label>
                            <input type="text" class="form-control" id="editUserURL" name="editUserURL"
                                value="<?php echo htmlspecialchars($row['url']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="editUserDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editUserDescription" name="editUserDescription"
                                rows="3"> <?php echo htmlspecialchars($row['desc1']); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



</body>

</html>
<?php
if (isset($success_message)) {
    echo '<script>
    Swal.fire({
    position: "top",
    icon: "success",
    title: "Edited Successfully",
    showConfirmButton: false,
    timer: 1500
  });
 
 </script> ';


}
if (isset($delete)) {
    echo '<script>
    Swal.fire({
    position: "top",
    icon: "success",
    title: "Deleted Successfully",
    showConfirmButton: false,
    timer: 1500
  });
 
 </script> ';


}
if (isset($add_user)) {
    echo '<script>
    Swal.fire({
    position: "top",
    icon: "success",
    title: "User Added Successfully",
    showConfirmButton: false,
    timer: 1500
  });
 
 </script> ';


}



?>