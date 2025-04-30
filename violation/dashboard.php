<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'functions.php';

requireLogin();

// Get stats for dashboard
$student_count = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$violation_count = $pdo->query("SELECT COUNT(*) FROM violations")->fetchColumn();
$pending_violations = $pdo->query("SELECT COUNT(*) FROM violations WHERE status = 'pending'")->fetchColumn();

// Get recent violations
$recent_violations = $pdo->query("SELECT v.*, s.first_name, s.last_name 
                                 FROM violations v 
                                 JOIN students s ON v.student_id = s.student_id 
                                 ORDER BY v.violation_date DESC 
                                 LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICS - Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    
</head>
<body>
    <div class="dashboard-container">
        <?php include 'sidebar.php'; ?>
        
        <div class="main-content">
            <header>
                <h1>Dashboard</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </header>
            
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Students</h3>
                    <p><?php echo $student_count; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Total Violations</h3>
                    <p><?php echo $violation_count; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Pending Violations</h3>
                    <p><?php echo $pending_violations; ?></p>
                </div>
            </div>
            
            <div class="recent-violations">
                <h2>Recent Violations</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Violation Type</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_violations as $violation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($violation['first_name'] . ' ' . htmlspecialchars($violation['last_name'])); ?></td>
                            <td><?php echo htmlspecialchars($violation['violation_type']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($violation['violation_date'])); ?></td>
                            <td><span class="status-badge <?php echo $violation['status']; ?>"><?php echo ucfirst($violation['status']); ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="violations.php" class="view-all">View All Violations</a>
            </div>
        </div>
    </div>
</body>
</html>