<?php
// Define your database credentials as constants
define('DB_HOST', 'localhost'); // Database host
define('DB_USER', 'root'); // Database username
define('DB_PASS', 'root123'); // Database password
define('DB_NAME', 'database'); // Database name

// Establish a new database connection using PDO
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Check if the form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare an insert statement
    $sql = "INSERT INTO userlogin (username, password, email) VALUES (:username, :password, :email)";

    try {
        $stmt = $pdo->prepare($sql);

        // Bind parameters to statement
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':email', $email);

    } catch (PDOException $e) {
        die("ERROR: Could not able to execute $sql. " . $e->getMessage());
    }
    if($stmt->execute()){
        header("Location: LoginPage.php");
        exit();
    }else{
        $messsage = "Error: Account could not be created.";
    }

    // Close statement
    unset($stmt);

    // Close connection
    unset($pdo);
} else {
    // Form not submitted, you might want to redirect to the registration form
    header('Location: register.html');
    exit();
}
?>