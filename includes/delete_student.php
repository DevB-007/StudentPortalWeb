<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.html?status=unauthorized");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $student_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM students WHERE id = ? AND role = 'student'");
    $stmt->bind_param("i", $student_id);
    
    if ($stmt->execute()) {
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