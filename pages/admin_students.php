<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?status=unauthorized");
    exit();
}

$search = $_GET['search'] ?? '';
$course = $_GET['course'] ?? '';

$query = "SELECT id, full_name, email, mobile, course, enroll_year FROM students WHERE role = 'student'";
$params = [];
$types = "";

if ($search !== '') {
    $query .= " AND (full_name LIKE ? OR email LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= "ss";
}

if ($course !== '') {
    $query .= " AND course = ?";
    $params[] = $course;
    $types .= "s";
}

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management | Admin</title>
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
                <li><a href="admin_students.php" aria-current="page">Manage Students</a></li>
                <li><a href="../includes/logout.php" class="btn-login" style="background-color: #dc3545;">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main id="main-content">
        <section class="content-section">
            <h2>Student Management</h2>
            
            <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
                <div style="background: #d4edda; color: #155724; padding: 1rem; border: 1px solid #c3e6cb; margin-bottom: 1rem;">Student record deleted successfully.</div>
            <?php endif; ?>

            <form method="GET" action="admin_students.php" style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                <input type="text" name="search" placeholder="Search name or email..." value="<?php echo htmlspecialchars($search); ?>" style="flex: 1; min-width: 200px;">
                <select name="course" style="width: auto;">
                    <option value="">All Courses</option>
                    <option value="BTech" <?php if($course=='BTech') echo 'selected'; ?>>B.Tech</option>
                    <option value="BCA" <?php if($course=='BCA') echo 'selected'; ?>>BCA</option>
                    <option value="MCA" <?php if($course=='MCA') echo 'selected'; ?>>MCA</option>
                </select>
                <button type="submit" class="btn">Filter</button>
                <a href="admin_students.php" class="btn" style="background-color: #6c757d;">Reset</a>
            </form>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background-color: var(--primary); color: white;">
                            <th style="padding: 0.75rem; border: 1px solid var(--border);">ID</th>
                            <th style="padding: 0.75rem; border: 1px solid var(--border);">Name</th>
                            <th style="padding: 0.75rem; border: 1px solid var(--border);">Email</th>
                            <th style="padding: 0.75rem; border: 1px solid var(--border);">Course</th>
                            <th style="padding: 0.75rem; border: 1px solid var(--border);">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td style="padding: 0.75rem; border: 1px solid var(--border);"><?php echo $row['id']; ?></td>
                                    <td style="padding: 0.75rem; border: 1px solid var(--border);"><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td style="padding: 0.75rem; border: 1px solid var(--border);"><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td style="padding: 0.75rem; border: 1px solid var(--border);"><?php echo htmlspecialchars($row['course']); ?></td>
                                    <td style="padding: 0.75rem; border: 1px solid var(--border);">
                                        <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.85rem; background-color: #ffc107; color: black;">Edit</a>
                                        <a href="../includes/delete_student.php?id=<?php echo $row['id']; ?>" class="btn" style="padding: 0.25rem 0.5rem; font-size: 0.85rem; background-color: #dc3545;" onclick="return confirm('Are you sure you want to delete this student record? This action cannot be undone.');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="padding: 1rem; text-align: center; border: 1px solid var(--border);">No student records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>