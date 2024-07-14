<?php
include 'config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$monthYear = $_GET['monthYear'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newMonthYear = $_POST['new_month'];  // This will be in the format "YYYY-MM"

    // Fetch the items from the selected list
    $query = $conn->prepare("SELECT * FROM market_price_list WHERE month = ?");
    $query->bind_param("s", $monthYear);
    $query->execute();
    $result = $query->get_result();
    
    if ($result === FALSE) {
        echo "<!-- SQL Error (Market): " . $conn->error . " -->";
        exit();
    }

    $items = $result->fetch_all(MYSQLI_ASSOC);

    // Insert a new list for the specified month and year
    $insertList = $conn->prepare("INSERT INTO market_price_list (item_name, unit_price, quantity, unit, month) VALUES (?, ?, ?, ?, ?)");
    foreach ($items as $item) {
        $insertList->bind_param("sdiss", $item['item_name'], $item['unit_price'], $item['quantity'], $item['unit'], $newMonthYear);
        $insertList->execute();
    }

    // Redirect back to the main page
    header("Location: house_expenses.php?section=view_lists");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duplicate Market Price List</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Duplicate Market Price List</h1>
        <form method="post">
            <label for="new_month">New Month:</label>
            <input type="month" name="new_month" required>
            <button type="submit">Duplicate</button>
        </form>
    </div>
</body>
</html>
