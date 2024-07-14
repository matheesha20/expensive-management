<?php
include 'config.php';

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
    <title>House Expenses</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1>House Expenses</h1>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="house_expenses.php?section=manage_market_price">Manage Market Price List</a></li>
            <li><a href="house_expenses.php?section=manage_house_items">Manage House Item List</a></li>
            <li><a href="house_expenses.php?section=view_lists">View Lists</a></li>
            <li><a href="house_expenses.php?section=manage_inventory">Manage Inventory</a></li>
        </ul>
    </nav>

    <div class="content">
        <?php
        if (isset($_GET['section'])) {
            $section = $_GET['section'];
            switch ($section) {
                case 'manage_market_price':
                    include 'manage_market_price.php';
                    break;
                case 'manage_house_items':
                    include 'manage_house_items.php';
                    break;
                case 'view_lists':
                    include 'view_lists.php';
                    break;
                case 'manage_inventory':
                    include 'manage_inventory.php';
                    break;
                default:
                    echo '<p>Welcome to the House Expenses page. Please select a section from the menu above.</p>';
                    break;
            }
        } else {
            echo '<p>Welcome to the House Expenses page. Please select a section from the menu above.</p>';
        }
        ?>
    </div>
</div>
</body>
</html>
