<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php?status=unauthorized");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = filter_var(trim($_POST["title"]), FILTER_SANITIZE_STRING);
    $category = filter_var(trim($_POST["category"]), FILTER_SANITIZE_STRING);
    $event_date = $_POST["event_date"];
    $description = filter_var(trim($_POST["description"]), FILTER_SANITIZE_STRING);
    $current_poster = $_POST['current_poster'];
    
    $newFileName = $current_poster; 

    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['poster']['tmp_name'];
        $fileName = $_FILES['poster']['name'];
        $fileSize = $_FILES['poster']['size'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowedExts = ['jpg', 'jpeg', 'png'];
        if (in_array($fileExtension, $allowedExts) && $fileSize <= 2097152) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = '../assets/uploads/posters/' . $newFileName;
            
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                if ($current_poster && file_exists('../assets/uploads/posters/' . $current_poster)) {
                    unlink('../assets/uploads/posters/' . $current_poster);
                }
            } else {
                $newFileName = $current_poster; 
            }
        }
    }

    $stmt = $conn->prepare("UPDATE events SET title=?, category=?, event_date=?, description=?, poster_image=? WHERE id=?");
    $stmt->bind_param("sssssi", $title, $category, $event_date, $description, $newFileName, $id);

    if ($stmt->execute()) {
        header("Location: ../pages/admin_events.php?status=updated");
    } else {
        header("Location: ../pages/admin_events.php?status=error");
    }
    $stmt->close();
}
$conn->close();
?>