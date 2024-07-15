<?php
include 'config.php';

//session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Existing content...
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Expenses</title>
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
        nav ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            background: #444;
            margin: 0;
        }
        nav ul li {
            margin: 5px;
        }
        nav ul li a {
            display: block;
            padding: 10px 20px;
            background: #555;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        nav ul li a:hover {
            background: #666;
        }
        nav ul li a:active {
            background: #777;
        }
        .content {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
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
            nav ul {
                flex-direction: column;
                align-items: center;
            }
            nav ul li {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
<header>
    <h1>Personal Expenses</h1>
</header>
<div class="container">
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="personal_expenses.php?section=add_personal_expense">Add Personal Expense</a></li>
            <li><a href="personal_expenses.php?section=view_personal_expenses">View Personal Expenses</a></li>
        </ul>
    </nav>

    <div class="content">
        <?php
        if (isset($_GET['section'])) {
            $section = $_GET['section'];
            switch ($section) {
                case 'add_personal_expense':
                    include 'add_expense.php';
                    break;
                case 'view_personal_expenses':
                    include 'view_expenses.php';
                    break;
                default:
                    echo '<p>Welcome to the Personal Expenses page. Please select a section from the menu above.</p>';
                    break;
            }
        } else {
            echo '<p>Welcome to the Personal Expenses page. Please select a section from the menu above.</p>';
        }
        ?>
    </div>
</div>
<footer>
    <p>&copy; <?php echo date("Y"); ?> Monthly Expenses Tracker</p>
</footer>
</body>
</html>
