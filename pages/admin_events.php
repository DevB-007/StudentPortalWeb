<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?status=unauthorized");
    exit();
}

$query = "SELECT id, title, category, event_date, poster_image FROM events ORDER BY event_date DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management | Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../assets/images/logo.png" alt="StudentHub Logo" width="150">
        </div>
        <nav aria-label="Main Navigation">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="admin_students.php">Manage Students</a></li>
                <li><a href="admin_events.php" aria-current="page">Manage Events</a></li>
                <li><a href="../includes/logout.php" class="btn-login" style="background-color: #dc3545;">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main id="main-content">
        <section class="content-section">
            <h2>Manage Campus Events</h2>
            
            <?php if (isset($_GET['status'])): ?>
                <?php if ($_GET['status'] == 'added'): ?>
                    <div style="background: #d4edda; color: #155724; padding: 1rem; border: 1px solid #c3e6cb; margin-bottom: 1rem;">Event created successfully.</div>
                <?php elseif ($_GET['status'] == 'deleted'): ?>
                    <div style="background: #d4edda; color: #155724; padding: 1rem; border: 1px solid #c3e6cb; margin-bottom: 1rem;">Event deleted successfully.</div>
                <?php elseif ($_GET['status'] == 'error' && isset($_GET['msg'])): ?>
                    <div style="background: #f8d7da; color: #721c24; padding: 1rem; border: 1px solid #f5c6cb; margin-bottom: 1rem;">Error: <?php echo htmlspecialchars($_GET['msg']); ?></div>
                <?php endif; ?>
            <?php endif; ?>

            <div style="background-color: var(--bg-light); padding: 1.5rem; border-radius: 4px; border: 1px solid var(--border); margin-bottom: 2rem;">
                <h3>Add New Event</h3>
                <form action="../includes/process_event.php" method="POST" enctype="multipart/form-data" style="margin-top: 1rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <input type="text" name="title" placeholder="Event Title" required>
                        <select name="category" required>
                            <option value="">Select Category</option>
                            <option value="Technology">Technology</option>
                            <option value="Sports">Sports</option>
                            <option value="Cultural">Cultural</option>
                        </select>
                        <input type="date" name="event_date" required>
                        <input type="file" name="poster" accept="image/jpeg, image/png" required style="padding: 0.5rem; border: 1px solid var(--border); background: white;">
                    </div>
                    <textarea name="description" rows="3" placeholder="Event Description" style="width: 100%; margin-bottom: 1rem; padding: 0.5rem;" required></textarea>
                    <button type="submit" class="btn">Create Event</button>
                </form>
            </div>

            <h3>Existing Events</h3>
            <div style="overflow-x: auto; margin-top: 1rem;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background-color: var(--primary); color: white;">
                            <th style="padding: 0.75rem; border: 1px solid var(--border);">Poster</th>
                            <th style="padding: 0.75rem; border: 1px solid var(--border);">Title</th>
                            <th style="padding: 0.75rem; border: 1px solid var(--border);">Category</th>
                            <th style="padding: 0.75rem; border: 1px solid var(--border);">Date</th>
                            <th style="padding: 0.75rem; border: 1px solid var(--border);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td style="padding: 0.75rem; border: 1px solid var(--border);">
                                        <img src="../assets/uploads/posters/<?php echo htmlspecialchars($row['poster_image']); ?>" alt="Poster" width="50">
                                    </td>
                                    <td style="padding: 0.75rem; border: 1px solid var(--border);"><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td style="padding: 0.75rem; border: 1px solid var(--border);"><?php echo htmlspecialchars($row['category']); ?></td>
                                    <td style="padding: 0.75rem; border: 1px solid var(--border);"><?php echo htmlspecialchars($row['event_date']); ?></td>
                                    <td style="padding: 0.75rem; border: 1px solid var(--border);">
                                        <a href="../includes/delete_event.php?id=<?php echo $row['id']; ?>" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.85rem; background-color: #dc3545;" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="padding: 1rem; text-align: center; border: 1px solid var(--border);">No events created yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
<?php $conn->close(); ?>