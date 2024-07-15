<?php
include("config.php");
//session_start();
if(!isset($_SESSION['username'])){
    header("location: login.php");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Expenses Tracker</title>
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
        .nav {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            background: #444;
        }
        .nav a {
            flex: 1 0 21%;
            margin: 10px;
            text-align: center;
            padding: 10px;
            background: #555;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .nav a:hover {
            background: #666;
        }
        .nav a:active {
            background: #777;
        }
        @media (max-width: 768px) {
            .nav a {
                flex: 1 0 46%;
            }
        }
        @media (max-width: 480px) {
            .nav a {
                flex: 1 0 100%;
            }
        }
        footer {
            text-align: center;
            padding: 1rem 0;
            background: #333;
            color: #fff;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Monthly Expenses Tracker</h1>
        <p><a href="logout.php" style="color: #fff; text-decoration: underline;">Logout</a></p>
    </header>
    <div class="container">
        <h2>Manage Your Data</h2>
        <div class="nav">
            <a href="house_expenses.php">House Expenses</a>
            <a href="personal_expenses.php">Personal Expenses</a>
            <a href="bank_deposit.php">Bank Deposit</a>
            <a href="loan.php">Loan</a>
            <a href="income_source.php">Income Source</a>
            <a href="summary.php">Summary Report</a>
        </div>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Monthly Expenses Tracker</p>
    </footer>
</body>
</html>
