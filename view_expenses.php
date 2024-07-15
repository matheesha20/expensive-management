<?php
include("config.php");
//session_start();
if(!isset($_SESSION['username'])){
    header("location: login.php");
    die();
}

// Initialize the $month variable
$month = date('Y-m');

if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET['month'])) {
    $month = $_GET['month'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        $conn->query("DELETE FROM personal_expenses WHERE id='$id'");
    } elseif (!empty($_POST['edit_id'])) {
        $id = $_POST['edit_id'];
        $item = $_POST['item'];
        $price = $_POST['price'];
        $used_price = $_POST['used_price'];
        $conn->query("UPDATE personal_expenses SET item='$item', price='$price', used_price='$used_price' WHERE id='$id'");
    }
}

$expenses_result = $conn->query("SELECT * FROM personal_expenses WHERE DATE_FORMAT(month, '%Y-%m') = '$month'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Personal Expenses</title>
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
        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="submit"],
        .form-container table {
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
        .form-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .form-container table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .form-container th {
            background-color: #f2f2f2;
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
            .form-container table, th, td {
                font-size: 0.8rem;
            }
        }
        @media (max-width: 480px) {
            .form-container {
                padding: 10px;
            }
            .form-container table, th, td {
                font-size: 0.7rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>View Personal Expenses</h1>
    </header>
    <div class="container">
        <div class="form-container">
            <form method="GET" action="personal_expenses.php">
                <input type="hidden" name="section" value="view_personal_expenses">
                Month: <input type="month" name="month" value="<?php echo $month; ?>" required>
                <input type="submit" value="View Expenses">
            </form>

            <?php if (isset($month)) { ?>
                <h2>Personal Expenses for <?php echo date("F Y", strtotime($month)); ?></h2>
                <?php if ($expenses_result->num_rows > 0) { ?>
                    <table>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Used Price</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        <?php while ($row = $expenses_result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['item']; ?></td>
                                <td><?php echo $row['price']; ?></td>
                                <td><?php echo $row['used_price']; ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                        Item: <input type="text" name="item" value="<?php echo $row['item']; ?>" required><br>
                                        Price: <input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>" required><br>
                                        Used Price: <input type="number" step="0.01" name="used_price" value="<?php echo $row['used_price']; ?>" required><br>
                                        <input type="submit" value="Update">
                                    </form>
                                </td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                        <input type="submit" value="Delete">
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } else {
                    echo "<p>No personal expenses found for $month.</p>";
                } ?>
            <?php } ?>
            <p><a href="index.php">Back to Home</a></p>
        </div>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Monthly Expenses Tracker</p>
    </footer>
</body>
</html>
