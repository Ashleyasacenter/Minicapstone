<?php
require_once 'auth.php';
?>
<aside class="sidebar">
    <div class="logo">
    <img src="cics.png" alt="CICS Logo" style="max-width: 30%; height: 50; ">
    </div>
    
    <nav>
        <ul>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) === 'students.php' ? 'active' : ''; ?>">
                <a href="students.php"><i class="fas fa-users"></i> Students</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) === 'violations.php' ? 'active' : ''; ?>">
                <a href="violations.php"><i class="fas fa-exclamation-triangle"></i> Violations</a>
            </li>
            <li class="<?php echo basename($_SERVER['PHP_SELF']) === 'profile.php' ? 'active' : ''; ?>">
                <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
            </li>
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <li>
                <a href="users.php"><i class="fas fa-user-cog"></i> User Management</a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
</aside>