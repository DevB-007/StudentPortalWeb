<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?status=unauthorized");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_events.php");
    exit();
}

$event_id = $_GET['id'];
$stmt = $conn->prepare("SELECT title, category, event_date, description, poster_image FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: admin_events.php");
    exit();
}
$event = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="logo"><img src="../assets/images/logo.png" alt="Logo" width="150"></div>
        <nav>
            <ul>
                <li><a href="admin_events.php">Back to Events</a></li>
            </ul>
        </nav>
    </header>
    <main id="main-content">
        <section class="content-section">
            <h2>Edit Event Details</h2>
            <div style="background-color: var(--bg-light); padding: 1.5rem; border: 1px solid var(--border); border-radius: 4px;">
                <form action="../includes/process_edit_event.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $event_id; ?>">
                    <input type="hidden" name="current_poster" value="<?php echo htmlspecialchars($event['poster_image']); ?>">
                    
                    <div style="margin-bottom: 1rem;">
                        <label>Event Title</label><br>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required style="width: 100%; padding: 0.5rem;">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label>Category</label><br>
                            <select name="category" required style="width: 100%; padding: 0.5rem;">
                                <option value="Technology" <?php if($event['category'] == 'Technology') echo 'selected'; ?>>Technology</option>
                                <option value="Sports" <?php if($event['category'] == 'Sports') echo 'selected'; ?>>Sports</option>
                                <option value="Cultural" <?php if($event['category'] == 'Cultural') echo 'selected'; ?>>Cultural</option>
                            </select>
                        </div>
                        <div>
                            <label>Date</label><br>
                            <input type="date" name="event_date" value="<?php echo htmlspecialchars($event['event_date']); ?>" required style="width: 100%; padding: 0.5rem;">
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label>Description</label><br>
                        <textarea name="description" rows="4" required style="width: 100%; padding: 0.5rem;"><?php echo htmlspecialchars($event['description']); ?></textarea>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label>Update Poster (Leave blank to keep current image)</label><br>
                        <input type="file" name="poster" accept="image/jpeg, image/png" style="width: 100%; padding: 0.5rem; border: 1px solid var(--border); background: white;">
                    </div>
                    <button type="submit" class="btn">Update Event</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
<?php $stmt->close(); $conn->close(); ?>