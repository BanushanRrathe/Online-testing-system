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

$question_id = isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT) ? $_GET['id'] : null;

if ($question_id) {
    // Retrieve the current question data from the database
    $stmt = $conn->prepare("SELECT * FROM exam_question WHERE id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $question = $result->fetch_assoc();

    if (!$question) {
        echo "<script>alert('Question not found.'); window.location='AddExam.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid question ID.'); window.location='AddExam.php';</script>";
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
        <div class="edit-question">
            <h2>Edit Question</h2>
            <form action="update_question.php" method="post">
                <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question_id); ?>">

                <label for="question">Question</label>
                <input type="text" id="question" name="question" value="<?php echo htmlspecialchars($question['question']); ?>" required>

                <label for="option1">Option 1</label>
                <input type="text" id="option1" name="option1" value="<?php echo htmlspecialchars($question['option1']); ?>" required>

                <label for="option2">Option 2</label>
                <input type="text" id="option2" name="option2" value="<?php echo htmlspecialchars($question['option2']); ?>" required>

                <label for="option3">Option 3</label>
                <input type="text" id="option3" name="option3" value="<?php echo htmlspecialchars($question['option3']); ?>" required>

                <label for="answer">Answer</label>
                <select id="answer" name="answer" required>
                    <option value="1" <?php echo $question['answer'] == '1' ? 'selected' : ''; ?>>1</option>
                    <option value="2" <?php echo $question['answer'] == '2' ? 'selected' : ''; ?>>2</option>
                    <option value="3" <?php echo $question['answer'] == '3' ? 'selected' : ''; ?>>3</option>
                </select>

                <input type="submit" name="update_question" value="Update Question">
            </form>
        </div>
    </main>
</div>
</body>
</html>
