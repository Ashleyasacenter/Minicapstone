<?php
require_once '..config.php';
require_once '..auth.php';
require_once '..functions.php';

requireLogin();

$error = '';
$success = '';

// Add new violation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_violation'])) {
    $student_id = trim($_POST['student_id']);
    $violation_type = trim($_POST['violation_type']);
    $violation_date = trim($_POST['violation_date']);
    $description = trim($_POST['description']);
    $reported_by = $_SESSION['user_id'];
    
    if (empty($student_id) || empty($violation_type) || empty($violation_date) || empty($description)) {
        $error = 'Required fields are missing';
    } else {
        if (addViolation($student_id, $violation_type, $violation_date, $description, $reported_by)) {
            $success = 'Violation recorded successfully';
        } else {
            $error = 'Failed to record violation';
        }
    }
}

// Update violation status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $violation_id = $_POST['violation_id'];
    $status = $_POST['status'];
    $resolution_details = trim($_POST['resolution_details']);
    
    if (updateViolationStatus($violation_id, $status, $resolution_details)) {
        $success = 'Violation status updated successfully';
    } else {
        $error = 'Failed to update violation status';
    }
}

// Get all violations
$violations = getViolations();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CICS - Violations</title>
    <link rel="stylesheet" href="..dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '..sidebar.php'; ?>
        
        <div class="main-content">
            <header>
                <h1>Violation Management</h1>
                <div class="user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="../logout.php" class="logout-btn">Logout</a>
                </div>
            </header>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <div class="content-section">
                <button id="addViolationBtn" class="btn">Record New Violation</button>
                
                <!-- Add Violation Modal -->
                <div id="addViolationModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Record New Violation</h2>
                        <form action="violations.php" method="post">
                            <div class="form-group">
                                <label for="student_id">Student ID</label>
                                <input type="text" id="student_id" name="student_id" required>
                            </div>
                            <div class="form-group">
                                <label for="violation_type">Violation Type</label>
                                <select id="violation_type" name="violation_type" required>
                                    <option value="Academic Dishonesty">Academic Dishonesty</option>
                                    <option value="Classroom Misconduct">Classroom Misconduct</option>
                                    <option value="Disruptive Behavior">Disruptive Behavior</option>
                                    <option value="Dress Code Violation">Dress Code Violation</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="violation_date">Date</label>
                                <input type="date" id="violation_date" name="violation_date" required value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="4" required></textarea>
                            </div>
                            <button type="submit" name="add_violation" class="btn">Record Violation</button>
                        </form>
                    </div>
                </div>
                
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Violation Type</th>
                                <th>Date</th>
                                <th>Reported By</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($violations as $violation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($violation['first_name'] . ' ' . $violation['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($violation['violation_type']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($violation['violation_date'])); ?></td>
                                <td><?php echo htmlspecialchars($violation['reported_by_name']); ?></td>
                                <td><span class="status-badge <?php echo $violation['status']; ?>"><?php echo ucfirst($violation['status']); ?></span></td>
                                <td>
                                    <a href="#" class="btn-view" onclick="viewViolation(<?php echo $violation['violation_id']; ?>)">View</a>
                                    <?php if ($violation['status'] === 'pending'): ?>
                                        <a href="#" class="btn-edit" onclick="editViolation(<?php echo $violation['violation_id']; ?>)">Update</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View Violation Modal -->
    <div id="viewViolationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Violation Details</h2>
            <div id="violationDetails"></div>
        </div>
    </div>
    
    <!-- Update Violation Modal -->
    <div id="updateViolationModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Update Violation Status</h2>
            <form action="violations.php" method="post">
                <input type="hidden" id="update_violation_id" name="violation_id">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="pending">Pending</option>
                        <option value="resolved">Resolved</option>
                        <option value="escalated">Escalated</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="resolution_details">Resolution Details</label>
                    <textarea id="resolution_details" name="resolution_details" rows="4"></textarea>
                </div>
                <button type="submit" name="update_status" class="btn">Update Status</button>
            </form>
        </div>
    </div>
    
    <script src="..script.js"></script>
    <script>
    function viewViolation(violationId) {
        fetch(`get_violation.php?id=${violationId}`)
            .then(response => response.json())
            .then(data => {
                const details = `
                    <p><strong>Student:</strong> ${data.first_name} ${data.last_name} (${data.student_id})</p>
                    <p><strong>Course/Year:</strong> ${data.course} - Year ${data.year_level}</p>
                    <p><strong>Violation Type:</strong> ${data.violation_type}</p>
                    <p><strong>Date:</strong> ${new Date(data.violation_date).toLocaleDateString()}</p>
                    <p><strong>Reported By:</strong> ${data.reported_by_name}</p>
                    <p><strong>Status:</strong> <span class="status-badge ${data.status}">${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</span></p>
                    <p><strong>Description:</strong><br>${data.description}</p>
                    ${data.resolution_details ? `<p><strong>Resolution Details:</strong><br>${data.resolution_details}</p>` : ''}
                `;
                document.getElementById('violationDetails').innerHTML = details;
                document.getElementById('viewViolationModal').style.display = 'block';
            });
    }
    
    function editViolation(violationId) {
        document.getElementById('update_violation_id').value = violationId;
        document.getElementById('updateViolationModal').style.display = 'block';
    }
    </script>
</body>
</html>