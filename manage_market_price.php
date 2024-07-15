<?php
include 'config.php';

//session_start(); // Make sure session is started

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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        header {
            background: #333;
            color: #fff;
            padding: 1rem 0;
            text-align: center;
        }
        header h1 {
            margin: 0;
            font-size: 2rem;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .form-container h1 {
            text-align: center;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .form-container label,
        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="month"],
        .form-container input[type="submit"] {
            margin: 10px 0;
            padding: 10px;
            font-size: 1rem;
        }
        .form-container input[type="submit"] {
            background: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }
        .form-container input[type="submit"]:hover {
            background: #555;
        }
        footer {
            text-align: center;
            padding: 1rem 0;
            background: #333;
            color: #fff;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
            }
        }
        @media (max-width: 480px) {
            .form-container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Add Market Price List</h1>
    </header>
    <div class="container">
        <div class="form-container">
         <!--   <h1>Add Market Price Item</h1> -->
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
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Monthly Expenses Tracker</p>
    </footer>
</body>
</html>
