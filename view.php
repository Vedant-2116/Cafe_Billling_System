<?php
include('connection.php');

if(isset($_GET['id'])) {
    $order_id = $_GET['id'];
    $order = $conn->query("SELECT * FROM orders WHERE order_id = $order_id")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #dee2e6;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Order Details</h1>
        
        <table>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Order Number</td>
                <td><?php echo $order['order_number']; ?></td>
            </tr>
            <tr>
                <td>Total Amount</td>
                <td><?php echo number_format($order['total_amount'], 2); ?></td>
            </tr>
            <tr>
                <td>Payment Status</td>
                <td><?php echo $order['payment_status'] == 1 ? 'Paid' : 'Unpaid'; ?></td>
            </tr>
            <!-- Add more fields as needed -->
        </table>

        <div class="text-center mt-4">
            <button class="btn btn-primary" onclick="location.href='order.php'">Back to Orders</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>
