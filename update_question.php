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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_question'])) {
    $question_id = $_POST['question_id'];
    $question_text = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $answer = $_POST['answer'];

    // Prepare an update statement
    $stmt = $conn->prepare("UPDATE exam_question SET question = ?, option1 = ?, option2 = ?, option3 = ?, answer = ? WHERE id = ?");
    $stmt->bind_param("ssssii", $question_text, $option1, $option2, $option3, $answer, $question_id);
    $stmt->execute();

    // Check for successful update, then redirect or display a message
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Question updated successfully!'); window.location='add_edit_question.php';</script>";
        } else {
            echo "<script>alert('No changes were made to the question.'); window.location='add_edit_question.php';</script>";
        }
    } else {
        echo "<script>alert('Error updating question.'); window.location='add_edit_question.php';</script>";
    }
    $stmt->close();
} else {
    // Redirect back if the method is not POST or if the update_question button was not clicked
    header("Location: add_edit_question.php");
    exit();
}
?>