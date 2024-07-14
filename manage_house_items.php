<?php
include 'config.php';

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
</head>
<body>
    <div class="container">
        <h1>Add or Update House Item</h1>
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
</body>
</html>
