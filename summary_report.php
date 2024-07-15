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

function getExpensesForIncomeSource($conn, $income_id) {
    $total = 0;

    $expense_result = $conn->query("SELECT price FROM personal_expenses WHERE id IN (SELECT item_id FROM income_assignments WHERE income_id='$income_id' AND item_type='personal_expense')");
    while ($row = $expense_result->fetch_assoc()) {
        $total += $row['price'];
    }

    $deposit_result = $conn->query("SELECT deposit_amount FROM bank_deposits WHERE id IN (SELECT item_id FROM income_assignments WHERE income_id='$income_id' AND item_type='bank_deposit')");
    while ($row = $deposit_result->fetch_assoc()) {
        $total += $row['deposit_amount'];
    }

    $loan_result = $conn->query("SELECT amount FROM loans WHERE id IN (SELECT item_id FROM income_assignments WHERE income_id='$income_id' AND item_type='loan')");
    while ($row = $loan_result->fetch_assoc()) {
        $total += $row['amount'];
    }

    $house_item_result = $conn->query("SELECT total_price FROM house_item_list WHERE id IN (SELECT item_id FROM income_assignments WHERE income_id='$income_id' AND item_type='house_item')");
    while ($row = $house_item_result->fetch_assoc()) {
        $total += $row['total_price'];
    }

    return $total;
}

$income_sources_result = $conn->query("SELECT * FROM income_sources WHERE DATE_FORMAT(income_date, '%Y-%m') = '$month'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Summary Report</title>
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
    <div class="container">
        <div class="form-container">
            <form method="GET" action="summary.php">
                <input type="hidden" name="section" value="report">
                Month: 
                <input type="month" name="month" value="<?php echo $month; ?>" required>
                <input type="submit" value="View Report">
            </form>

            <?php if (isset($month)) { ?>
                <h2>Summary for <?php echo date("F Y", strtotime($month)); ?></h2>
                <?php if ($income_sources_result->num_rows > 0) { ?>
                    <table>
                        <tr>
                            <th>Income Source</th>
                            <th>Total Income</th>
                            <th>Total Expenses</th>
                        </tr>
                        <?php while ($row = $income_sources_result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['source']; ?></td>
                                <td><?php echo $row['amount']; ?></td>
                                <td><?php echo getExpensesForIncomeSource($conn, $row['id']); ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } else {
                    echo "<p>No income sources found for this month.</p>";
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
