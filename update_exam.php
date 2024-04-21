<!-- update_exam.php -->
<?php
session_start();
require 'connection.php';
require 'connection.php';
$servername = "localhost";
$username = "root";
$password = "root123";
$db_name = "database";
$conn = new mysqli($servername, $username, $password, $db_name);
if (!isset($_SESSION['username'])) {
    header("Location: AdminLogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $exam_id = $_POST['exam_id'];
    $exam_name = $_POST['exam_name'];
    $exam_time = $_POST['exam_time'];

    // Prepare an update statement
    $stmt = $conn->prepare("UPDATE exam_cat SET exam_name = ?, exam_time = ? WHERE id = ?");
    $stmt->bind_param("sii", $exam_name, $exam_time, $exam_id);
    $stmt->execute();

    // Check for successful update, then redirect or display a message
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Exam updated successfully!'); window.location='AddExam.php';</script>";
    } else {
        echo "<script>alert('Error updating exam or no changes made.'); window.location='AddExam.php';</script>";
    }
} else {
    // Redirect back if the method is not POST
    header("Location: AddExam.php");
    exit();
}
?>
