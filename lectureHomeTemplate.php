<link rel="stylesheet" href="lectureHomeTemplate.css">
<?php
// Start the session
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: AdminLogin.php");
    exit();
}
$lecturerName = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="lectureHomeTemplate.css">
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
            <h3>Dashboard </h3>
        </header>
        <main>
            //second content columes
        </main>
    </div>

</body>
</html>
