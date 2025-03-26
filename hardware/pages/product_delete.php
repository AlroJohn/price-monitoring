<?php
session_start();
include '../../includes/connection.php';

// Check if the product ID is passed in the URL
if (isset($_GET['id'])) {
    $product_id = mysqli_real_escape_string($db, $_GET['id']);

    // Fetch the product details from the database
    $query = "SELECT * FROM product WHERE PRODUCT_ID = '$product_id'";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "Product not found.";
        exit();
    }
} else {
    echo "Product ID is missing.";
    exit();
}

// Handle the form submission for deleting products
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $qty_to_delete = mysqli_real_escape_string($db, $_POST['qty_to_delete']);

    // Check if the quantity to delete is valid
    if ($qty_to_delete > 0 && $qty_to_delete <= $product['QTY_STOCK']) {
        // Calculate new stock quantity
        $new_qty_stock = $product['QTY_STOCK'] - $qty_to_delete;

        if ($new_qty_stock == 0) {
            // Delete the product if stock reaches zero
            $delete_query = "DELETE FROM product WHERE PRODUCT_ID = '$product_id'";
            if (mysqli_query($db, $delete_query)) {
                header("Location: products.php?success=deleted");
                exit();
            } else {
                echo "Error deleting product: " . mysqli_error($db);
            }
        } else {
            // Update the stock quantity
            $update_query = "UPDATE product SET QTY_STOCK = '$new_qty_stock' WHERE PRODUCT_ID = '$product_id'";
            if (mysqli_query($db, $update_query)) {
                header("Location: products.php?success=updated");
                exit();
            } else {
                echo "Error updating product: " . mysqli_error($db);
            }
        }
    } else {
        echo "Invalid quantity to delete.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <!-- Modal -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-8 rounded-lg shadow-xl w-96">
            <h2 class="text-xl font-bold mb-4 text-center">Delete Product Quantity</h2>

            <form action="product_delete.php?id=<?php echo $product['PRODUCT_ID']; ?>" method="POST">
                <p class="mb-4">Product: <?php echo $product['NAME']; ?> (Current Stock:
                    <?php echo $product['QTY_STOCK']; ?>)
                </p>

                <div class="mb-4">
                    <label for="qty_to_delete" class="block text-sm text-gray-600 mb-2">Enter Quantity to Delete</label>
                    <input type="number" id="qty_to_delete" name="qty_to_delete" min="1"
                        max="<?php echo $product['QTY_STOCK']; ?>" class="border border-gray-300 rounded-md p-2 w-full"
                        required>
                    <p class="text-sm text-gray-500 mt-2">Max quantity: <?php echo $product['QTY_STOCK']; ?></p>
                </div>

                <div class="flex justify-between">
                    <!-- Cancel Button -->
                    <a href="products.php"
                        class="bg-gray-500 text-white py-2 px-6 rounded-md hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <!-- Delete Button -->
                    <button type="submit"
                        class="bg-red-600 text-white py-2 px-6 rounded-md hover:bg-red-700 transition">
                        Delete Product
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>