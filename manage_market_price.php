<?php
include 'config.php';

session_start(); // Make sure session is started

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_name'])) {
    $item_name = $conn->real_escape_string($_POST['item_name']);
    $unit_price = $conn->real_escape_string($_POST['unit_price']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $unit = $conn->real_escape_string($_POST['unit']);
    $month = $conn->real_escape_string($_POST['month']);

    $sql = "INSERT INTO market_price_list (item_name, unit_price, quantity, unit, month) VALUES ('$item_name', '$unit_price', '$quantity', '$unit', '$month')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New market price item created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Market Price List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Add Market Price Item</h1>
        <form method="post" action="">
            <label for="item_name">Item Name:</label>
            <input type="text" id="item_name" name="item_name" required><br>
            <label for="unit_price">Unit Price:</label>
            <input type="number" id="unit_price" name="unit_price" step="0.01" required><br>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" step="0.01" required><br>
            <label for="unit">Unit:</label>
            <input type="text" id="unit" name="unit" required><br>
            <label for="month">Month:</label>
            <input type="month" id="month" name="month" required><br>
            <input type="submit" value="Add Item">
        </form>
    </div>
</body>
</html>
