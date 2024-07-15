<?php
include 'config.php';

//session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['market_item_id'])) {
    $market_item_id = $conn->real_escape_string($_POST['market_item_id']);
    $required_quantity = floatval($conn->real_escape_string($_POST['required_quantity']));
    $month = $conn->real_escape_string($_POST['month']);

    $sql = "SELECT unit_price FROM market_price_list WHERE id = '$market_item_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $unit_price = $row['unit_price'];
        $total_price = $unit_price * $required_quantity;

        // Check if the item already exists for the given month
        $check_sql = "SELECT id FROM house_item_list WHERE market_item_id = '$market_item_id' AND month = '$month'";
        $check_result = $conn->query($check_sql);
        if ($check_result->num_rows > 0) {
            // Update existing entry
            $update_row = $check_result->fetch_assoc();
            $update_id = $update_row['id'];
            $sql = "UPDATE house_item_list SET required_quantity = '$required_quantity', total_price = '$total_price' WHERE id = '$update_id'";
            if ($conn->query($sql) === TRUE) {
                echo "House item updated successfully";
            } else {
                echo "Error updating item: " . $conn->error;
            }
        } else {
            // Insert new entry
            $sql = "INSERT INTO house_item_list (market_item_id, required_quantity, total_price, month) VALUES ('$market_item_id', '$required_quantity', '$total_price', '$month')";
            if ($conn->query($sql) === TRUE) {
                echo "New house item created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage House Item List</title>
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
        <h1>Add or Update House Item List</h1>
    </header>
    <div class="container">
        <div class="form-container">
          <!--  <h1>Add or Update House Item</h1>  -->
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
                <label for="required_quantity">Required Quantity:</label>
                <input type="number" id="required_quantity" name="required_quantity" step="0.01" required><br>
                <label for="month">Month:</label>
                <input type="month" id="month" name="month" required><br>
                <input type="submit" value="Add or Update Item">
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Monthly Expenses Tracker</p>
    </footer>
</body>
</html>
