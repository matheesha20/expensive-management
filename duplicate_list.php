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
        .form-container input[type="month"],
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
        <h1>Duplicate Market Price List</h1>
    </header>
    <div class="container">
        <div class="form-container">
            <form method="post">
                <label for="new_month">New Month:</label>
                <input type="month" name="new_month" required>
                <button type="submit">Duplicate</button>
            </form>
        </div>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Monthly Expenses Tracker</p>
    </footer>
</body>
</html>
