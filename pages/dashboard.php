<?php
session_start();

$timeout_duration = 1800;

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: login.html?status=timeout");
    exit();
}

$_SESSION['last_activity'] = time();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html?status=unauthorized");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="StudentHub - The ultimate portal for campus events and student management.">
    <title>Dashboard | StudentHub</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <a href="#main-content" class="skip-link" style="position: absolute; left: -9999px;">Skip to main content</a>

    <div id="notification-banner" class="banner">
        <span>Welcome to the new StudentHub portal! Registration is now open.</span>
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
            <section class="page-header">
                <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h1>
                <p>Your central portal for campus life, events, and resources.</p>
            </section>
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="events.html">Campus Events</a></li>
                <li><a href="directory.html">Student Directory</a></li>
                <li><a href="faq.html">FAQ / Help</a></li>
                <li><a href="../includes/logout.php" class="btn-login">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main id="main-content">

        <section class="page-header">
            <h1>Student Dashboard</h1>
            <p>Your central portal for campus life, events, and resources.</p>
        </section>

        <section class="content-section" aria-labelledby="dashboard-overview">
            <h2 id="dashboard-overview">Dashboard Overview</h2>
            <p>Welcome back! Your personalized notices and quick links will appear here.</p>
        </section>

    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; 2026 StudentHub (ITUE203 Project). All rights reserved.</p>

            <nav aria-label="Footer Navigation">
                <ul>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="privacy.html">Privacy Policy</a></li>
                </ul>
            </nav>
        </div>
    </footer>

    <script src="../assets/js/main.js"></script>
</body>

</html>