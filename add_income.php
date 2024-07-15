<?php
include("config.php");
//session_start();
if(!isset($_SESSION['username'])){
    header("location: login.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $source = $_POST['source'];
    $amount = $_POST['amount'];
    $income_date = $_POST['income_date'] . "-01";  // Add day to make it a valid date
    $sql = "INSERT INTO income_sources (source, amount, income_date) VALUES ('$source', '$amount', '$income_date')";
    if ($conn->query($sql) === TRUE) {
        echo "New income source added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Income Source</title>
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
        }
        .form-container h1, .form-container h2 {
            text-align: center;
        }
        .form-container form {
            display: flex;
            flex-direction: column;
        }
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
        <h1>Add Income Source</h1>
    </header>
    <div class="container">
        <div class="form-container">
            <form method="POST">
                Source: <input type="text" name="source" required><br>
                Amount: <input type="number" step="0.01" name="amount" required><br>
                Date: <input type="month" name="income_date" required><br>
                <input type="submit" value="Add Income Source">
            </form>
        </div>
        <p style="text-align:center;"><a href="index.php">Back to Home</a></p>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Monthly Expenses Tracker</p>
    </footer>
</body>
</html>
