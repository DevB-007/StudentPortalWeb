<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php?status=unauthorized");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $full_name = filter_var(trim($_POST["full_name"]), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $mobile = filter_var(trim($_POST["mobile"]), FILTER_SANITIZE_STRING);
    $course = filter_var(trim($_POST["course"]), FILTER_SANITIZE_STRING);
    $enroll_year = (int)$_POST["enroll_year"];

    $stmt = $conn->prepare("UPDATE students SET full_name=?, email=?, mobile=?, course=?, enroll_year=? WHERE id=? AND role='student'");
    $stmt->bind_param("ssssii", $full_name, $email, $mobile, $course, $enroll_year, $id);

    if ($stmt->execute()) {
        header("Location: ../pages/admin_students.php?status=updated");
    } else {
        header("Location: ../pages/admin_students.php?status=error");
    }
    $stmt->close();
}
$conn->close();
?>