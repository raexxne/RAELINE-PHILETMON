<?php
// Establish database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "Shinigami_02";     // Replace with your database password
$dbname = "burgerqueen"; // Replace with your database name

$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add a new item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $name = $_POST['burger_name'];
    $description = $_POST['burger_description'];
    $price = $_POST['burger_price'];
    $stock = $_POST['burger_stock'];
    $image = $_POST['burger_image'];

    $sql = "INSERT INTO burgers (name, description, price, stock, image) VALUES ('$name', '$description', '$price', '$stock', '$image')";
    if ($conn->query($sql) === TRUE) {
        echo "New item added successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Update stock
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_stock'])) {
    $id = $_POST['burger_id'];
    $stock = $_POST['burger_stock'];

    $sql = "UPDATE burgers SET stock='$stock' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Stock updated successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch items
$burgers = [];
$sql = "SELECT * FROM burgers";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $burgers[] = $row;
    }
}
$conn->close();
?>
