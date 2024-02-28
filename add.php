<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $order_number = $_POST['order_number'];
    $total_amount = $_POST['total_amount'];
    $payment_status = $_POST['payment_status'];

    // Insert the new order into the database
    $conn->query("INSERT INTO orders (order_number, total_amount, payment_status) VALUES ('$order_number', '$total_amount', '$payment_status')");

    // Redirect to order.php or any other page after adding the order
    header("Location: order.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Order</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            max-width: 400px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            color: #333;
        }

        input,
        select {
            padding: 10px;
            margin-bottom: 16px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            padding: 12px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Add New Order</h1>
        
        <form method="post" action="">
        <label for="invoice_number">Invoice Number:</label>
            <input type="text" id="invoice_number" name="invoice_number" required>

            <label for="order_number">Order Number:</label>
            <input type="text" id="order_number" name="order_number" required>

            <label for="total_amount">Total Amount:</label>
            <input type="text" id="total_amount" name="total_amount" required>

            <!-- Payment Status Field -->
            <label for="payment_status">Payment Status:</label>
            <select id="payment_status" name="payment_status">
                <option value="1">Paid</option>
                <option value="0">Unpaid</option>
            </select>

            <!-- Add more form fields as needed -->

            <button type="submit">Add Order</button>
        </form>
    </div>

</body>

</html>
