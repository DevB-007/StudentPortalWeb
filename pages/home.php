<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="StudentHub - The ultimate portal for campus events and student management.">
    <title>Home | StudentHub</title>
    
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
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="events.php">Campus Events</a></li>
                <li><a href="directory.php">Directory</a></li>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <!-- If user IS logged in -->
                    <?php if($_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin_students.php">Admin Panel</a></li>
                        <li><a href="admin.php">System Stats</a></li>
                    <?php else: ?>
                        <li><a href="dashboard.php">Dashboard</a></li>
                    <?php endif; ?>
                    <li><a href="../includes/logout.php" class="btn-login" style="background-color: #dc3545;">Logout</a></li>
                
                <?php else: ?>
                    <!-- If user is NOT logged in -->
                    <li><a href="login.php" class="btn-login">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main id="main-content">
        
        <section class="page-header">
            <h1>Welcome to StudentHub</h1>
            <p>Your central portal for campus life, events, and resources.</p>
        </section>

        <section class="content-section" aria-labelledby="latest-news">
            <h2 id="latest-news">Latest Announcements</h2>
            <article>
                <h3>Tech Symposium 2026 Registration Open</h3>
                <p>Join us for the annual technology symposium. Register via the events hub.</p>
            </article>
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