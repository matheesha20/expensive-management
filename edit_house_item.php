<?php
include 'config.php';

$itemId = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $boughtQuantity = $_POST['bought_quantity'];
    $boughtAmount = $_POST['bought_amount'];
    $remainingQuantity = $_POST['remaining_quantity'];
    $remainingAmount = $_POST['remaining_amount'];

    $updateItem = $conn->prepare("UPDATE house_item_list SET bought_quantity = ?, bought_amount = ?, remaining_quantity = ?, remaining_amount = ? WHERE id = ?");
    $updateItem->bind_param("iddii", $boughtQuantity, $boughtAmount, $remainingQuantity, $remainingAmount, $itemId);
    $updateItem->execute();

    header("Location: house_expenses.php?section=view_lists");
    exit;
}

$query = $conn->prepare("SELECT * FROM house_item_list WHERE id = ?");
$query->bind_param("i", $itemId);
$query->execute();
$item = $query->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit House Item List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Edit House Item List</h1>
        <form method="POST">
            <label for="bought_quantity">Bought Quantity:</label>
            <input type="number" name="bought_quantity" value="<?php echo $item['bought_quantity']; ?>" required>
            <label for="bought_amount">Bought Amount:</label>
            <input type="number" name="bought_amount" step="0.01" value="<?php echo $item['bought_amount']; ?>" required>
            <label for="remaining_quantity">Remaining Quantity:</label>
            <input type="number" name="remaining_quantity" value="<?php echo $item['remaining_quantity']; ?>" required>
            <label for="remaining_amount">Remaining Amount:</label>
            <input type="number" name="remaining_amount" step="0.01" value="<?php echo $item['remaining_amount']; ?>" required>
            <button type="submit">Save</button>
        </form>
    </div>
</body>
</html>
