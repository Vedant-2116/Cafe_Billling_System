<?php
include('connection.php');

// Initialize product with default values
$product = ['item_name' => '', 'price' => '', 'category' => ''];

// Check if product name is provided in the query string
if (isset($_GET['item_name'])) {
    $product_name = urldecode($_GET['item_name']); // Decode the URL-encoded product name

    // Fetch the details of the product
    $result = $conn->query("SELECT * FROM menu_items WHERE item_name = '$product_name'");

    if ($result) {
        // Check if the product exists
        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
        } else {
            echo "Product not found.";
            exit();
        }
    } else {
        echo "Error retrieving product details. Error: " . $conn->error;
        exit();
    }
}

// Print product details for debugging
// echo "Debugging Information:<br>";
// echo "Product Name: " . $product['item_name'] . "<br>";
// echo "Price: " . $product['price'] . "<br>";
// echo "Category: " . $product['category'] . "<br>";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
    // Get the updated details from the form
    $new_price = $_POST['price'];
    $new_category = $_POST['category'];

    // Print updated details for debugging
    echo "<br>Updated Information:<br>";
    echo "Price: " . $new_price . "<br>";
    echo "Category: " . $new_category . "<br>";

    // Validate and update the product in the database
    if (!empty($new_price) && !empty($new_category)) {
        $conn->query("UPDATE menu_items SET price = '$new_price', category = '$new_category' WHERE item_name = '$product_name'");

        // Redirect to the product list page or any other page as needed
        header("Location: product.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- Add any additional CSS styling if needed -->
</head>

<body>

    <div class="container">
        <h2>Edit Product: <?php echo $product['item_name']; ?></h2>

        <form method="post" action="">
            <div class="form-group">
                <label for="item_name">Product Name:</label>
                <input type="text" id="item_name" name="item_name" class="form-control" value="<?php echo $product['item_name']; ?>" readonly>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="text" id="price" name="price" class="form-control" value="<?php echo $product['price']; ?>" placeholder="Enter new price" required>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" id="category" name="category" class="form-control" value="<?php echo $product['category']; ?>" placeholder="Enter new category" required>
            </div>

            <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
        </form>
    </div>

</body>

</html>
