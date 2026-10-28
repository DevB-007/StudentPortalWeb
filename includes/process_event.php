<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php?status=unauthorized");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = filter_var(trim($_POST["title"]), FILTER_SANITIZE_STRING);
    $category = filter_var(trim($_POST["category"]), FILTER_SANITIZE_STRING);
    $event_date = $_POST["event_date"];
    $description = filter_var(trim($_POST["description"]), FILTER_SANITIZE_STRING);

    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['poster']['tmp_name'];
        $fileName = $_FILES['poster']['name'];
        $fileSize = $_FILES['poster']['size'];
        
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        $allowedExts = ['jpg', 'jpeg', 'png'];
        $maxSize = 2 * 1024 * 1024; 

        if (!in_array($fileExtension, $allowedExts)) {
            header("Location: ../pages/admin_events.php?status=error&msg=Invalid file type. Only JPG and PNG allowed.");
            exit();
        }

        if ($fileSize > $maxSize) {
            header("Location: ../pages/admin_events.php?status=error&msg=File exceeds 2MB limit.");
            exit();
        }

        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = '../assets/uploads/posters/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $stmt = $conn->prepare("INSERT INTO events (title, category, event_date, description, poster_image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $title, $category, $event_date, $description, $newFileName);

            if ($stmt->execute()) {
                header("Location: ../pages/admin_events.php?status=added");
            } else {
                header("Location: ../pages/admin_events.php?status=error&msg=Database insertion failed.");
            }
            $stmt->close();
        } else {
            header("Location: ../pages/admin_events.php?status=error&msg=Error moving uploaded file.");
        }
    } else {
        header("Location: ../pages/admin_events.php?status=error&msg=Please upload an image.");
    }
}
$conn->close();
?>