<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../pages/login.php?status=unauthorized");
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $event_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT poster_image FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $stmt->bind_result($poster_image);
    $stmt->fetch();
    $stmt->close();

    $stmt_delete = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt_delete->bind_param("i", $event_id);
    
    if ($stmt_delete->execute()) {
        if ($poster_image && file_exists("../assets/uploads/posters/" . $poster_image)) {
            unlink("../assets/uploads/posters/" . $poster_image);
        }
        header("Location: ../pages/admin_events.php?status=deleted");
    } else {
        header("Location: ../pages/admin_events.php?status=error&msg=Failed to delete event.");
    }
    $stmt_delete->close();
} else {
    header("Location: ../pages/admin_events.php");
}
$conn->close();
?>