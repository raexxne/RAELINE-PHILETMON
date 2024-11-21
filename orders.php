<?php
$host = 'localhost';
$username = 'root';
$password = 'Shinigami_02';
$dbname = 'burgerqueen';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = trim($_POST['customerName'] ?? '');
    $customerPhone = trim($_POST['customerPhone'] ?? '');
    $customerAddress = trim($_POST['customerAddress'] ?? '');
    $cartData = $_POST['cart'] ?? '';

    if (!$customerName || !$customerPhone || !$customerAddress || !$cartData) {
        die('Missing required information.');
    }

    $cart = json_decode($cartData, true);
    if (!$cart) {
        die('Invalid cart data.');
    }

    $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_phone, customer_address, total_price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $customerName, $customerPhone, $customerAddress, $totalPrice);
    $stmt->execute();
    $orderId = $stmt->insert_id;

    $stmt = $conn->prepare("INSERT INTO order_items (order_id, item_name, item_price, quantity, total_price) VALUES (?, ?, ?, ?, ?)");
    foreach ($cart as $item) {
        $itemName = $item['name'];
        $price = $item['price'];
        $quantity = $item['quantity'];
        $itemTotal = $price * $quantity;

        $stmt->bind_param("isidi", $orderId, $itemName, $price, $quantity, $itemTotal);
        $stmt->execute();
    }

    echo "
    <div class='order-success'>
        <h2>Order Successfully Placed!</h2>
        <p>Thank you for your order. Your order ID is <strong>{$orderId}</strong>.</p>
        <a href='index.html' class='btn-back'>Back to Home</a>
    </div>
    ";

    $stmt->close();
    $conn->close();
} else {
    echo 'Invalid request method.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        .order-success {
            background-color: #fff;
            margin: 50px auto;
            padding: 30px;
            text-align: center;
            border-radius: 10px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .order-success h2 {
            color: #333;
            font-size: 24px;
        }

        .order-success p {
            font-size: 18px;
            color: #555;
        }

        .order-success .btn-back {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .order-success .btn-back:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

</body>
</html>
