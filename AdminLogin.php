<link rel="stylesheet" href="AdminLogin.css">
<?php
session_start(); // Start the session to maintain login state
include("connection.php"); // Ensure this file uses secure database connection practices
$lecturerName = $_SESSION['username'];
require 'connection.php';
$servername = "localhost";
$username = "root";
$password = "root123";
$db_name = "database";
$conn = new mysqli($servername, $username, $password, $db_name);
$message = '';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (!empty($username) && !empty($password)) {
        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, userName, password FROM admin_login WHERE userName = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the username exists in the database
        if ($admin = $result->fetch_assoc()) {
            // This is for plain text passwords; for hashed passwords, use password_verify()
            if ($password === $admin['password']) {
                // Password is correct, create session variables and redirect
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $username;
                header("Location: lectureHomeTemplate.php"); // Redirect to the admin dashboard page
                exit();
            } else {
                // Incorrect password
                $message = 'The username or password is incorrect.';
            }
        } else {
            // Username not found
            $message = 'The username or password is incorrect.';
        }
        $stmt->close();
    } else {
        // Username or password field is empty
        $message = 'Please enter both username and password.';
    }
}
// If the headers have not been sent, start output buffering
if (!headers_sent()) {
    ob_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Page</title>

</head>
<body>

<div class="login-container">
    <h2>Admin Login to MCQ Master</h2>
    <?php if (!empty($message)): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <a href="LoginReset.php" class="forgot-link">Forgot Username/Password?</a>
    <p class="sign-up-text">Don't have an account? <a href="register.html">Sign up</a></p> <!-- Add this line for sign-up -->

</div>

</body>
</html>
<?php
// Flush the output buffer
if (!headers_sent()) {
    ob_end_flush();
}
