<?php
require_once 'config.php';

// Register new user
function registerUser($username, $email, $password, $role = 'staff') {
    global $pdo;
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$username, $email, $hashed_password, $role]);
}

// Login user
function loginUser($username, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        return true;
    }
    return false;
}

// Get all students
function getStudents() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM students ORDER BY last_name, first_name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Add new student
function addStudent($student_id, $first_name, $last_name, $course, $year_level, $contact_number = null, $email = null) {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO students (student_id, first_name, last_name, course, year_level, contact_number, email) 
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$student_id, $first_name, $last_name, $course, $year_level, $contact_number, $email]);
}

// Add new violation
function addViolation($student_id, $violation_type, $violation_date, $description, $reported_by) {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO violations (student_id, violation_type, violation_date, description, reported_by) 
                          VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$student_id, $violation_type, $violation_date, $description, $reported_by]);
}

// Get all violations
function getViolations() {
    global $pdo;
    $stmt = $pdo->query("SELECT v.*, s.first_name, s.last_name, s.course, s.year_level, u.username as reported_by_name 
                         FROM violations v 
                         JOIN students s ON v.student_id = s.student_id 
                         JOIN users u ON v.reported_by = u.id 
                         ORDER BY v.violation_date DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Update violation status
function updateViolationStatus($violation_id, $status, $resolution_details = null) {
    global $pdo;
    
    $resolution_date = ($status === 'resolved') ? date('Y-m-d') : null;
    
    $stmt = $pdo->prepare("UPDATE violations 
                          SET status = ?, resolution_details = ?, resolution_date = ? 
                          WHERE violation_id = ?");
    return $stmt->execute([$status, $resolution_details, $resolution_date, $violation_id]);
}
?>