<?php
include('connection.php');

if (isset($_GET['totalAmount'])) {
    $totalAmount = floatval($_GET['totalAmount']);
    $orderID = generateOrderID(); // You need to implement a function to generate a unique order ID
    $paymentStatus = 'paid';
    
    // Insert the bill information into the database
    $conn->query("INSERT INTO bills (order_id, total_amount, payment_status) VALUES ('$orderID', $totalAmount, '$paymentStatus')");
    
    echo 'Bill stored successfully!';
} else {
    echo 'Invalid request!';
}

function generateOrderID() {
    return date('YmdHis') . rand(1000, 9999);
}
?>
