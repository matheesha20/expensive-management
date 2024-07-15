<?php
include("config.php");

$item_type = $_GET['type'];

switch ($item_type) {
    case 'personal_expense':
        $result = $conn->query("SELECT id, item FROM personal_expenses");
        while ($row = $result->fetch_assoc()) {
            echo "<input type='checkbox' name='item_ids[]' value='".$row['id']."'>".$row['item']."<br>";
        }
        break;
    case 'bank_deposit':
        $result = $conn->query("SELECT id, bank FROM bank_deposits");
        while ($row = $result->fetch_assoc()) {
            echo "<input type='checkbox' name='item_ids[]' value='".$row['id']."'>".$row['bank']."<br>";
        }
        break;
    case 'loan':
        $result = $conn->query("SELECT id, company_person FROM loans");
        while ($row = $result->fetch_assoc()) {
            echo "<input type='checkbox' name='item_ids[]' value='".$row['id']."'>".$row['company_person']."<br>";
        }
        break;
    case 'house_item':
        $result = $conn->query("SELECT hil.id, mpl.item_name 
                                FROM house_item_list hil 
                                JOIN market_price_list mpl 
                                ON hil.market_item_id = mpl.id");
        while ($row = $result->fetch_assoc()) {
            echo "<input type='checkbox' name='item_ids[]' value='".$row['id']."'>".$row['item_name']."<br>";
        }
        break;
    default:
        echo "Please select an item type.";
}
?>
