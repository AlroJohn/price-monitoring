<?php
include('../../includes/connection.php');

if (!isset($_GET['shop_id']) || !isset($_GET['product_code'])) {
    echo "<p class='text-red-500 text-center mt-10'>Invalid product details.</p>";
    exit();
}

$shop_id = mysqli_real_escape_string($db, $_GET['shop_id']);
$product_code = mysqli_real_escape_string($db, $_GET['product_code']);

// Fetch product details with store and owner info
$productQuery = "SELECT p.*, s.SHOP_NAME, s.ADDRESS, s.IMAGE AS STORE_IMG, s.AVAILABILITY,
                        o.FIRST_NAME, o.LAST_NAME, o.PHONE_NUMBER, o.OWNER_ID, o.LOCATION_ID,
                        l.STREET, l.PUROK, l.BARANGAY
                 FROM product p
                 JOIN stores s ON p.SHOP_ID = s.SHOP_ID
                 JOIN owners o ON s.SHOP_ID = o.SHOP_ID
                 LEFT JOIN location l ON o.LOCATION_ID = l.LOCATION_ID
                 WHERE p.SHOP_ID = '$shop_id' AND p.PRODUCT_CODE = '$product_code'
                 LIMIT 1";

$productResult = mysqli_query($db, $productQuery);
$product = mysqli_fetch_assoc($productResult);

if (!$product) {
    echo "<p class='text-red-500 text-center mt-10'>Product not found.</p>";
    exit();
}

// Handle product image
$prodImg = "../../assets/product_img/{$product['IMAGE']}";
if (!file_exists($prodImg) || empty($product['IMAGE'])) {
    $prodImg = "../../assets/product_img/no_img.jpg";
}

// Handle store image
$storeImg = "../../assets/store_img/{$product['STORE_IMG']}";
if (!file_exists($storeImg) || empty($product['STORE_IMG'])) {
    $storeImg = "../../assets/store_img/no_img.jpg";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['NAME']); ?> - Product Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="font-sans bg-gray-100">

    <!-- Include Topbar -->
    <?php include '../../user/includes/topbar.php'; ?>

    <section class="container mx-auto my-10 px-4">

        <!-- Back Button -->
        <div class="mb-6">
            <a href="index.php"
                class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-700 flex items-center w-fit">
                <i class="fas fa-reply mr-2"></i> Back
            </a>
        </div>

        <!-- Product Details -->
        <div class="bg-white p-6 shadow-lg rounded-lg flex flex-col md:flex-row">

            <!-- Left Column: Product Image -->
            <div class="w-full md:w-1/2 flex justify-center">
                <img src="<?php echo $prodImg; ?>" alt="Product Image" class="h-80 w-80 object-cover rounded-lg shadow">
            </div>

            <!-- Right Column: Product Details -->
            <div class="w-full md:w-1/2 px-4 flex flex-col justify-center">
                <h2 class="text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($product['NAME']); ?></h2>
                <p class="text-gray-600 mt-2 italic"><?php echo htmlspecialchars($product['DESCRIPTION']); ?></p>

                <p class="text-4xl font-bold text-blue-500 mt-4">₱<?php echo number_format($product['PRICE'], 2); ?></p>

                <!-- Action Buttons in One Row (Two Columns) -->
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <a href="store.php?shop_id=<?php echo $product['SHOP_ID']; ?>"
                        class="text-center bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">
                        Visit Store
                    </a>
                    <a href="reserve.php?shop_id=<?php echo $shop_id; ?>&product_code=<?php echo $product['PRODUCT_CODE']; ?>"
                        class="text-center bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600">
                        Reserve
                    </a>

                </div>
            </div>
        </div>

        <!-- Store Information -->
        <div class="mt-10 bg-gray-50 p-6 shadow-lg rounded-lg grid grid-cols-4 items-center gap-4">

            <!-- Store Image + Shop Name & Availability in One Flex Container -->
            <div class="flex items-center space-x-4 col-span-2">
                <img src="<?php echo $storeImg; ?>" alt="Store Logo" class="h-16 w-16 object-cover rounded-lg shadow">
                <div>
                    <h3 class="text-2xl font-bold text-gray-800 leading-tight">
                        <?php echo htmlspecialchars($product['SHOP_NAME']); ?>
                    </h3>
                    <p
                        class="mt-1 text-<?php echo $product['AVAILABILITY'] == 'Open' ? 'green' : 'red'; ?>-500 font-semibold">
                        <?php echo $product['AVAILABILITY']; ?>
                    </p>
                </div>
            </div>

            <!-- Address -->
            <div>
                <h4 class="text-lg font-semibold text-gray-800">Address</h4>
                <p class="text-gray-600">
                    <?php echo htmlspecialchars($product['PUROK']) . ', ' . htmlspecialchars($product['BARANGAY']) . ', Daraga, Albay'; ?>
                </p>
            </div>

            <!-- Visit Shop Button (Right-Aligned) -->
            <div class="text-right">
                <a href="store.php?shop_id=<?php echo $product['SHOP_ID']; ?>"
                    class="bg-blue-500 text-white px-4 py-2 rounded shadow hover:bg-blue-600">
                    Visit Shop
                </a>
            </div>

        </div>


    </section>

</body>

</html>