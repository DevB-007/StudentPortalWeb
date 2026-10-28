<?php
require 'db.php';
header('Content-Type: application/json');

$query = "SELECT id, title, category, event_date as date, description, poster_image FROM events ORDER BY event_date DESC";
$result = $conn->query($query);

$events = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

echo json_encode($events);
$conn->close();
?>