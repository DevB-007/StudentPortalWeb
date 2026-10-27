<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = filter_var(trim($_POST["name"]), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = filter_var(trim($_POST["message"]), FILTER_SANITIZE_STRING);

    if (empty($name) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../pages/contact.html?status=error");
        exit();
    }

    $fileData = [
        date("Y-m-d H:i:s"),
        $name,
        $email,
        str_replace(array("\r", "\n"), ' ', $message)
    ];

    $filePath = "../assets/data/messages.csv";
    $fileExists = file_exists($filePath);

    $file = fopen($filePath, "a");

    if ($file) {
        if (!$fileExists) {
            fputcsv($file, ["Timestamp", "Name", "Email", "Message"]);
        }
        
        fputcsv($file, $fileData);
        fclose($file);
        
        header("Location: ../pages/contact.html?status=success");
        exit();
    } else {
        header("Location: ../pages/contact.html?status=error");
        exit();
    }
} else {
    header("Location: ../pages/contact.html");
    exit();
}
?>