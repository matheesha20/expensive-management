<?php
include("config.php");
//session_start();
if(!isset($_SESSION['username'])){
    header("location: login.php");
    die();
}

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = ""; // Variable to store messages

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['section'])) {
        if ($_POST['section'] == 'add_personal_expense') {
            // Adding a new personal expense
            $month = $_POST['month'] . "-01";  // Add day to make it a valid date
            $item = $_POST['item'];
            $price = $_POST['price'];

            if (!empty($month) && !empty($item) && !empty($price)) {
                $sql = "INSERT INTO personal_expenses (month, item, price) VALUES ('$month', '$item', '$price')";
                if ($conn->query($sql) === TRUE) {
                    $message = "New expense added successfully";
                } else {
                    $message = "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                $message = "All fields are required.";
            }
        } elseif ($_POST['section'] == 'update_used_price') {
            // Updating the used price
            $item_used = $_POST['item_used'];
            $used_price = $_POST['used_price'];

            if (!empty($item_used) && !empty($used_price)) {
                $sql = "UPDATE personal_expenses SET used_price = '$used_price' WHERE id = '$item_used'";
                if ($conn->query($sql) === TRUE) {
                    $message = "Used price updated successfully";
                } else {
                    $message = "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                $message = "All fields are required.";
            }
        } else {
            $message = "Invalid section.";
        }
    } else {
        $message = "Section not set.";
    }
}

// Fetch personal expenses items
$items_result = $conn->query("SELECT id, item FROM personal_expenses");

if ($conn->error) {
    $message = "Error fetching items: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Personal Expense</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            margin-bottom: 20px;
        }
        .form-container h1, .form-container h2 {
            text-align: center;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
        }
        .form-container input[type="month"],
        .form-container input[type="text"],
        .form-container input[type="number"],
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
        .message {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
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
        <h1>Add Personal Expense</h1>
    </header>
    <div class="container">
        <?php if (!empty($message)) { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>
        <div class="form-container">
            <form method="POST" action="">
                <input type="hidden" name="section" value="add_personal_expense">
                Month: <input type="month" name="month" required><br>
                Item: <input type="text" name="item" required><br>
                Price: <input type="number" step="0.01" name="price" required><br>
                <input type="submit" value="Add Expense">
            </form>
        </div>
        <div class="form-container">
            <h2>Update Used Price</h2>
            <form method="POST" action="">
                <input type="hidden" name="section" value="update_used_price">
                Item: 
                <select name="item_used" required>
                    <?php while ($row = $items_result->fetch_assoc()) { echo "<option value='".$row['id']."'>".$row['item']."</option>"; } ?>
                </select><br>
                Used Price: <input type="number" step="0.01" name="used_price" required><br>
                <input type="submit" value="Update Used Price">
            </form>
        </div>
        <p style="text-align:center;"><a href="index.php">Back to Home</a></p>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Monthly Expenses Tracker</p>
    </footer>
</body>
</html>
