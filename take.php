<?php
include('connection.php');

// Fetch categories
$categories = $conn->query("SELECT DISTINCT category FROM menu_items");

// Fetch products for all categories initially
$allProducts = $conn->query("SELECT * FROM menu_items");

if (isset($_GET['category'])) {
    $category = $_GET['category'];
    if ($category === 'All') {
        // If 'All' is selected, fetch all products without filtering by category
        $productsQuery = "SELECT * FROM menu_items";
    } else {
        // Fetch products based on the selected category
        $category = $conn->real_escape_string($category); // Sanitize input
        $productsQuery = "SELECT * FROM menu_items WHERE category = '$category'";
    }
    $productsResult = $conn->query($productsQuery);
    $products = array();
    while ($row = $productsResult->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode($products);
    exit(); // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Order</title>
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .container {
            display: flex;
            justify-content: space-between;
            margin: 20px;
        }

        .category-container {
            width: 20%;
        }

        .category-button {
            width: 100%;
            margin-bottom: 10px;
        }

        .product-container {
            width: 50%;
            overflow-y: auto;
            height: 400px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .product-button {
            width: calc(33.33% - 10px);
            border: 1px solid #007bff;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            text-align: center;
            text-decoration: none;
            color: #333;
            cursor: pointer;
        }

        .product-button:hover {
            background-color: #f2f2f2;
        }

        .bill-container {
            width: 28%;
            border: 1px solid #007bff;
            border-radius: 8px;
            padding: 20px;
            background-color: #fff; /* Background color inside the bill container */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Box shadow for a subtle lift */
        }

        .bill-header {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .bill-order-no {
            text-align: center;
            margin-bottom: 20px;
            color: #555; /* Color for the order number */
        }

        .bill-items {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .bill-item {
            display: flex;
            justify-content: space-between;
        }

        .bill-total {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-weight: bold; /* Bold font for total amount */
        }

        .pay-button {
            margin-top: 20px;
            padding: 12px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .pay-button:hover {
            background-color: #45a049;
        }
    </style>

</head>

<body>
                            <div class="col-md-6 text-right">
                			<!-- Add Order button -->
                				<button class="btn btn-primary" onclick="location.href='order.php'">View Order</button>
                                <button class="btn btn-primary" onclick="location.href='bills.php'">View Bills</button>
            				</div>
    <div class="container">
        <div class="category-container">
            <button class="category-button" onclick="showCategory('All')">All</button>
            <?php while ($category = $categories->fetch_assoc()) : ?>
                <button class="category-button" onclick="showCategory('<?php echo $category['category']; ?>')">
                    <?php echo $category['category']; ?>
                </button>
            <?php endwhile; ?>
        </div>

        <div class="product-container" id="productContainer">
            <?php
            // Resetting the categories query
            $categories->data_seek(0);
            while ($product = $allProducts->fetch_assoc()) :
            ?>
                <button class="product-button" onclick="addToBill('<?php echo $product['item_name']; ?>', <?php echo $product['price']; ?>)">
                    <h3 style="font-size: 14px;"><?php echo $product['item_name']; ?></h3>
                    <p><strong>Price:</strong> <?php echo number_format($product['price'], 2); ?></p>
                </button>
            <?php endwhile; ?>
        </div>
        <div class="bill-container" id="billContainer">
            <div class="bill-header">
                Bill
            </div>
            <div class="bill-order-no">
                Order No: <?php echo date('YmdHis'); ?>
            </div>
            <div class="bill-items" id="billItemsContainer">
                <!-- Bill items will be dynamically added here -->
            </div>
            <div class="bill-total">
                <p>Total:</p>
                <p id="totalAmountBill">0.00</p>
            </div>
            <button class="pay-button" onclick="pay()">Pay</button>
        </div>
    </div>

    <script>
        var billItems = [];
        var totalAmount = 0;

        function showCategory(category) {
    var productContainer = document.getElementById('productContainer');
    productContainer.innerHTML = ''; // Clear previous products
    
    // Construct the URL for fetching products based on the selected category
    var url = 'take.php';
    if (category == 'All') {
        url += '?category=' + encodeURIComponent(category);
    }
    if (category !== 'All') {
        url += '?category=' + encodeURIComponent(category);
    }

    // Fetch products based on the selected category
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var products = JSON.parse(xhr.responseText);
                products.forEach(function(product) {
                    var productButton = createProductButton(product);
                    productContainer.appendChild(productButton);
                });
            } else {
                console.error('Error fetching products:', xhr.status, xhr.statusText);
                // Optionally, display an error message to the user
            }
        }
    };
    xhr.send();
}
    function createProductButton(product) {
        var productButton = document.createElement('button');
        productButton.classList.add('product-button');
        productButton.onclick = function () {
            addToBill(product.item_name, product.price);
        };

        var productName = document.createElement('h3');
        productName.style.fontSize = '14px';
        productName.textContent = product.item_name;

        var price = document.createElement('p');
        price.innerHTML = '<strong>Price:</strong> ' + Number(product.price).toFixed(2);

        productButton.appendChild(productName);
        productButton.appendChild(price);

        return productButton;
    }

        function addToBill(productName, productPrice) {
            var quantity = prompt("Enter quantity:", "1");
            if (quantity !== null && !isNaN(quantity) && quantity > 0) {
                var totalAmountProduct = quantity * productPrice;
                var item = {
                    name: productName,
                    price: productPrice,
                    quantity: quantity,
                    totalAmount: totalAmountProduct
                };

                // Add the item to the bill
                billItems.push(item);

                // Update the bill items
                updateBill();
            }
        }

        function updateBill() {
            var billItemsContainer = document.getElementById('billItemsContainer');
            billItemsContainer.innerHTML = '';

            billItems.forEach(function (item) {
                var billItemContainer = document.createElement('div');
                billItemContainer.classList.add('bill-item');

                var quantity = document.createElement('span');
                quantity.textContent = 'Qty: ' + item.quantity;

                var name = document.createElement('span');
                name.textContent = 'Name: ' + item.name;

                var price = document.createElement('span');
                price.textContent = 'Price: $' + item.totalAmount.toFixed(2);

                billItemContainer.appendChild(quantity);
                billItemContainer.appendChild(name);
                billItemContainer.appendChild(price);

                billItemsContainer.appendChild(billItemContainer);

                // Update total amount for the bill
                totalAmount += item.totalAmount;
            });

            // Display total amount for the bill
            var totalAmountBillElement = document.getElementById('totalAmountBill');
            totalAmountBillElement.textContent = totalAmount.toFixed(2);
        }

        function pay() {
            if (billItems.length > 0) {
                var amountPaid = parseFloat(prompt('Enter the amount paid:'));
                var tenderedAmount = parseFloat(prompt('Enter the tendered amount:'));

                if (isNaN(amountPaid) || isNaN(tenderedAmount) || amountPaid < 0 || tenderedAmount < totalAmount) {
                    alert('Invalid payment. Please enter valid amounts.');
                } else {
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'store_bill.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            alert('Payment Successful!\nTotal Amount: $' + totalAmount.toFixed(2) +
                                '\nAmount Paid: $' + amountPaid.toFixed(2) +
                                '\nChange: $' + (tenderedAmount - totalAmount).toFixed(2));
                            billItems = [];
                            totalAmount = 0;
                            updateBill();
                        } else {
                            alert('Failed to store the bill. Please try again.');
                        }
                    };

                    var params = 'totalAmount=' + totalAmount +
                        '&amountPaid=' + amountPaid +
                        '&tenderedAmount=' + tenderedAmount +
                        '&billItems=' + encodeURIComponent(JSON.stringify(billItems));

                    xhr.send(params);
                }
            } else {
                alert('Please add items to the bill before making a payment.');
            }
        }
    </script>

</body>

</html>
