<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "Shinigami_02";
$dbname = "burgerqueen";
$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

// Get the JSON input
$input = json_decode(file_get_contents('php://input'), true);

$userName = $input['userName'];
$userPhone = $input['userPhone'];
$userAddress = $input['userAddress'];
$orderDetails = json_encode($input['orderDetails']); // Store as JSON
$total = $input['total'];

// Insert order into database
$sql = "INSERT INTO orders (userName, userPhone, userAddress, orderDetails, total, status) 
        VALUES (?, ?, ?, ?, ?, 'Pending')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssd", $userName, $userPhone, $userAddress, $orderDetails, $total);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Order placed successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Error placing the order."]);
}

$stmt->close();
$conn->close();
?>
