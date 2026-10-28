<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $fullName = filter_var(trim($_POST["fullName"]), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $mobile = filter_var(trim($_POST["mobile"]), FILTER_SANITIZE_STRING);
    $course = filter_var(trim($_POST["course"]), FILTER_SANITIZE_STRING);
    $year = (int)$_POST["year"];
    $gender = filter_var(trim($_POST["gender"]), FILTER_SANITIZE_STRING);
    $password = $_POST["password"];

    if (empty($fullName) || empty($email) || empty($password) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../pages/register.php?status=error&msg=invalid_input");
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        header("Location: ../pages/register.php?status=error&msg=duplicate_email");
        exit();
    }
    $stmt->close();

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $insertStmt = $conn->prepare("INSERT INTO students (full_name, email, mobile, course, enroll_year, gender, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertStmt->bind_param("ssssiss", $fullName, $email, $mobile, $course, $year, $gender, $hashedPassword);

    if ($insertStmt->execute()) {
        $insertStmt->close();
        header("Location: ../pages/register.php?status=success");
        exit();
    } else {
        $insertStmt->close();
        header("Location: ../pages/register.php?status=error&msg=db_error");
        exit();
    }

} else {
    header("Location: ../pages/register.php");
    exit();
}
?>