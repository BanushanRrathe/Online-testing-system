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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_question'])) {
    // Retrieve form data
    $question_num = $_POST['question_num'];
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $answer = $_POST['answer'];
    $difficulty = isset($_POST['difficulty']) ? $_POST['difficulty'] : 'Unknown'; // Set a default difficulty
    if (!in_array($difficulty, ['easy', 'medium', 'hard'], true)) {
        $difficulty = 'unknown'; // default to 'unknown' if an invalid value is provided
    }
    // Prepare an insert statement
    $stmt = $conn->prepare("INSERT INTO exam_question (question_num, question, option1, option2, option3, answer, difficulty) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssss", $question_num, $question, $option1, $option2, $option3, $answer, $difficulty);
    if($stmt->execute()) {
        echo "<script>alert('Question added successfully" . htmlspecialchars($stmt->error) . "');</script>";
    } else {
        echo "<script>alert('Error adding question: ');</script>";
    }
}
// Fetch questions by difficulty
function fetch_questions_by_difficulty($conn, $difficulty) {
    $stmt = $conn->prepare("SELECT id, question_num, question, option1, option2, option3, answer FROM exam_question WHERE difficulty = ?");
    $stmt->bind_param("s", $difficulty);
    $stmt->execute();
    return $stmt->get_result();
}

$easy_questions = fetch_questions_by_difficulty($conn, 'Easy');
$medium_questions = fetch_questions_by_difficulty($conn, 'Medium');
$hard_questions = fetch_questions_by_difficulty($conn, 'Hard');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Questions</title>
    <link rel="stylesheet" href="lectureHomeTemplate.css">
    <link rel="stylesheet" href="AddExam.css">
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
        <h3>Add Question</h3>
    </header>
    <main>
        <form action="add_edit_question.php" method="post">
            <input type="text" name="question_num" placeholder="Question Number" required>
            <input type="text" name="question" placeholder="Question" required>
            <input type="text" name="option1" placeholder="Option 1" required>
            <input type="text" name="option2" placeholder="Option 2" required>
            <input type="text" name="option3" placeholder="Option 3" required>
            <select name="answer" required>
                <option value="">Correct Answer</option>
                <option value="1">Option 1</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
            </select>
            <select name="difficulty" required>
                <option value="">Difficulty</option>
                <option value="easy">Easy</option>
                <option value="medium">Medium</option>
                <option value="hard">Hard</option>

            </select>
            <input type="submit" name="add_question" value="Add Question">
        </form>
    </main>
    <div class="question-list">
        <h3>Easy Questions</h3>
        <!-- Table for easy questions -->
        <?php display_questions_table($easy_questions); ?>

        <h3>Medium Questions</h3>
        <!-- Table for medium questions -->
        <?php display_questions_table($medium_questions); ?>

        <h3>Hard Questions</h3>
        <!-- Table for hard questions -->
        <?php display_questions_table($hard_questions); ?>
    </div>
</div>



<?php
function display_questions_table($questions) {
    echo '<table>';
    echo '<thead>';
    echo '<tr><th>#</th><th>Question</th><th>Option 1</th><th>Option 2</th><th>Option 3</th><th>Answer</th><th>Edit/Delete</th></tr>';
    echo '</thead>';
    echo '<tbody>';
    while ($row = $questions->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['question_num']) . '</td>';
        echo '<td>' . htmlspecialchars($row['question']) . '</td>';
        echo '<td>' . htmlspecialchars($row['option1']) . '</td>';
        echo '<td>' . htmlspecialchars($row['option2']) . '</td>';
        echo '<td>' . htmlspecialchars($row['option3']) . '</td>';
        echo '<td>' . htmlspecialchars($row['answer']) . '</td>';
        echo '<td><a href="edit_question.php?id=' . $row['id'] . '">Edit</a> / <a href="delete_question.php?id=' . $row['id'] . '">Delete</a></td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
}
?>

</html>