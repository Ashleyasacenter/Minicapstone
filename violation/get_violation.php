<?php
require_once 'config.php';
require_once 'auth.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    die("Violation ID is required");
}

$violation_id = $_GET['id'];

$stmt = $pdo->prepare("SELECT v.*, s.first_name, s.last_name, s.course, s.year_level, u.username as reported_by_name 
                      FROM violations v 
                      JOIN students s ON v.student_id = s.student_id 
                      JOIN users u ON v.reported_by = u.id 
                      WHERE v.violation_id = ?");
$stmt->execute([$violation_id]);
$violation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$violation) {
    http_response_code(404);
    die("Violation not found");
}

header('Content-Type: application/json');
echo json_encode($violation);
?>