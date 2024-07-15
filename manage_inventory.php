<?php
include 'config.php';

//session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['market_item_id'])) {
    $market_item_id = $conn->real_escape_string($_POST['market_item_id']);
    $bought_quantity = floatval($conn->real_escape_string($_POST['bought_quantity']));
    $month = $conn->real_escape_string($_POST['month']);

    // Fetch required_quantity and total_price from house_item_list
    $sql = "SELECT required_quantity, total_price FROM house_item_list WHERE market_item_id = '$market_item_id' AND month = '$month'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $required_quantity = $row['required_quantity'];
        $total_price = $row['total_price'];

        $bought_amount = $total_price / $required_quantity * $bought_quantity;

        // Fetch existing values from inventory
        $sql = "SELECT id, bought_quantity, bought_amount FROM inventory 
                WHERE market_item_id = '$market_item_id' AND month = '$month'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $inventory = $result->fetch_assoc();
            $existing_id = $inventory['id'];
            $existing_bought_quantity = $inventory['bought_quantity'];
            $existing_bought_amount = $inventory['bought_amount'];
            
            // Calculate new values
            $new_bought_quantity = $existing_bought_quantity + $bought_quantity;
            $new_bought_amount = $existing_bought_amount + $bought_amount;
            $remaining_quantity = $required_quantity - $new_bought_quantity;
            $remaining_price = $total_price - $new_bought_amount;

            // Update the existing record in the inventory table
            $sql = "UPDATE inventory SET 
                    bought_quantity = '$new_bought_quantity', 
                    bought_amount = '$new_bought_amount', 
                    remaining_quantity = '$remaining_quantity', 
                    remaining_price = '$remaining_price'
                    WHERE id = '$existing_id'";
        } else {
            // Calculate remaining values for the new record
            $remaining_quantity = $required_quantity - $bought_quantity;
            $remaining_price = $total_price - $bought_amount;

            // Insert the new record into the inventory table
            $sql = "INSERT INTO inventory (market_item_id, bought_quantity, bought_amount, remaining_quantity, remaining_price, month) 
                    VALUES ('$market_item_id', '$bought_quantity', '$bought_amount', '$remaining_quantity', '$remaining_price', '$month')";
        }

        if ($conn->query($sql) === TRUE) {
            echo "Inventory updated successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Item not found in house item list";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory</title>
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
        .form-container input[type="number"],
        .form-container input[type="month"],
        .form-container select,
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
        <h1>Manage Inventory</h1>
    </header>
    <div class="container">
        <div class="form-container">
          <!--  <h1>Add Inventory Item</h1>  -->
            <form method="post" action="">
                <label for="market_item_id">Market Item:</label>
                <select id="market_item_id" name="market_item_id" required>
                    <?php
                    $sql = "SELECT id, item_name, unit FROM market_price_list";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='".$row['id']."'>".$row['item_name']." (".$row['unit'].")</option>";
                        }
                    } else {
                        echo "<option value=''>No items available</option>";
                    }
                    ?>
                </select><br>
                <label for="bought_quantity">Bought Quantity:</label>
                <input type="number" id="bought_quantity" name="bought_quantity" step="0.01" required><br>
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
