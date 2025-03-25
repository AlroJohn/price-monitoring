<?php
session_start();
// Include the database connection
include '../../includes/connection.php';

// Include Top Bar
include '../include/topbar.php';

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

// Check if the form is submitted to update the product
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_code = mysqli_real_escape_string($db, $_POST['product_code']);
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $qty_stock = mysqli_real_escape_string($db, $_POST['qty_stock']);
    $measure = mysqli_real_escape_string($db, $_POST['measure']);
    $price = mysqli_real_escape_string($db, $_POST['price']);
    $date_stock_in = mysqli_real_escape_string($db, $_POST['date_stock_in']);
    $date_expiry = mysqli_real_escape_string($db, $_POST['date_expiry']);

    // Handle Image Upload
    $image_path = $product['IMAGE']; // Retain the existing image if no new one is uploaded

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_error = $_FILES['image']['error'];

        // Get the extension of the image file
        $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        // Define allowed file types
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Check if the image has an allowed extension
        if (in_array($image_ext, $allowed_extensions)) {
            // Generate the new image name (NAME + SHOP_ID + PRODUCT_ID)
            $image_name = $name . $product['SHOP_ID'] . $product['PRODUCT_ID'] . '.' . $image_ext;
            $image_path = "../../assets/product_img/" . $image_name;

            // Move the uploaded image to the desired folder
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                // Image upload success
            } else {
                echo "Error uploading the image.";
                exit();
            }
        } else {
            echo "Invalid image type. Only jpg, jpeg, png, gif are allowed.";
            exit();
        }
    }

    // Update the product in the database
    $update_query = "UPDATE product
                     SET PRODUCT_CODE = '$product_code', NAME = '$name', DESCRIPTION = '$description', QTY_STOCK = '$qty_stock', MEASURE = '$measure', PRICE = '$price', DATE_STOCK_IN = '$date_stock_in', DATE_EXPIRY = '$date_expiry', IMAGE = '$image_path'
                     WHERE PRODUCT_ID = '$product_id'";

    if (mysqli_query($db, $update_query)) {
        // Redirect to the products page after updating
        header("Location: products.php?success=true");
        exit();
    } else {
        echo "Error: " . mysqli_error($db);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Heroicons for icons -->
    <script src="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/dist/heroicons.min.js"></script>
</head>

<body class="bg-gray-100"
    style="background-image: url('../../img/choboard.jpg'); background-size: cover; background-position: center;">

    <!-- Container styled as a chopping board -->
    <div
        class="max-w-3xl mx-auto mt-2 p-8 bg-yellow-50 shadow-lg rounded-xl border-4 border-brown-600 bg-gradient-to-b from-yellow-100 to-yellow-300">
        <!-- Form Header -->
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Edit Product</h2>

        <!-- Product Form -->
        <form action="product_edit.php?id=<?php echo $product['PRODUCT_ID']; ?>" method="POST"
            enctype="multipart/form-data">
            <div class="space-y-8">

                <!-- IMAGE: Circular image or default image -->
                <div class="flex justify-center">
                    <div class="w-32 h-32 bg-gray-300 rounded-full flex items-center justify-center">
                        <?php
                        // Check if the image file exists in the specified path
                        $imagePath = $product['IMAGE'];
                        if ($imagePath && !file_exists($imagePath)) {
                            $imagePath = '../../assets/product_img/no_img.jpg'; // Fallback to default image if the file is missing
                        }
                        ?>
                        <img src="<?php echo $imagePath; ?>" alt="Product Image"
                            class="w-24 h-24 object-cover rounded-full">
                    </div>
                </div>


                <!-- Image upload field -->
                <div class="text-center mt-0">
                    <label for="image" class="text-sm text-gray-600">UPLOAD</label>
                    <input type="file" id="image" name="image" class="mt-0">
                </div>

                <!-- Row for Product Code, Name, and Description -->
                <div class="grid grid-cols-3 gap-6 mt-6">
                    <!-- Product Code -->
                    <div class="flex flex-col">
                        <label for="product_code" class="text-sm text-gray-600">Product Code</label>
                        <input type="text" id="product_code" name="product_code"
                            value="<?php echo $product['PRODUCT_CODE']; ?>"
                            class="border border-gray-300 rounded-md p-2 mt-2 text-center w-full" required>
                    </div>
                    <!-- Product Name -->
                    <div class="flex flex-col">
                        <label for="name" class="text-sm text-gray-600">Product Name</label>
                        <input type="text" id="name" name="name" value="<?php echo $product['NAME']; ?>"
                            class="border border-gray-300 rounded-md p-2 mt-2" required>
                    </div>
                    <!-- Description -->
                    <div class="flex flex-col">
                        <label for="description" class="text-sm text-gray-600">Description</label>
                        <textarea id="description" name="description" rows="4"
                            class="border border-gray-300 rounded-md p-2 mt-2"
                            required><?php echo $product['DESCRIPTION']; ?></textarea>
                    </div>
                </div>

                <!-- Row for Stock, Measure, and Price -->
                <div class="grid grid-cols-3 gap-6 mt-6">
                    <!-- Stock Quantity -->
                    <div class="flex flex-col">
                        <label for="qty_stock" class="text-sm text-gray-600">Stock Quantity</label>
                        <input type="number" id="qty_stock" name="qty_stock"
                            value="<?php echo $product['QTY_STOCK']; ?>"
                            class="border border-gray-300 rounded-md p-2 mt-2" required>
                    </div>
                    <!-- Measure -->
                    <div class="flex flex-col">
                        <label for="measure" class="text-sm text-gray-600">Measure</label>
                        <select id="measure" name="measure" class="border border-gray-300 rounded-md p-2 mt-2" required>
                            <option value="kilo" <?php if ($product['MEASURE'] == 'kilo')
                                echo 'selected'; ?>>Kilo</option>
                            <option value="pack" <?php if ($product['MEASURE'] == 'pack')
                                echo 'selected'; ?>>Pack</option>
                            <option value="whole" <?php if ($product['MEASURE'] == 'whole')
                                echo 'selected'; ?>>Whole</option>
                        </select>
                    </div>
                    <!-- Price -->
                    <div class="flex flex-col">
                        <label for="price" class="text-sm text-gray-600">Price</label>
                        <input type="number" id="price" name="price" value="<?php echo $product['PRICE']; ?>"
                            class="border border-gray-300 rounded-md p-2 mt-2" step="0.01" min="0" required>
                    </div>
                </div>

                <!-- Row for Stock In Date and Expiry Date -->
                <div class="grid grid-cols-2 gap-6 mt-6">
                    <!-- Stock In Date -->
                    <div class="flex flex-col">
                        <label for="date_stock_in" class="text-sm text-gray-600">Stock In Date</label>
                        <input type="date" id="date_stock_in" name="date_stock_in"
                            value="<?php echo $product['DATE_STOCK_IN']; ?>"
                            class="border border-gray-300 rounded-md p-2 mt-2" required>
                    </div>
                    <!-- Expiry Date -->
                    <div class="flex flex-col">
                        <label for="date_expiry" class="text-sm text-gray-600">Expiry Date</label>
                        <input type="date" id="date_expiry" name="date_expiry"
                            value="<?php echo $product['DATE_EXPIRY']; ?>"
                            class="border border-gray-300 rounded-md p-2 mt-2" required>
                    </div>
                </div>

                <!-- Buttons: Cancel and Update -->
                <div class="grid grid-cols-2 gap-6 mt-8">
                    <!-- Cancel Button -->
                    <a href="products.php"
                        class="bg-gray-500 text-white text-center py-2 px-6 rounded-md hover:bg-gray-600 transition col-span-1">
                        Cancel
                    </a>
                    <!-- Update Button -->
                    <button type="submit"
                        class="bg-green-600 text-white text-center py-2 px-6 rounded-md hover:bg-green-700 transition col-span-1">
                        Update Product
                    </button>
                </div>

            </div>
        </form>
    </div>


</body>

</html>