<?php
session_start();
require 'connection.php';
require 'connection.php';
$servername = "localhost";
$username = "root";
$password = "root123";
$db_name = "database";
$conn = new mysqli($servername, $username, $password, $db_name);
// Check if the user is logged in and if not, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: AdminLogin.php");
    exit();
}

// Check if the 'id' GET parameter is set and is a valid number
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $exam_id = $_GET['id'];

    // Prepare a DELETE statement to safely remove the exam category
    $stmt = $conn->prepare("DELETE FROM exam_cat WHERE id = ?");
    $stmt->bind_param("i", $exam_id);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        echo "<script type='text/javascript'>alert('Exam deleted successfully!'); window.location='AddExam.php';</script>";
    } else {
        echo "<script type='text/javascript'>alert('Error: Could not delete the exam.'); window.location='AddExam.php';</script>";
    }
} else {
    echo "<script type='text/javascript'>alert('Error: Invalid ID.'); window.location='AddExam.php';</script>";
}

