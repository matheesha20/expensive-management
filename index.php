<?php
include 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Existing content...
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<div class="container">
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="house_expenses.php">House Expenses</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <!-- Existing content of index.php -->
</div>
</body>
</html>
