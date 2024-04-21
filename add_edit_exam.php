<link rel="stylesheet" href="lectureHomeTemplate.css">
<?php
// Start the session
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

// Fetch existing categories to display in the dropdown
$exam_query = "SELECT id, exam_name, exam_time FROM exam_cat";
$exam_result = $conn->query($exam_query);
$examId = isset($_GET['examId']) ? intval($_GET['examId']) : 0;
// Fetch existing exams to display in the list
$exam_result = $conn->query($exam_query);
if (!$exam_result) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="lectureHomeTemplate.css">
    <link rel="stylesheet" href="AddExam.css">
    <!-- Add other head elements and external libraries or frameworks if needed -->
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
        <h3>Select exam categories to add and edit questions </h3>
    </header>
    <main>
        <div class="exam-list">
            <h2>Exam Categories</h2>
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Exam Name</th>
                    <th>Exam Time</th>
                    <th>Select</th>

                </tr>
                </thead>
                <tbody>
                <?php while($row = $exam_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td> <!-- Use 'id' here, not 'exam_id' -->
                        <td><?php echo $row['exam_name']; ?></td>
                        <td><?php echo $row['exam_time']; ?></td>
                        <td><a href="add_edit_question.php?examId=<?php echo $row['id']; ?>">Select</a></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>