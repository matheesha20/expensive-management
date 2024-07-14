<?php
include 'config.php';

$itemId = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemName = $_POST['item_name'];
    $unitPrice = $_POST['unit_price'];
    $quantity = $_POST['quantity'];
    $unit = $_POST['unit'];

    $updateItem = $conn->prepare("UPDATE market_price_list SET item_name = ?, unit_price = ?, quantity = ?, unit = ? WHERE id = ?");
    $updateItem->bind_param("sdssi", $itemName, $unitPrice, $quantity, $unit, $itemId);
    $updateItem->execute();

    header("Location: house_expenses.php?section=view_lists");
    exit;
}

$query = $conn->prepare("SELECT * FROM market_price_list WHERE id = ?");
$query->bind_param("i", $itemId);
$query->execute();
$item = $query->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Market Price List Item</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit Market Price List Item</h1>
        <form method="POST">
            <label for="item_name">Item Name:</label>
            <input type="text" name="item_name" value="<?php echo $item['item_name']; ?>" required>
            <label for="unit_price">Unit Price:</label>
            <input type="number" name="unit_price" step="0.01" value="<?php echo $item['unit_price']; ?>" required>
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" required>
            <label for="unit">Unit:</label>
            <input type="text" name="unit" value="<?php echo $item['unit']; ?>" required>
            <button type="submit">Save</button>
        </form>
    </div>
</body>
</html>
