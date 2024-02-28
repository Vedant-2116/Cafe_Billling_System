<?php include('connection.php');?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Orders</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .container-fluid {
            margin-bottom: 30px;
        }

        .card {
            border-radius: 10px;
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            border-bottom: 1px solid #dee2e6;
        }

        .btn-outline-primary {
            color: #007bff;
            border-color: #007bff;
        }

        .btn-outline-primary:hover {
            background-color: #007bff;
            color: #fff;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .btn-outline-danger,
        .btn-outline-info,
        .btn-outline-secondary {
            margin-right: 5px;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="col-lg-12">
            <div class="row mb-4 mt-4">
                <div class="col-md-12">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <b>List of Orders</b>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
							<div class="col-md-6 text-right">
                			<!-- Add Order button -->
                				<button class="btn btn-primary" onclick="location.href='add.php'">Add Order</button>
                                <button class="btn btn-primary" onclick="location.href='take.php'">Take Order</button>
                                <button class="btn btn-primary" onclick="location.href='bills.php'">View Bills</button>
            				</div>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Invoice</th>
                                        <th>Order Number</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
                                    while($row = $orders->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><?php echo date("M d, Y", strtotime($row['order_date'])); ?></td>
                                        <td><?php echo $row['invoice']; ?></td>
                                        <td><?php echo $row['order_number']; ?></td>
                                        <td><?php echo number_format($row['total_amount'], 2); ?></td>
                                        <td><?php echo $row['payment_status'] == 1 ? 'Paid' : 'Unpaid'; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" type="button" onclick="location.href='edit.php?id=<?php echo $row['order_id']; ?>'">Edit</button>
                                            <button class="btn btn-sm btn-outline-danger delete_order" type="button" data-id="<?php echo $row['order_id']; ?>">Delete</button>
                                            <button class="btn btn-sm btn-outline-info" type="button" onclick="location.href='view.php?id=<?php echo $row['order_id']; ?>'">View</button>
                                            <button class="btn btn-sm btn-outline-secondary" type="button" onclick="location.href='print.php?id=<?php echo $row['order_id']; ?>'">Print</button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
										
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

	<script>
		$(document).ready(function() {
    // Handle delete button click
    $('.delete_order').click(function() {
        var orderId = $(this).data('id');
        var confirmation = confirm("Are you sure to delete this order?");
        if (confirmation) {
            $.ajax({
                url: 'delete.php',
                method: 'POST',
                data: { orderId: orderId },
                success: function(response) {
                    if (response == 1) {
                        alert("Order successfully deleted");
                        // Reload the page
                        location.reload();
                    } else {
                        alert("Failed to delete order");
                    }
                }
            });
        }
    });
});

	</script>

</body>

</html>
