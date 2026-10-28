<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?status=unauthorized");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin_students.php");
    exit();
}

$student_id = $_GET['id'];
$stmt = $conn->prepare("SELECT full_name, email, mobile, course, enroll_year FROM students WHERE id = ? AND role = 'student'");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: admin_students.php");
    exit();
}
$student = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header>
        <div class="logo"><img src="../assets/images/logo.png" alt="Logo" width="150"></div>
        <nav>
            <ul>
                <li><a href="admin_students.php">Back to Students</a></li>
            </ul>
        </nav>
    </header>
    <main id="main-content">
        <section class="content-section">
            <h2>Edit Student Record</h2>
            <div style="background-color: var(--bg-light); padding: 1.5rem; border: 1px solid var(--border); border-radius: 4px;">
                <form action="../includes/process_edit_student.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $student_id; ?>">
                    
                    <div style="margin-bottom: 1rem;">
                        <label>Full Name</label><br>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($student['full_name']); ?>" required style="width: 100%; padding: 0.5rem;">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label>Email</label><br>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required style="width: 100%; padding: 0.5rem;">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label>Mobile</label><br>
                        <input type="text" name="mobile" value="<?php echo htmlspecialchars($student['mobile']); ?>" required style="width: 100%; padding: 0.5rem;">
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label>Course</label><br>
                        <select name="course" required style="width: 100%; padding: 0.5rem;">
                            <option value="BTech" <?php if($student['course'] == 'BTech') echo 'selected'; ?>>B.Tech</option>
                            <option value="BCA" <?php if($student['course'] == 'BCA') echo 'selected'; ?>>BCA</option>
                            <option value="MCA" <?php if($student['course'] == 'MCA') echo 'selected'; ?>>MCA</option>
                        </select>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label>Enroll Year (1-4)</label><br>
                        <input type="number" name="enroll_year" value="<?php echo htmlspecialchars($student['enroll_year']); ?>" required min="1" max="4" style="width: 100%; padding: 0.5rem;">
                    </div>
                    <button type="submit" class="btn">Update Student</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
<?php $stmt->close(); $conn->close(); ?>