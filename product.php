<?php
include('connection.php');

// Handle product addition logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $item_name = $_POST['item_name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    // Validate and insert into the database
    if (!empty($item_name) && !empty($price) && !empty($category)) {
        $insertQuery = "INSERT INTO menu_items (item_name, price, category) VALUES ('$item_name', '$price', '$category')";
        if ($conn->query($insertQuery)) {
            header("Location: product.php");
            exit();
        } else {
            echo "Error inserting into the database: " . $conn->error;
        }
    } else {
        echo "Please fill in all fields.";
    }
}


// Fetch products
$products = $conn->query("SELECT * FROM menu_items");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .container {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            width: 70%;
            max-height: 80vh; /* Maximum height for scrollability */
            overflow-y: auto; /* Enable vertical scroll */
        }

        .product-card {
            width: calc(20% - 20px); /* 20% to have 5 cards in a row with a gap of 20px */
            border: 1px solid #007bff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
            text-decoration: none;
            color: #333;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .product-card:hover {
            background-color: #f2f2f2;
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

        form {
            width: 28%;
            border: 1px solid #007bff;
            border-radius: 8px;
            padding: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 16px;
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
        <div class="product-container">
            <?php while ($row = $products->fetch_assoc()) : ?>
                <form action="pro_edit.php" method="get" class="product-card" style="cursor: pointer;" onclick="this.submit();">
                    <input type="hidden" name="item_name" value="<?php echo urlencode($row['item_name']); ?>">
                    <h3 style="font-size: 16px;"><?php echo $row['item_name']; ?></h3>
                    <p><strong>Price:</strong> <?php echo number_format($row['price'], 2); ?></p>
                    <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
                </form>
            <?php endwhile; ?>
        </div>

        <form method="post" action="">
            <label for="item_name">Product Name:</label>
            <input type="text" id="item_name" name="item_name" required>

            <label for="price">Price:</label>
            <input type="text" id="price" name="price" required>

            <label for="category">Category:</label>
            <input type="text" id="category" name="category" required>

            <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
        </form>
    </div>

</body>

</html>
