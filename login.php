<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if inputs are empty
    if (empty($username) || empty($password)) {
        header("Location: error.html");
        exit();
    }

    // Database connection
    $host = "localhost";
    $dbusername = "root";
    $dbpassword = "Shinigami_02";
    $dbname = "burgerqueen";

    $conn = mysqli_connect($host, $dbusername, $dbpassword, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate login authentication using prepared statements
    $query = "SELECT * FROM login WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Login success
        header("Location: admin.php");
        exit();
    } else {
        // Login failed
        header("Location: error.html");
        exit();
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
