<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderId'])) {
    // Sanitize the input
    $orderId = $conn->real_escape_string($_POST['orderId']);

    // Delete associated records from order_items table
    $deleteOrderItemsQuery = "DELETE FROM order_items WHERE order_id = '$orderId'";
    if ($conn->query($deleteOrderItemsQuery)) {
        // If deletion from order_items table was successful, delete the order from orders table
        $deleteOrderQuery = "DELETE FROM orders WHERE order_id = '$orderId'";
        if ($conn->query($deleteOrderQuery)) {
            // If the deletion was successful, return a success response
            echo 1;
            exit();
        } else {
            // If there was an error deleting the order, return an error response
            echo "Error deleting order: " . $conn->error;
            exit();
        }
    } else {
        // If there was an error deleting associated records, return an error response
        echo "Error deleting associated records: " . $conn->error;
        exit();
    }
} else {
    // If the request method is not POST or the orderId is not set, return an error response
    echo "Invalid request";
    exit();
}
?>


