<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Order Management</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border: 1px solid #ddd;
        }
        table th, table td {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #333;
            color: #FFF;
            font-weight: bold;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        table tr:hover {
            background-color: #f1f8ff;
        }
        button {
            padding: 10px 15px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }
        .approve-btn {
        background-color: #43a047; /* Green for Approve */
        color: white;
        }

        .approve-btn:hover {
        background-color: #388e3c; /* Darker green on hover */
        }

        /* Red styling for Cancel */
        .cancel-btn {
        background-color: #e53935; /* Red for Cancel */
        color: white;
        }

        .cancel-btn:hover {
        background-color: #d32f2f; /* Darker red on hover */
        }

        .back-btn {
            background-color: #1976d2;
            color: white;
            font-size: 16px;
            text-align: center;
            display: block;
            margin: 20px auto;
            width: 200px;
        }
        .back-btn:hover {
            background-color: #1565c0;
        }
        p {
            font-size: 16px;
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>
    <h1>Order Management</h1>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "Shinigami_02";
    $dbname = "burgerqueen";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle Update/Toggle Status Action
    if (isset($_POST['update_status'])) {
        $order_id = $_POST['order_id'];
        // Fetch current status
        $statusQuery = "SELECT status FROM order_items WHERE order_id = ?";
        $stmt = $conn->prepare($statusQuery);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->bind_result($current_status);
        $stmt->fetch();
        $stmt->close();

        // Toggle status
        if ($current_status == 'Pending') {
            $updateQuery = "UPDATE order_items SET status = 'Completed' WHERE order_id = ?";
        } else {
            $updateQuery = "UPDATE order_items SET status = 'Pending' WHERE order_id = ?";
        }

        // Execute update query
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("i", $order_id);
        if ($stmt->execute()) {
            echo "<p>Order ID $order_id status updated to $current_status.</p>";
        } else {
            echo "<p>Error updating status: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }

    // Fetch and Display Order Items
    $sql = "SELECT * FROM order_items";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Item Name</th>
                <th>Item Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row["id"] . '</td>';
            echo '<td>' . $row["order_id"] . '</td>';
            echo '<td>' . htmlspecialchars($row["item_name"]) . '</td>';
            echo '<td>' . number_format($row["item_price"], 2) . '</td>';
            echo '<td>' . $row["quantity"] . '</td>';
            echo '<td>' . number_format($row["total_price"], 2) . '</td>';
            echo '<td>' . htmlspecialchars($row["status"]) . '</td>';
            echo '<td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="order_id" value="' . $row["order_id"] . '">
                        <button type="submit" name="update_status" class="' . ($row["status"] == 'Pending' ? 'approve-btn' : 'cancel-btn') . '">' . ($row["status"] == 'Pending' ? 'Approve' : 'Cancel') . '</button>
                    </form>
                    </td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>No records found.</p>';
    }

    $conn->close();
    ?>

    <!-- Back Button -->
    <a href="index.html">
        <button class="back-btn">Back to Home</button>
    </a>
</body>
</html>
