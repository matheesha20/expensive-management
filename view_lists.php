<?php
include 'config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle deletion of items
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $conn->real_escape_string($_POST['delete_id']);
    
    // Check if the item exists in the house_item_list
    $sql = "SELECT market_item_id FROM house_item_list WHERE id = '$delete_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $market_item_id = $row['market_item_id'];

        // Delete from house_item_list
        $sql = "DELETE FROM house_item_list WHERE id = '$delete_id'";
        if ($conn->query($sql) === TRUE) {
            // Also delete from inventory based on the retrieved market_item_id
            $sql = "DELETE FROM inventory WHERE market_item_id = '$market_item_id'";
            $conn->query($sql); // Deleting related inventory items
        } else {
            echo "Error deleting from house item list: " . $conn->error;
        }
    } else {
        // If not found in house_item_list, check market_price_list
        $sql = "DELETE FROM market_price_list WHERE id = '$delete_id'";
        if ($conn->query($sql) === TRUE) {
            echo "Item deleted from market price list successfully.";
        } else {
            echo "No item found with the given ID in house item list or market price list.";
        }
    }
}

// Function to retrieve and display market price list items by month and year
function displayMarketItems($conn, $month_year) {
    $sql = "SELECT * FROM market_price_list WHERE month = '$month_year'";
    $result = $conn->query($sql);

    if ($result === FALSE) {
        echo "<!-- SQL Error (Market): " . $conn->error . " -->";
    }

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['item_name'] . "</td>";
            echo "<td>LKR " . number_format($row['unit_price'], 2) . "</td>";
            echo "<td>" . number_format($row['quantity'], 2) . "</td>";
            echo "<td>" . $row['unit'] . "</td>";
            echo "<td><form method='post'><input type='hidden' name='delete_id' value='" . $row['id'] . "'><button type='submit'>Delete</button></form></td>";
            echo "<td><button onclick=\"editItem('" . $row['id'] . "')\">Edit</button></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No items found for the selected month.</td></tr>";
    }
}

// Function to retrieve and display house item list items by month and year
function displayHouseItems($conn, $month_year) {
    $sql = "SELECT hil.*, mpl.item_name, 
            IFNULL(i.bought_quantity, 0.00) AS bought_quantity, 
            IFNULL(i.bought_amount, 0.00) AS bought_amount, 
            IFNULL(i.remaining_quantity, 0.00) AS remaining_quantity, 
            IFNULL(i.remaining_price, 0.00) AS remaining_price 
            FROM house_item_list hil
            LEFT JOIN market_price_list mpl ON hil.market_item_id = mpl.id
            LEFT JOIN inventory i ON hil.market_item_id = i.market_item_id
            WHERE hil.month = '$month_year'";

    // Debugging: Output the SQL query
    echo "<!-- SQL: $sql -->";

    $result = $conn->query($sql);

    // Debugging: Check if the query was successful
    if ($result === FALSE) {
        echo "<!-- SQL Error (House): " . $conn->error . " -->";
    }

    $total_price = 0;
    $total_bought_amount = 0;
    $total_remaining_amount = 0;

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $total_price += $row['total_price'];
            $total_bought_amount += $row['bought_amount'];
            $total_remaining_amount += $row['remaining_price'];

            echo "<tr>";
            echo "<td>" . $row['item_name'] . "</td>";
            echo "<td>" . number_format($row['required_quantity'], 2) . "</td>";
            echo "<td>LKR " . number_format($row['total_price'], 2) . "</td>";
            echo "<td>" . number_format($row['bought_quantity'], 2) . "</td>";
            echo "<td>LKR " . number_format($row['bought_amount'], 2) . "</td>";
            echo "<td>" . number_format($row['remaining_quantity'], 2) . "</td>";
            echo "<td>LKR " . number_format($row['remaining_price'], 2) . "</td>";
            echo "<td><form method='post'><input type='hidden' name='delete_id' value='" . $row['id'] . "'><button type='submit'>Delete</button></form></td>";
            echo "<td><button onclick=\"editHouseItem('" . $row['id'] . "')\">Edit</button></td>";
            echo "</tr>";
        }
        echo "<tr>";
        echo "<td><strong>Totals</strong></td>";
        echo "<td></td>";
        echo "<td><strong>LKR " . number_format($total_price, 2) . "</strong></td>";
        echo "<td></td>";
        echo "<td><strong>LKR " . number_format($total_bought_amount, 2) . "</strong></td>";
        echo "<td></td>";
        echo "<td><strong>LKR " . number_format($total_remaining_amount, 2) . "</strong></td>";
        echo "<td></td>";
        echo "</tr>";
    } else {
        echo "<tr><td colspan='9'>No items found for the selected month.</td></tr>";
    }
}

// Handle month selection
$month = isset($_POST['month']) ? $_POST['month'] : date('Y-m');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Lists</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Market Price List</h1>

        <!-- Month Selection Form -->
        <form method="post">
            <label for="month">Month:</label>
            <input type="month" name="month" id="month" value="<?php echo $month; ?>">
            <button type="submit">Filter</button>
        </form>

        <table>
            <tr>
                <th>Item Name</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Action</th>
                <th>Edit</th>
            </tr>
            <?php displayMarketItems($conn, $month); ?>
        </table>
        <button onclick="duplicateList('<?php echo $month; ?>')">Duplicate Market Price List</button>

        <h1>House Item List</h1>

        <table>
            <tr>
                <th>Item Name</th>
                <th>Required Quantity</th>
                <th>Total Price</th>
                <th>Bought Quantity</th>
                <th>Bought Amount</th>
                <th>Remaining Quantity</th>
                <th>Remaining Amount</th>
                <th>Action</th>
                <th>Edit</th>
            </tr>
            <?php displayHouseItems($conn, $month); ?>
        </table>
    </div>
    <script>
    function duplicateList(monthYear) {
        window.location.href = `duplicate_list.php?monthYear=${monthYear}`;
    }

    function editItem(itemId) {
        window.location.href = `edit_item.php?id=${itemId}`;
    }

    function editHouseItem(itemId) {
        window.location.href = `edit_house_item.php?id=${itemId}`;
    }
    </script>
</body>
</html>
