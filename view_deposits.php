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
        $conn->query("DELETE FROM bank_deposits WHERE id='$id'");
    } elseif (!empty($_POST['edit_id'])) {
        $id = $_POST['edit_id'];
        $bank = $_POST['bank'];
        $deposit_amount = $_POST['deposit_amount'];
        $status = $_POST['status'];
        $conn->query("UPDATE bank_deposits SET bank='$bank', deposit_amount='$deposit_amount', status='$status' WHERE id='$id'");
    }
}

$deposits_result = $conn->query("SELECT * FROM bank_deposits WHERE DATE_FORMAT(month, '%Y-%m') = '$month'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bank Deposits</title>
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
        .form-container select,
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
        <h1>View Bank Deposits</h1>
    </header>
    <div class="container">
        <div class="form-container">
            <form method="GET" action="view_deposits.php">
                Month: <input type="month" name="month" value="<?php echo $month; ?>" required>
                <input type="submit" value="View Deposits">
            </form>

            <?php if (isset($month)) { ?>
                <h2>Bank Deposits for <?php echo date("F Y", strtotime($month)); ?></h2>
                <?php if ($deposits_result->num_rows > 0) { ?>
                    <table>
                        <tr>
                            <th>Bank</th>
                            <th>Deposit Amount</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        <?php while ($row = $deposits_result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['bank']; ?></td>
                                <td><?php echo $row['deposit_amount']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                        Bank: <input type="text" name="bank" value="<?php echo $row['bank']; ?>" required><br>
                                        Deposit Amount: <input type="number" step="0.01" name="deposit_amount" value="<?php echo $row['deposit_amount']; ?>" required><br>
                                        Status: 
                                        <select name="status" required>
                                            <option value="completed" <?php if($row['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                            <option value="ignored" <?php if($row['status'] == 'ignored') echo 'selected'; ?>>Ignored</option>
                                            <option value="failed" <?php if($row['status'] == 'failed') echo 'selected'; ?>>Failed</option>
                                            <option value="pending" <?php if($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                        </select><br>
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
                    echo "<p>No bank deposits found for $month.</p>";
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
