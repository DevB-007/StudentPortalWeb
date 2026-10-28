<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php?status=unauthorized");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $student_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM students WHERE id = ? AND role = 'student'");
    $stmt->bind_param("i", $student_id);
    
    if ($stmt->execute()) {
        // Generate Audit Log
        $action = "Deleted Student ID: " . $student_id;
        $admin_name = $_SESSION['full_name'];
        $log_stmt = $conn->prepare("INSERT INTO audit_logs (action, user_name) VALUES (?, ?)");
        $log_stmt->bind_param("ss", $action, $admin_name);
        $log_stmt->execute();
        $log_stmt->close();
        
        header("Location: ../pages/admin_students.php?status=deleted");
    } else {
        header("Location: ../pages/admin_students.php?status=error");
    }
    $stmt->close();
} else {
    header("Location: ../pages/admin_students.php");
}
$conn->close();
exit();
?>