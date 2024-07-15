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
        .form-container button {
            margin: 10px 0;
            padding: 10px;
            font-size: 1rem;
        }
        .form-container button {
            background: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.3s;
        }
        .form-container button:hover {
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
        <h1>Edit House Item List</h1>
    </header>
    <div class="container">
        <div class="form-container">
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
        <p style="text-align:center;"><a href="house_expenses.php?section=view_lists">Back to View Lists</a></p>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Monthly Expenses Tracker</p>
    </footer>
</body>
</html>
