<?php
include('connection.php');

// Fetch bills
$bills = $conn->query("SELECT * FROM bills");

// Handle bill status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_status"])) {
    $billId = $_POST["bill_id"];
    $newStatus = $_POST["new_status"];

    // Update the bill status in the database
    $updateQuery = "UPDATE bills SET payment_status = '$newStatus' WHERE bill_id = $billId";
    $conn->query($updateQuery);

    // Redirect to the same page after updating the status
    header("Location: $_SERVER[PHP_SELF]");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bills</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .container {
            margin: 20px;
        }

        .bill-container {
            width: 70%;
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .status-buttons button {
            margin-right: 5px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Bills</h2>
        <div class="col-md-6 text-right">
            <!-- Add button -->
            <button class="btn btn-primary" onclick="location.href='order.php'">View Order</button>
            <button class="btn btn-primary" onclick="location.href='take.php'">Take Order</button>
        </div>
        <div class="bill-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Total Amount</th>
                        <th>Payment Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $bills->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['bill_id']; ?></td>
                            <td><?php echo number_format($row['total_amount'], 2); ?></td>
                            <td><?php echo $row['payment_status']; ?></td>
                            <td><?php echo $row['bill_date']; ?></td>
                            <td class="status-buttons">
                                <form method="post">
                                    <input type="hidden" name="bill_id" value="<?php echo $row['bill_id']; ?>">
                                    <input type="hidden" name="new_status" value="Paid">
                                    <button type="submit" class="btn btn-sm btn-success" name="update_status">Paid</button>
                                </form>
                                <form method="post">
                                    <input type="hidden" name="bill_id" value="<?php echo $row['bill_id']; ?>">
                                    <input type="hidden" name="new_status" value="Unpaid">
                                    <button type="submit" class="btn btn-sm btn-danger" name="update_status">Unpaid</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>

