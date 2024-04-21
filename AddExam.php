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

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_exam'])) {
    $exam_name = $_POST['exam_name'];
    $exam_time = $_POST['exam_time'];
    $category = $_POST['exam_category'];

    // Prepare an insert statement
    $query = "INSERT INTO exam_cat (exam_name, exam_time, category) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    // Corrected the bind_param, exam_time is an integer
    $stmt->bind_param("sis", $exam_name, $exam_time, $category);
    $stmt->execute();

    // Check for successful insertion, then redirect or display a message
    if ($stmt->affected_rows > 0) {
        echo '<script type="text/javascript">';
        echo 'alert("Exam added successfully!!");';
        echo 'window.location.href = "AddExam.php";';
        echo '</script>';
        exit();
    } else {
        // Handle error, could be a duplicate entry, or a database error
        echo "Error: " . $stmt->error;
    }
}

// Fetch existing exams to display in the list
$exam_query = "SELECT id, exam_name, exam_time FROM exam_cat"; // Corrected column name
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
        <h3>Exam Manager </h3>
    </header>
    <main>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Add Exam</title>
            <link rel="stylesheet" href="AddExam.css">
        </head>

        <div class="container">
            <div class="add-exam">
                <h2>Add Exam </h2>
                <form action="AddExam.php" method="post">
                    <label for="exam_name">New Exam Name</label>
                    <input type="text" id="exam_name" name="exam_name" required>

                    <label for="exam_time">Exam Time In Minutes</label>
                    <input type="number" id="exam_time" name="exam_time" required>

                    <label for="exam_category">New Exam Category</label>
                    <input type="text" id="exam_category" name="exam_category" required>

                    <input type="submit" name="add_exam" value="Add Exam">
                </form>
            </div>

            <div class="exam-list">
                <h2>Exam Categories</h2>
                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Exam Name</th>
                        <th>Exam Time</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while($row = $exam_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td> <!-- Corrected column name -->
                            <td><?php echo $row['exam_name']; ?></td>
                            <td><?php echo $row['exam_time']; ?></td>
                            <td><a href="edit_exam.php?id=<?php echo $row['id']; ?>">Edit</a></td>
                            <td><a href="delete_exam.php?id=<?php echo $row['id']; ?>">Delete</a></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        </body>
    </main>
</div>


</html>
