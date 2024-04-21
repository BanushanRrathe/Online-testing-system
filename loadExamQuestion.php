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

$lecturerName = $_SESSION['username'];

// Get the exam_id from the URL
$exam_id = isset($_GET['exam_id']) ? intval($_GET['exam_id']) : 0;

// SQL to get questions for the selected exam category
$questionsSql = "SELECT * FROM exam_question WHERE exam_id = ? ORDER BY question_num";
$questionsStmt = $conn->prepare($questionsSql);
$questionsStmt->bind_param("i", $exam_id);
$questionsStmt->execute();
$questionsResult = $questionsStmt->get_result();
$questions = $questionsResult->fetch_all(MYSQLI_ASSOC);

// SQL to get exam details including time
$examDetailsSql = "SELECT exam_time FROM exam_cat WHERE id = ?";
$examDetailsStmt = $conn->prepare($examDetailsSql);
$examDetailsStmt->bind_param("i", $exam_id);
$examDetailsStmt->execute();
$examDetailsResult = $examDetailsStmt->get_result();
$examDetails = $examDetailsResult->fetch_assoc();
$exam_time = isset($examDetails['exam_time']) ? intval($examDetails['exam_time']) : 0;
if (!isset($_SESSION['end_time'])) {
    $_SESSION['end_time'] = date('Y-m-d H:i:s', strtotime('+' . $exam_time . ' minutes'));
}

// Calculate remaining time in seconds
$remainingTime = strtotime($_SESSION['end_time']) - time();
$remainingTime = $remainingTime > 0 ? $remainingTime : 0;
error_log("Exam time in minutes: " . $exam_time);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Questions</title>
    <link rel="stylesheet" href="LecturerHome.css">

</head>
<body>
    <header class="dashboard-header">
        <nav class="dashboard-nav">
            <ul>
                <li class="active"><a href="lectureHome.php">Dashboard</a></li>
                <li><a href="#">My Courses</a></li>
                <li><a href="#">Grade Submissions</a></li>
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
        <main class="exam-questions">
            <h2>Exam Questions</h2>
            <div>Time remaining: <span id="time"></span></div>
            <div id="questionContainer">
                <script>
                    function getQuestionFromPython(student_id) {
                        // Use Fetch API to post to the Flask endpoint
                        fetch('http://localhost:5000/get_next_question', {  // Replace with your Python server URL
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({student_id: student_id}),
                        })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Success:', data);
                                // Now you can do something with the question and feedback
                                // For example, display the question in the HTML
                                displayQuestion(data.question);
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                            });
                    }
                    function displayQuestion(questionData) {
                        if (questionData && questionData.question) {
                            // Update your HTML with the question data
                            var questionContainer = document.getElementById('questionContainer');
                            questionContainer.innerHTML = ''; // Clear previous question

                            // Create question paragraph element
                            var questionParagraph = document.createElement('p');
                            questionParagraph.textContent = questionData.question.question;

                            // Append question to container
                            questionContainer.appendChild(questionParagraph);

                            // Create and append options
                            var optionsContainer = document.createElement('div');
                            optionsContainer.className = 'options';

                            // Assuming each question has a fixed number of options, for example, 3
                            for (var i = 1; i <= 3; i++) {
                                var optionButton = document.createElement('button');
                                optionButton.textContent = questionData.question['option' + i];
                                // Set a data attribute on the button for the option number
                                optionButton.dataset.option = i;
                                // Bind the click event to a function that will handle answer submission
                                optionButton.onclick = function() {
                                    selectAnswer(this.dataset.option, questionData.question.id);
                                };
                                optionsContainer.appendChild(optionButton);
                            }

                            // Append options to container
                            questionContainer.appendChild(optionsContainer);
                        } else {
                            // Handle the case where there are no more questions or an error occurred
                            document.getElementById('questionContainer').innerHTML = '<p>No more questions available or there was an error fetching the next question.</p>';
                        }
                    }
                    var currentQuestionIndex = 0;
                    var questions = <?php echo json_encode($questions); ?>;
                    var examTime = <?php echo $exam_time * 60; ?>; // Convert minutes to seconds

                    function showNextQuestion() {
                        var questionContainer = document.getElementById('questionContainer');
                        var nextButton = document.getElementById('nextButton');

                        // Check if there are still questions left to display
                        if (currentQuestionIndex < questions.length) {
                            var question = questions[currentQuestionIndex];

                            questionContainer.innerHTML = `
            <div class="question">
                <p>${question.question}</p>
                <div class="options">
                    <button onclick="selectAnswer(1, ${question.id})">${question.option1}</button>
                    <button onclick="selectAnswer(2, ${question.id})">${question.option2}</button>
                    <button onclick="selectAnswer(3, ${question.id})">${question.option3}</button>
                </div>
            </div>
        `;

                            // Enable the "Next Question" button in case it was disabled
                            nextButton.style.display = 'block';

                            currentQuestionIndex++;
                        } else {
                            // All questions have been displayed
                            questionContainer.innerHTML = "<p>All questions completed!</p>";
                            // Add a 'Go to Home Page' button
                            var buttonHTML = '<button id="goHomeButton" onclick="goToHomePage()">Go to Home Page</button>';
                            questionContainer.insertAdjacentHTML('beforeend', buttonHTML);

                            // Hide the "Next Question" button
                            nextButton.style.display = 'none';
                        }
                    }

                    function goToHomePage() {
                        window.location.href = 'lectureHome.php';
                    }

                    function selectAnswer(option, questionId) {
                        // Clear previous selections
                        var buttons = document.querySelectorAll('.options button');
                        buttons.forEach(function(button) {
                            button.classList.remove('selected');
                        });

                        // Highlight the clicked button
                        event.currentTarget.classList.add('selected');

                        // Store the selected answer, compare it, or send it to serveer
                        console.log(`Question ID: ${questionId}, Selected Option: ${option}`);
                    }

                    function startTimer(duration, display) {
                        console.log(`Timer initialized with duration: ${duration}`); // Debug line
                        var timer = duration, minutes, seconds;
                        var interval = setInterval(function () {
                            minutes = parseInt(timer / 60, 10);
                            seconds = parseInt(timer % 60, 10);
                            minutes = minutes < 10 ? "0" + minutes : minutes;
                            seconds = seconds < 10 ? "0" + seconds : seconds;

                            display.textContent = minutes + ":" + seconds; // Ensure this is the right element
                            console.log('Timer running:', display.textContent); // Should print the current time

                            if (--timer < 0) {
                                clearInterval(interval);
                                display.textContent = "Time's up!";
                                submitAnswers();
                            }
                        }, 1000);
                    }
                    // Call this function when the time is up or the exam is otherwise completed
                    function submitAnswers() {
                        // You could collect answers from a form or any data structure where you've stored them
                        console.log('Submitting answers...');
                        // Redirect to a results page or display results
                        window.location.href = 'results.php'; // Update with the actual results page
                    }

                    // Show the first question
                    showNextQuestion();

                    // Load the first question and start the timer when the page loads
                    window.onload = function () {
                        const display = document.getElementById('time');
                        if(examTime && examTime > 0) {
                            console.log('Starting timer with duration:', examTime); // This should print the duration in seconds
                            startTimer(examTime, display);
                        } else {
                            console.error("Invalid exam time. Cannot start timer.");
                            display.textContent = "Error: Invalid Exam Time"; // Display error directly on page for visibility

                        }
                        showNextQuestion();
                    };
                </script>
            </div>
            <button id="nextButton" onclick="showNextQuestion()">Next Question</button>

        </main>
</body>
</html>
<?php
// Close your prepared statements.
$conn->close();
?>
