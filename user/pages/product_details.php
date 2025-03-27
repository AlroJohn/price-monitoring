<?php
include('../../includes/connection.php');

if (!isset($_GET['shop_id']) || !isset($_GET['product_code'])) {
    echo "<p class='text-red-500 text-center mt-10'>Invalid product details.</p>";
    exit();
}

$shop_id = mysqli_real_escape_string($db, $_GET['shop_id']);
$product_code = mysqli_real_escape_string($db, $_GET['product_code']);

// Fetch product details with store and owner info
$productQuery = "SELECT p.*, s.SHOP_NAME, s.ADDRESS, s.IMAGE AS STORE_IMG, s.AVAILABILITY, s.TIME_OPEN, s.TIME_CLOSE,
                        o.FIRST_NAME, o.LAST_NAME, o.PHONE_NUMBER, o.EMAIL, o.OWNER_ID, o.LOCATION_ID,
                        l.STREET, l.PUROK, l.BARANGAY,
                        st.CATEGORY AS STORE_CATEGORY
                 FROM product p
                 JOIN stores s ON p.SHOP_ID = s.SHOP_ID
                 JOIN owners o ON s.SHOP_ID = o.SHOP_ID
                 LEFT JOIN location l ON o.LOCATION_ID = l.LOCATION_ID
                 LEFT JOIN store_type st ON s.STORE_ID = st.STORE_ID
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

// Format dates
$stockDate = date('F j, Y', strtotime($product['DATE_STOCK_IN']));
$expiryDate = date('F j, Y', strtotime($product['DATE_EXPIRY']));
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
            <a href="javascript:history.back()"
                class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-700 flex items-center w-fit">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
        </div>

        <!-- Product Details -->
        <div class="bg-white p-6 shadow-lg rounded-lg flex flex-col md:flex-row gap-6">

            <!-- Left Column: Product Image -->
            <div class="w-full md:w-1/3 flex justify-center">
                <img src="<?php echo $prodImg; ?>" alt="Product Image" class="h-80 w-80 object-cover rounded-lg shadow">
            </div>

            <!-- Right Column: Product Details -->
            <div class="w-full md:w-2/3">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($product['NAME']); ?></h2>
                        <p class="text-gray-600 mt-2 italic"><?php echo htmlspecialchars($product['DESCRIPTION']); ?></p>
                    </div>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                        <?php echo htmlspecialchars($product['STORE_CATEGORY']); ?>
                    </span>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Product Pricing -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Pricing Information</h3>
                        <div class="space-y-2">
                            <p class="text-4xl font-bold text-blue-500">₱<?php echo number_format($product['PRICE'], 2); ?></p>
                            <p><span class="font-medium">Per:</span> <?php echo htmlspecialchars($product['MEASURE']); ?></p>
                            <p><span class="font-medium">Available Stock:</span> <?php echo $product['QTY_STOCK']; ?></p>
                        </div>
                    </div>

                    <!-- Product Identification -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Product Identification</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium">Product Code:</span> <?php echo htmlspecialchars($product['PRODUCT_CODE']); ?></p>
                            <p><span class="font-medium">Product ID:</span> <?php echo $product['PRODUCT_ID']; ?></p>
                            <p><span class="font-medium">Status:</span> 
                                <span class="px-2 py-1 text-xs rounded-full 
                                    <?php echo $product['pro_status'] == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                    <?php echo ucfirst($product['pro_status']); ?>
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Stock Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Stock Information</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium">Date Stock In:</span> <?php echo $stockDate; ?></p>
                            <p><span class="font-medium">Expiry Date:</span> <?php echo $expiryDate; ?></p>
                            <p><span class="font-medium">Quantity in Stock:</span> <?php echo $product['QTY_STOCK']; ?></p>
                        </div>
                    </div>

                    <!-- Store Information -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Store Information</h3>
                        <div class="space-y-2">
                            <p><span class="font-medium">Shop Name:</span> <?php echo htmlspecialchars($product['SHOP_NAME']); ?></p>
                            <p><span class="font-medium">Category:</span> <?php echo htmlspecialchars($product['STORE_CATEGORY']); ?></p>
                            <p><span class="font-medium">Availability:</span> 
                                <span class="font-semibold <?php echo $product['AVAILABILITY'] == 'Open' ? 'text-green-600' : 'text-red-600'; ?>">
                                    <?php echo $product['AVAILABILITY']; ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex flex-wrap gap-4">
                    <a href="store.php?shop_id=<?php echo $product['SHOP_ID']; ?>"
                        class="flex-1 md:flex-none bg-blue-500 text-white px-6 py-3 rounded shadow hover:bg-blue-600 flex items-center justify-center">
                        <i class="fas fa-store mr-2"></i> Visit Store
                    </a>
                    <a href="reserve_index.php?shop_id=<?php echo $shop_id; ?>&product_code=<?php echo $product['PRODUCT_CODE']; ?>"
                        class="flex-1 md:flex-none bg-green-500 text-white px-6 py-3 rounded shadow hover:bg-green-600 flex items-center justify-center">
                        <i class="fas fa-calendar-check mr-2"></i> Reserve Product
                    </a>
                </div>
            </div>
        </div>

        <!-- Detailed Store Information -->
        <div class="mt-10 bg-white p-6 shadow-lg rounded-lg">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Store Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Store Image and Basic Info -->
                <div class="flex items-start space-x-4">
                    <img src="<?php echo $storeImg; ?>" alt="Store Logo" class="h-24 w-24 object-cover rounded-lg shadow">
                    <div>
                        <h4 class="text-xl font-bold text-gray-800"><?php echo htmlspecialchars($product['SHOP_NAME']); ?></h4>
                        <p class="text-sm text-gray-600"><?php echo htmlspecialchars($product['STORE_CATEGORY']); ?></p>
                        <p class="mt-1">
                            <span class="px-2 py-1 text-xs rounded-full 
                                <?php echo $product['AVAILABILITY'] == 'Open' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo $product['AVAILABILITY']; ?>
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Contact Information</h4>
                    <div class="space-y-2">
                        <p><i class="fas fa-user mr-2 text-gray-600"></i> <?php echo htmlspecialchars($product['FIRST_NAME'] . ' ' . $product['LAST_NAME']); ?></p>
                        <p><i class="fas fa-phone mr-2 text-gray-600"></i> <?php echo htmlspecialchars($product['PHONE_NUMBER']); ?></p>
                        <p><i class="fas fa-envelope mr-2 text-gray-600"></i> <?php echo htmlspecialchars($product['EMAIL']); ?></p>
                    </div>
                </div>

                <!-- Location and Hours -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-lg font-semibold text-gray-800 border-b pb-2 mb-3">Location & Hours</h4>
                    <div class="space-y-2">
                        <p><i class="fas fa-map-marker-alt mr-2 text-gray-600"></i> 
                            <?php echo htmlspecialchars($product['PUROK'] . ', ' . $product['BARANGAY'] . ', Daraga, Albay'); ?>
                        </p>
                        <p><i class="fas fa-clock mr-2 text-gray-600"></i> 
                            <?php echo htmlspecialchars($product['TIME_OPEN'] . ' - ' . $product['TIME_CLOSE']); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Map and Additional Actions -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Map Placeholder -->
                <div class="bg-gray-200 h-64 rounded-lg flex items-center justify-center">
                    <p class="text-gray-500">Map would be displayed here</p>
                </div>
                
                <!-- Additional Actions -->
                <div class="flex flex-col space-y-4">
                    <a href="store.php?shop_id=<?php echo $product['SHOP_ID']; ?>"
                        class="bg-blue-500 text-white px-6 py-3 rounded shadow hover:bg-blue-600 text-center">
                        View All Products from This Store
                    </a>
                    <a href="reserve_index.php?shop_id=<?php echo $shop_id; ?>&product_code=<?php echo $product['PRODUCT_CODE']; ?>"
                        class="bg-green-500 text-white px-6 py-3 rounded shadow hover:bg-green-600 text-center">
                        Make a Reservation
                    </a>
                </div>
            </div>
        </div>

    </section>

</body>

</html>