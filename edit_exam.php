<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: AdminLogin.php");
    exit();
}
$lecturerName = $_SESSION['username'];
require 'connection.php';
$servername = "localhost";
$username = "root";
$password = "root123";
$db_name = "database";
$conn = new mysqli($servername, $username, $password, $db_name);

// Assume the exam ID is passed via a GET request
$exam_id = isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT) ? $_GET['id'] : null;

if ($exam_id) {
    // Retrieve the current exam data from the database
    $stmt = $conn->prepare("SELECT exam_name, exam_time, category FROM exam_cat WHERE id = ?");
    $stmt->bind_param("i", $exam_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $exam = $result->fetch_assoc();

    if (!$exam) {
        echo "<script>alert('Exam not found.'); window.location='AddExam.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid exam ID.'); window.location='AddExam.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Exam</title>
    <link rel="stylesheet" href="lectureHomeTemplate.css">
</head>
<body>

<aside id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <h3>MCQ Master Admin</h3>
    </div>

    <ul class="sidebar-nav">
        <li><a href="lectureHomeTemplate.php">Dashboard</a></li>
        <li><a href="AddExam.php">Add / Edit Exam</a></li>
        <li><a href="add_edit_exam.php">Add / Edit Question</a></li>
        <li><a href="AdminLogin.php">Logout</a></li>

    </ul>
    <div class="lecture-name-container">
        <span>Welcome, <?php echo htmlspecialchars($lecturerName); ?></span>
    </div>
</aside>

<div id="content">
    <header id="header" class="header">
        <h3>Exam Manager</h3>
    </header>
    <main>
        <div class="edit-exam">
            <h2>Edit Exam</h2>
            <form action="update_exam.php" method="post">
                <input type="hidden" name="exam_id" value="<?php echo htmlspecialchars($exam_id); ?>">

                <label for="exam_name">Exam Name</label>
                <input type="text" id="exam_name" name="exam_name" value="<?php echo htmlspecialchars($exam['exam_name']); ?>" required>

                <label for="exam_time">Exam Time In Minutes</label>
                <input type="number" id="exam_time" name="exam_time" value="<?php echo htmlspecialchars($exam['exam_time']); ?>" required>

                <!-- Include other fields as necessary -->

                <input type="submit" name="update_exam" value="Update Exam">
            </form>
        </div>
    </main>
</div>
</body>
</html>
