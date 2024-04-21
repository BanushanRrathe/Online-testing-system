<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require 'connection.php';
$lecturerName = $_SESSION['username'];
$servername = "localhost";
$username = "root";
$password = "root123";
$db_name = "database";
$conn = new mysqli($servername, $username, $password, $db_name);
$exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 0;

// Prepare the SQL statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM exam_question WHERE exam_id = ?");
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$result = $stmt->get_result();

$sql = "SELECT id, exam_name, category FROM exam_cat";
$result = $conn->query($sql);
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
            <ul>
                <li class="active"><a href="lectureHome.php">Dashboard</a></li>
                <li><a href="#">My Courses</a></li>
                <li><a href="#">Grade </a></li>
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
        <div class="course-filter">
            <input type="text" id="courseSearch" placeholder="Search for courses">
            <button onclick="searchCourses()">Search</button>
            <select id="sortCourses">
                <option value="name">Sort by course name</option>
                <option value="last_accessed">Sort by last accessed</option>
            </select>
            <button onclick="categorizeCourses()">Categorised</button>
        </div>
        <section class="module-overview">
            <h2>My Modules</h2>
            <div class="course-grid">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="course-item">
                            <div class="image-container">
                                <!-- Replace href with the link to the specific course or exam -->
                                <a href="loadExamQuestion.php" class="course-link">
                                    <!-- Add a placeholder image or relevant image for the category -->
                                    <img src="path_to_your_images_folder/placeholder.jpg" alt="<?php echo htmlspecialchars($row['exam_name']); ?>">
                                </a>
                            </div>
                            <h3><?php echo htmlspecialchars($row['category']); ?></h3>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No categories found.</p>
                <?php endif; ?>
            </div>
            <div class="course-grid">
                <div class="course-item">

                    <div class="image-container">
                        <a href="Maths.php" class="course-link">
                            <img src="Photos/maths.webp" alt="Maths">
                    </div>
                    <h3>Mathemetic</h3>
                </div>
                <div class="course-item">
                    <div class="image-container">
                        <a href="Science.php" class="course-link">
                            <img src="Photos/pic3.jpg" alt="Science">
                    </div>
                    <h3>Science</h3>
                </div>
                <div class="course-item">
                    <div class="image-container">
                        <img src="Photos/pic2.jpg" alt="English">
                    </div>
                    <h3>English</h3>
                </div>
                <div class="course-item">
                    <div class="image-container">
                        <img src="Photos/pic1.webp" alt="Computer Science">
                    </div>
                    <h3>Computer Science</h3>
                </div>
            </div>
        </section>
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
