<?php
include("config.php");
//session_start();
if(!isset($_SESSION['username'])){
    header("location: login.php");
    die();
}

$income_sources_result = $conn->query("SELECT id, source FROM income_sources");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $income_id = $_POST['income_id'];
    $item_type = $_POST['item_type'];
    $month = $_POST['month'] . "-01";  // Add day to make it a valid date

    if (isset($_POST['item_ids'])) {
        foreach ($_POST['item_ids'] as $item_id) {
            $sql = "INSERT INTO income_assignments (income_id, item_type, item_id, month) VALUES ('$income_id', '$item_type', '$item_id', '$month')";
            if (!$conn->query($sql)) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        echo "Items assigned to income source successfully";
    }

    if (!empty($_POST['loan_id'])) {
        $loan_id = $_POST['loan_id'];
        $repaid_date = $_POST['repaid_date'] . "-01";  // Add day to make it a valid date
        $amount = $_POST['repaid_amount'];
        $sql = "INSERT INTO loan_repayments (loan_id, repaid_date, amount) VALUES ('$loan_id', '$repaid_date', '$amount')";
        if ($conn->query($sql) === TRUE) {
            echo "Repayment recorded successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Items to Income Source</title>
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
        .form-container input[type="submit"],
        .form-container select {
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
    <script>
        function loadItems() {
            var itemType = document.getElementById('item_type').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'load_items.php?type=' + itemType, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById('items').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
    </script>
</head>
<body>
    <header>
        <h1>Assign Items to Income Source</h1>
    </header>
    <div class="container">
        <div class="form-container">
            <form method="POST">
                Income Source:
                <select name="income_id" required>
                    <?php while ($row = $income_sources_result->fetch_assoc()) { echo "<option value='".$row['id']."'>".$row['source']."</option>"; } ?>
                </select><br>

                Month: <input type="month" name="month" required><br>

                Item Type:
                <select name="item_type" id="item_type" onchange="loadItems()" required>
                    <option value="">Select Item Type</option>
                    <option value="personal_expense">Personal Expense</option>
                    <option value="bank_deposit">Bank Deposit</option>
                    <option value="loan">Loan</option>
                    <option value="house_item">House Item</option>
                </select><br>

                Items:
                <div id="items">
                    <!-- Items will be loaded here based on the selected item type -->
                </div>

                <input type="submit" value="Assign Items">
            </form>
        </div>
        <div class="form-container">
            <h2>Record Loan Repayment</h2>
            <form method="POST">
                Loan: 
                <select name="loan_id" required>
                    <?php
                    $loans_result = $conn->query("SELECT id, company_person FROM loans");
                    while ($row = $loans_result->fetch_assoc()) { echo "<option value='".$row['id']."'>".$row['company_person']."</option>"; }
                    ?>
                </select><br>
                Repaid Date: <input type="month" name="repaid_date" required><br>
                Amount: <input type="number" step="0.01" name="repaid_amount" required><br>
                <input type="submit" value="Record Repayment">
            </form>
        </div>
        <p style="text-align:center;"><a href="index.php">Back to Home</a></p>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Monthly Expenses Tracker</p>
    </footer>
</body>
</html>
