
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$lecturerName = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecturer Dashboard</title>
    <link rel="stylesheet" href="LecturerHome.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <nav class="dashboard-nav">
                <div class="nav-brand">Online Quiz System</div>
                <ul>
                    <li class="active"><a href="#">Dashboard</a></li>
                    <li><a href="#">My Exams</a></li>
                    <li><a href="#">Results</a></li>
                    <li><a href="#">Create Assignment</a></li>
                    <li><a href="#">Messages</a></li>
                    <li><a href="#">Settings</a></li>
                </ul>
                <div class="logout-container">
                    <span>Welcome, <?php echo htmlspecialchars($lecturerName); ?></span>
                    <a href="LoginPage.php" class="logout-button">Logout</a>
                </div>
            </nav>
        </header>

        <main class="dashboard-main">
            <div class="timer-container text-right">
                <span id="countdown-timer">Count Down Timer</span>
            </div>
        </main>

        <footer class="dashboard-footer">
            &copy; <?php echo date("Y"); ?> MCQ Master
        </footer>
    </div>

    <script>
        // JavaScript function to search courses
        function searchCourses() {
            var input, filter, courseGrid, courseItem, h3, i, txtValue;
            input = document.getElementById('courseSearch');
            filter = input.value.toUpperCase();
            courseGrid = document.getElementsByClassName('course-grid')[0];
            courseItem = courseGrid.getElementsByClassName('course-item');

            for (i = 0; i < courseItem.length; i++) {
                h3 = courseItem[i].getElementsByTagName('h3')[0];
                txtValue = h3.textContent || h3.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    courseItem[i].style.display = "";
                } else {
                    courseItem[i].style.display = "none";
                }
            }
        }

        // JavaScript function to categorize courses
        function categorizeCourses() {
            // Implement categorization logic here
        }
    </script>
</body>
</html>
