<nav class="navbar navbar-custom bg-white">
    <div class="container-fluid flex-column resp_nav">
        <h2 class="navbar-brand text-primary">Code Forum</h2>
        <ul class="navbar-nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-secondary <?php echo ($currentPage == 'Dashboard') ? 'active' : ''; ?>" href="Dashboard.php?user=<?php echo $id; ?>" id="dashboard">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-secondary <?php echo ($currentPage == 'Forum') ? 'active' : ''; ?>" href="Forum.php?user=<?php echo $id; ?>" id="forum">
                    <i class="fas fa-comments"></i> Our Forums
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-secondary <?php echo ($currentPage == 'Profile') ? 'active' : ''; ?>" href="profile.php?user=<?php echo $id; ?>" id="Profile">
                    <i class="fas fa-user"></i> Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-secondary" href="Logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</nav>
