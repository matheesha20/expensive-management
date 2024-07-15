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
        $conn->query("DELETE FROM income_sources WHERE id='$id'");
    } elseif (!empty($_POST['edit_id'])) {
        $id = $_POST['edit_id'];
        $source = $_POST['source'];
        $amount = $_POST['amount'];
        $income_date = $_POST['income_date'] . "-01";  // Add day to make it a valid date
        $conn->query("UPDATE income_sources SET source='$source', amount='$amount', income_date='$income_date' WHERE id='$id'");
    } elseif (!empty($_POST['remove_assignment_id'])) {
        $assignment_id = $_POST['remove_assignment_id'];
        $conn->query("DELETE FROM income_assignments WHERE id='$assignment_id'");
    }
}

$income_result = $conn->query("SELECT * FROM income_sources WHERE DATE_FORMAT(income_date, '%Y-%m') = '$month'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Income Sources</title>
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
        <h1>View Income Sources</h1>
    </header>
    <div class="container">
        <div class="form-container">
            <form method="GET" action="income_source.php">
                <input type="hidden" name="section" value="view_income_sources">
                Month: <input type="month" name="month" value="<?php echo $month; ?>" required>
                <input type="submit" value="View Income Sources">
            </form>

            <?php if (isset($month)) { ?>
                <h2>Income Sources for <?php echo date("F Y", strtotime($month)); ?></h2>
                <?php if ($income_result->num_rows > 0) { ?>
                    <table>
                        <tr>
                            <th>Source</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        <?php while ($row = $income_result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $row['source']; ?></td>
                                <td><?php echo $row['amount']; ?></td>
                                <td><?php echo $row['income_date']; ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                        Source: <input type="text" name="source" value="<?php echo $row['source']; ?>" required><br>
                                        Amount: <input type="number" step="0.01" name="amount" value="<?php echo $row['amount']; ?>" required><br>
                                        Date: <input type="month" name="income_date" value="<?php echo date('Y-m', strtotime($row['income_date'])); ?>" required><br>
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
                            <tr>
                                <td colspan="5">
                                    <h3>Assigned Items</h3>
                                    <?php
                                    $income_id = $row['id'];
                                    $assignments_result = $conn->query("SELECT ia.id as assignment_id, ia.item_type, ia.item_id, 
                                                                        CASE 
                                                                            WHEN ia.item_type = 'personal_expense' THEN pe.item
                                                                            WHEN ia.item_type = 'bank_deposit' THEN bd.bank
                                                                            WHEN ia.item_type = 'loan' THEN l.company_person
                                                                            WHEN ia.item_type = 'house_item' THEN mpl.item_name
                                                                        END as item_name
                                                                        FROM income_assignments ia
                                                                        LEFT JOIN personal_expenses pe ON ia.item_id = pe.id AND ia.item_type = 'personal_expense'
                                                                        LEFT JOIN bank_deposits bd ON ia.item_id = bd.id AND ia.item_type = 'bank_deposit'
                                                                        LEFT JOIN loans l ON ia.item_id = l.id AND ia.item_type = 'loan'
                                                                        LEFT JOIN house_item_list hil ON ia.item_id = hil.id AND ia.item_type = 'house_item'
                                                                        LEFT JOIN market_price_list mpl ON hil.market_item_id = mpl.id
                                                                        WHERE ia.income_id = '$income_id'");
                                    if ($assignments_result->num_rows > 0) { ?>
                                        <ul>
                                            <?php while ($assignment = $assignments_result->fetch_assoc()) { ?>
                                                <li>
                                                    <?php echo $assignment['item_name']; ?>
                                                    <form method="POST" style="display:inline;">
                                                        <input type="hidden" name="remove_assignment_id" value="<?php echo $assignment['assignment_id']; ?>">
                                                        <input type="submit" value="Remove">
                                                    </form>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } else {
                                        echo "No items assigned to this income source.";
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                <?php } else {
                    echo "No income sources found for this month.";
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
