<?php
include("config.php");
//session_start();
if(!isset($_SESSION['username'])){
    header("location: login.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $loans_result = $conn->query("SELECT * FROM loans");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        $conn->query("DELETE FROM loans WHERE id='$id'");
    } elseif (!empty($_POST['edit_id'])) {
        $id = $_POST['edit_id'];
        $company_person = $_POST['company_person'];
        $amount = $_POST['amount'];
        $conn->query("UPDATE loans SET company_person='$company_person', amount='$amount' WHERE id='$id'");
    } elseif (!empty($_POST['loan_id'])) {
        $loan_id = $_POST['loan_id'];
        $repaid_date = $_POST['repaid_date'];
        $amount = $_POST['repaid_amount'];
        $conn->query("INSERT INTO loan_repayments (loan_id, repaid_date, amount) VALUES ('$loan_id', '$repaid_date', '$amount')");
    }
}

$loans_result = $conn->query("SELECT * FROM loans");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Loans</title>
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
        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container input[type="date"],
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
        <h1>View Loans</h1>
        
        <?php if ($loans_result->num_rows > 0) { ?>
            <div class="form-container">
                <table>
                    <tr>
                        <th>Company/Person</th>
                        <th>Amount</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        <th>Record Repayment</th>
                    </tr>
                    <?php while ($row = $loans_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['company_person']; ?></td>
                            <td><?php echo $row['amount']; ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                    Company/Person: <input type="text" name="company_person" value="<?php echo $row['company_person']; ?>" required><br>
                                    Amount: <input type="number" step="0.01" name="amount" value="<?php echo $row['amount']; ?>" required><br>
                                    <input type="submit" value="Update">
                                </form>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                    <input type="submit" value="Delete">
                                </form>
                            </td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="loan_id" value="<?php echo $row['id']; ?>">
                                    Repaid Date: <input type="date" name="repaid_date" required><br>
                                    Amount: <input type="number" step="0.01" name="repaid_amount" required><br>
                                    <input type="submit" value="Record Repayment">
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        <?php } else {
            echo "<p>No loans found.</p>";
        } ?>
        <p><a href="index.php">Back to Home</a></p>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Monthly Expenses Tracker</p>
    </footer>
</body>
</html>
