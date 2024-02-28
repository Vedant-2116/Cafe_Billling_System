<?php
include('connection.php');

if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $order = $conn->query("SELECT * FROM orders WHERE order_id = $order_id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Order</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            max-width: 600px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #007bff;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            text-align: left;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        .print-button {
            margin-top: 20px;
        }

        .logo {
            display: inline-block;
            width: 60px;
            height: 60px;
            background-color: #007bff;
            border-radius: 50%;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="logo"></div>
        <h1>Order Details</h1>

        <table>
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Order Number</td>
                    <td><?php echo $order['order_number']; ?></td>
                </tr>
                <tr>
                    <td>Total Amount</td>
                    <td><?php echo number_format($order['total_amount'], 2); ?></td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
        <button class="btn btn-primary print-button" onclick="window.print()">Print Order</button>
        <button class="btn btn-primary" onclick="location.href='order.php'">Back to Orders</button>
    </div>

</body>

</html>

