<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract data from the AJAX request
    $totalAmount = $_POST['totalAmount'];
    $amountPaid = $_POST['amountPaid'];
    $tenderedAmount = $_POST['tenderedAmount'];
    $billItems = json_decode($_POST['billItems'], true);

    // Insert the bill into the 'bills' table
    $insertBillQuery = "INSERT INTO bills (order_id, total_amount) VALUES (NULL, $totalAmount)";
    $conn->query($insertBillQuery);

    // Get the last inserted order ID
    $orderId = $conn->insert_id;

    // Insert individual items into 'bill_items' table
    foreach ($billItems as $item) {
        $itemName = $item['name'];
        $quantity = $item['quantity'];
        $itemTotalAmount = $item['totalAmount'];

        $insertItemQuery = "INSERT INTO bill_items (order_id, item_name, quantity, total_amount) VALUES ($orderId, '$itemName', $quantity, $itemTotalAmount)";
        $conn->query($insertItemQuery);
    }

    echo 'Success'; // Response to AJAX request
} else {
    // Handle invalid requests
    echo 'Invalid Request';
}
?>
