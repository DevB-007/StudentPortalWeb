<?php
session_start();
require '../includes/db.php';

// 1. Role-Based Access Control (RBAC) - Kick out non-admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?status=unauthorized");
    exit();
}

// 2. Fetch Aggregated Statistics
$studentCount = $conn->query("SELECT COUNT(*) as count FROM students WHERE role = 'student'")->fetch_assoc()['count'];
$eventCount = $conn->query("SELECT COUNT(*) as count FROM events")->fetch_assoc()['count'];

// 3. Fetch Latest Registrations
$latestUsers = $conn->query("SELECT full_name, email, created_at FROM students WHERE role = 'student' ORDER BY created_at DESC LIMIT 5");

// 4. Fetch Audit Logs
$auditLogs = $conn->query("SELECT action, user_name, created_at FROM audit_logs ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="StudentHub - Admin Dashboard">
    <title>Admin Dashboard | StudentHub</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .dashboard-cards { display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; }
        .card { background: var(--bg-light); padding: 1.5rem; border: 1px solid var(--border); border-radius: 4px; flex: 1; min-width: 200px; text-align: center; }
        .card h3 { margin-bottom: 0.5rem; color: var(--primary); }
        .card p { font-size: 2.5rem; font-weight: bold; margin: 0; color: #333; }
    </style>
</head>

<body>
    <a href="#main-content" class="skip-link" style="position: absolute; left: -9999px;">Skip to main content</a>

    <div id="notification-banner" class="banner">
        <span>Welcome to the StudentHub Admin Panel.</span>
        <button id="close-banner" aria-label="Close notification">×</button>
    </div>

    <header>
        <div class="logo">
            <img src="../assets/images/logo.png" alt="StudentHub Logo" width="150">
        </div>
        
        <button class="hamburger" id="hamburger-menu" aria-label="Toggle navigation">
            ☰
        </button>

        <nav aria-label="Main Navigation">
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="admin.php" aria-current="page">Dashboard</a></li>
                <li><a href="admin_students.php">Manage Students</a></li>
                <li><a href="admin_events.php">Manage Events</a></li>
                <li><a href="../includes/logout.php" class="btn-login" style="background-color: #dc3545;">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main id="main-content">
        <section class="page-header">
            <h1>Admin Control Panel</h1>
            <p>System Overview and Recent Activity</p>
        </section>

        <section class="content-section" aria-labelledby="admin-stats">
            <h2 id="admin-stats">System Statistics</h2>
            
            <div class="dashboard-cards">
                <div class="card">
                    <h3>Total Students</h3>
                    <p><?php echo $studentCount; ?></p>
                </div>
                <div class="card">
                    <h3>Total Events</h3>
                    <p><?php echo $eventCount; ?></p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; flex-wrap: wrap;">
                <div style="min-width: 300px; overflow-x: auto;">
                    <h3>Latest Registrations</h3>
                    <table style="width: 100%; border-collapse: collapse; text-align: left; margin-top: 1rem;">
                        <thead>
                            <tr style="background-color: var(--primary); color: white;">
                                <th style="padding: 0.75rem; border: 1px solid var(--border);">Name</th>
                                <th style="padding: 0.75rem; border: 1px solid var(--border);">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($latestUsers->num_rows > 0): ?>
                                <?php while($row = $latestUsers->fetch_assoc()): ?>
                                    <tr>
                                        <td style="padding: 0.75rem; border: 1px solid var(--border);"><?php echo htmlspecialchars($row['full_name']); ?></td>
                                        <td style="padding: 0.75rem; border: 1px solid var(--border);"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="2" style="padding: 1rem; text-align: center; border: 1px solid var(--border);">No registrations found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div style="min-width: 300px; overflow-x: auto;">
                    <h3>Recent System Activity (Audit Log)</h3>
                    <table style="width: 100%; border-collapse: collapse; text-align: left; margin-top: 1rem;">
                        <thead>
                            <tr style="background-color: #6c757d; color: white;">
                                <th style="padding: 0.75rem; border: 1px solid var(--border);">Action</th>
                                <th style="padding: 0.75rem; border: 1px solid var(--border);">Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($auditLogs->num_rows > 0): ?>
                                <?php while($row = $auditLogs->fetch_assoc()): ?>
                                    <tr>
                                        <td style="padding: 0.75rem; border: 1px solid var(--border);"><?php echo htmlspecialchars($row['action']); ?></td>
                                        <td style="padding: 0.75rem; border: 1px solid var(--border);"><?php echo htmlspecialchars($row['user_name']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="2" style="padding: 1rem; text-align: center; border: 1px solid var(--border);">No recent activity.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; 2026 StudentHub (ITUE203 Project). All rights reserved.</p>
            <nav aria-label="Footer Navigation">
                <ul>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="privacy.php">Privacy Policy</a></li>
                </ul>
            </nav>
        </div>
    </footer>

    <script src="../assets/js/main.js"></script>
</body>
</html>
<?php $conn->close(); ?>